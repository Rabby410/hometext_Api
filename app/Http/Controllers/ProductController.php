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
use Facebook\Facebook;
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
            'per_page' => $request->input('per_page'), // You can adjust this key according to your request data
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
        ])->where('id', '=', $id)->first();
        return response()->json($products);
    }



    /**
     * @param StoreProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
//    public function store(StoreProductRequest $request)
//    {
//        try{
//            DB::beginTransaction();
//            $product = (new Product())->storeProduct($request->all(), auth()->id=1);
//            // $product =$product->storeProduct($product_data);
//            if($request->has('attributes')){
//                (new ProductAttribute())->storeAttribute ($request->input('attributes'), $product);
//            }
//            if($request->has('specifications')){
//                ( new ProductSpecification())-> storeProductSpecification ($request->input('specifications'), $product);
//            }
//
//            DB::commit();
//            return response()->json(['msg'=> 'Product Saved Successfully', 'cls'=>'success', 'product_id'=>$product->id]);
//
//        }catch(\Throwable $e){
//            info("PRODUCT_SAVE_FAILED", ['data'=>$request->all(), 'error'=>$e->getMessage()]);
//            DB::rollBack();
//            return response()->json(['msg'=> $e->getMessage(), 'cls'=>'warning']);
//        }
//    }
    public function store(StoreProductRequest $request)
    {
        try {
            DB::beginTransaction();

            // Create the product
            $product = (new Product())->storeProduct($request->all(), auth()->id());

            // Associate the product with shops and their quantities
            $shopsData = array_combine(
                $request->input('shop_ids'),
                $request->input('shop_quantities')
            );

            foreach ($shopsData as $shopId => $quantity) {
                $product->shops()->attach($shopId, ['quantity' => $quantity['quantity']]);
            }

            // Store attributes and specifications if needed

            DB::commit();

            return response()->json(['msg' => 'Product Saved Successfully', 'cls' => 'success', 'product_id' => $product->id]);
        } catch (\Throwable $e) {
            info("PRODUCT_SAVE_FAILED", ['data' => $request->all(), 'error' => $e->getMessage()]);
            DB::rollBack();
            return response()->json(['msg' => $e->getMessage(), 'cls' => 'warning']);
        }
    }


//    public function store(StoreProductRequest $request)
//    {
//        try {
//            DB::beginTransaction();
//            $product = (new Product())->storeProduct($request->all(), auth()->id = 1);
//
//            // Publish on Facebook page
//            $fb = new Facebook([
//                'app_id' => '2360056677506427',
//                'app_secret' => '3267ca22d08a1c3eae6afe543e799f83',
//                'default_graph_version' => 'v17.0',
//                // Add any additional configuration options here
//            ]);
//
//            // Get access token with publish_pages permission
//            $accessToken = 'EAAhidYPpnXsBAOmU99fn5UCx7ZCJmRsLlJkNZCzoyASMgsiUelB15TGDvJGZC1VLxLM5wEnAaMNZAxiUr6ULDA4EzeKl0gjGFd2MMKF4jb5NWGSVWjrFGlf0YnIwQZCfgvPZCWsmQUnSlsy1RDfmtPlINil41PaktPnOYxF6AMNzTXNM8VA37I7KXMBpWs8ldGil5v7ZA0wH7MIjfy5DA2J';
//
//            // Prepare the message and other parameters
//            $message = 'Check out our new product: ' . $product->name;
//            $link = 'https://bd.hometex.ltd/Shop/' . $product->id;
//            // Add other necessary parameters like image, description, etc.
//
//            // Make a POST request to publish on the page
//            $response = $fb->post('/hometexbangladesh.store/feed', [
//                'message' => $message,
//                'link' => $link,
//                // Add other parameters here
//            ], $accessToken);
//
//            // Get the published post ID
//            $graphNode = $response->getGraphNode();
//            $postID = $graphNode['id'];
//
//            if ($request->has('attributes')) {
//                (new ProductAttribute())->storeAttribute($request->input('attributes'), $product);
//            }
//            if ($request->has('specifications')) {
//                (new ProductSpecification())->storeProductSpecification($request->input('specifications'), $product);
//            }
//
//            DB::commit();
//            return response()->json(['msg' => 'Product Saved Successfully', 'cls' => 'success', 'product_id' => $product->id]);
//
//        } catch (\Throwable $e) {
//            info("PRODUCT_SAVE_FAILED", ['data' => $request->all(), 'error' => $e->getMessage()]);
//            DB::rollBack();
//            return response()->json(['msg' => $e->getMessage(), 'cls' => 'warning']);
//        }
//    }

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
    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            DB::beginTransaction();

            $product->update($request->except(['attributes', 'specifications']));

            // Update attributes if provided
            if ($request->has('attributes')) {
                $productAttribute = new ProductAttribute();
                $productAttribute->updateAttribute($request->input('attributes'), $product);
            }

            // Update specifications if provided
            if ($request->has('specifications')) {
                $productSpecification = new ProductSpecification();
                $productSpecification->updateProductSpecification($request->input('specifications'), $product);
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

    public function get_product_list_for_bar_code(Request $request)
    {
        $produrcts = (new Product())->getProductForBarCode($request->all());
        return ProductListForBarCodeResource::collection($produrcts);
    }

    public function get_product_columns()
    {
        $columns = Schema::getColumnListing('products');
        $formated_columns=[];
        foreach ($columns as $column){
            $formated_columns[] = ['id'=>$column, 'name'=>ucfirst(str_replace('_',' ', $column))];
        }
        return response()->json($formated_columns);
    }

    /**
     * @return JsonResponse
     */
    final public function get_add_product_data():JsonResponse
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
}
