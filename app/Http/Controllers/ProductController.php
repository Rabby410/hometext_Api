<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductDetailsResource;
use App\Http\Resources\ProductListForBarCodeResource;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ChildSubCategory;
use App\Models\Country;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductListResource;
use App\Models\ProductAttribute;
use App\Models\ProductSpecification;
use App\Models\Shop;
use App\Models\SubCategory;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request, $is_all = 'yes'): AnonymousResourceCollection
    {
        $input = [
            'per_page' => $request->input('per_page'),
            'search' => $request->input('search'),
            'order_by' => $request->input('order_by'),
            'direction' => $request->input('direction'),
        ];

        $products = (new Product())->getProductList($input, $is_all);
        return ProductListResource::collection($products);
    }

    /**
     *product details for web
     */

    final public function productsdetails($id)
    {
        $products = Product::query()->with([
            'category:id,name',
            'sub_category:id,name',
            'child_sub_category:id,name',
            'brand:id,name',
            'country:id,name',
            'supplier:id,name,phone',
            'created_by:id,name',
            'updated_by:id,name',
            'primary_photo',
            'product_attributes',
            'product_attributes.attributes',
            'product_attributes.attribute_value',
            'product_specifications.specifications'
        ])->where('id', '=', $id)->first();
        return response()->json($products);
    }


    /**
     * @param StoreProductRequest $request
     * @return JsonResponse
     */
    public function store(StoreProductRequest $request)
    {
        try {
            DB::beginTransaction();
            $product = (new Product())->storeProduct($request->all(), auth()->id = 1);

            if ($request->has('attributes')) {
                (new ProductAttribute())->storeAttribute($request->input('attributes'), $product);
            }

            if ($request->has('specifications')) {
                (new ProductSpecification())->storeProductSpecification($request->input('specifications'), $product);
            }

            // Attach shops to the product
            $shopsData = array_combine(
                $request->input('shop_ids'),
                $request->input('shop_quantities')
            );

            foreach ($shopsData as $shopId => $quantity) {
                $product->shops()->attach($shopId, ['quantity' => $quantity['quantity']]);
            }

            DB::commit();
            return response()->json(['msg' => 'Product Saved Successfully', 'cls' => 'success', 'product_id' => $product->id]);
        } catch (\Throwable $e) {
            info("PRODUCT_SAVE_FAILED", ['data' => $request->all(), 'error' => $e->getMessage()]);
            DB::rollBack();
            return response()->json(['msg' => $e->getMessage(), 'cls' => 'warning']);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $productDetails = $product->load([
            'category:id,name',
            'photos:id,photo,product_id,is_primary',
            'sub_category:id,name',
            'child_sub_category:id,name',
            'brand:id,name',
            'country:id,name',
            'supplier:id,name,phone',
            'created_by:id,name',
            'updated_by:id,name',
            'primary_photo',
            'product_attributes',
            'product_attributes.attributes',
            'product_attributes.attribute_value',
        ]);

        return new ProductDetailsResource($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
{
    try {
        DB::beginTransaction();

        // Update the basic product details if they are present in the request
        $productData = $request->all();
        $product->update($productData);

        // Update attributes if provided
        if ($request->has('attributes')) {
            (new ProductAttribute())->updateAttribute($request->input('attributes'), $product);
        }

        // Update specifications if provided
        if ($request->has('specifications')) {
            (new ProductSpecification())->updateProductSpecification($request->input('specifications'), $product);
        }

        // Attach shops to the product if shop data is provided
        if ($request->has('shop_ids') && $request->has('shop_quantities')) {
            $shopsData = array_combine(
                $request->input('shop_ids'),
                $request->input('shop_quantities')
            );

            foreach ($shopsData as $shopId => $quantity) {
                $product->shops()->sync([$shopId => ['quantity' => $quantity['quantity']]]);
            }
        }

        DB::commit();
        return response()->json(['msg' => 'Product Updated Successfully', 'cls' => 'success', 'product_id' => $product->id]);
    } catch (\Throwable $e) {
        info("PRODUCT_UPDATE_FAILED", ['data' => $request->all(), 'error' => $e->getMessage()]);
        DB::rollBack();
        return response()->json(['msg' => $e->getMessage(), 'cls' => 'warning']);
    }
}



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            $product->delete();

            DB::commit();
            return response()->json(['msg' => 'Product Deleted Successfully', 'cls' => 'success']);
        } catch (\Throwable $e) {
            info("PRODUCT_DELETE_FAILED", ['product_id' => $product->id, 'error' => $e->getMessage()]);
            DB::rollBack();
            return response()->json(['msg' => $e->getMessage(), 'cls' => 'warning']);
        }
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function get_product_list_for_bar_code(Request $request)
    {
        $products = (new Product())->getProductForBarCode($request->all());

        // Eager load the product_attributes relationship with its related attributes and attribute values
        $products->load([
            'product_attributes.attributes',
            'product_attributes.attribute_value',
        ]);

        return ProductListForBarCodeResource::collection($products);
    }

    /**
     * @return JsonResponse
     */
    public function get_product_columns()
    {
        $columns = Schema::getColumnListing('products');
        $formated_columns = [];
        foreach ($columns as $column) {
            $formated_columns[] = ['id' => $column, 'name' => ucfirst(str_replace('_', ' ', $column))];
        }
        return response()->json($formated_columns);
    }

    /**
     * @return JsonResponse
     */
    final public function get_add_product_data(): JsonResponse
    {
        //        $categories, $brand, $countries, $suppliers, $attributes, $sub_categories, $child_sub_categories, $shop
        return response()->json([
            'categories' => (new Category())->getCategoryIdAndName(),
            'brands' => (new Brand())->getBrandIdAndName(),
            'countries' => (new Country())->getCountryIdAndName(),
            'providers' => (new Supplier())->getProviderIdAndName(),
            'attributes' => (new Attribute())->getAttributeIdAndName(),
            'sub_categories' => (new SubCategory())->getSubCategoryIdAndNameForProduct(),
            'child_sub_categories' => (new ChildSubCategory())->getChildSubCategoryIdAndNameForProduct(),
            'shops' => (new Shop())->getShopIdAndName()
        ]);
    }
    public function duplicate(Product $product)
    {
        $newProduct = $product->duplicateProduct($product->id);
        // print_r($product->id);
        // die();
        return response()->json(['msg' => 'Product Duplicated Successfully', 'cls' => 'success', 'product_id' => $newProduct->id]);
    }
}
