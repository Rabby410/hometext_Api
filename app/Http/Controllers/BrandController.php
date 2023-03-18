<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Manager\ImageUploadManager;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\BrandEditResource;
use App\Http\Resources\BrandListResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BrandController extends Controller
{
     /**
     * Display a listing of the resource.
     *@return AnonymousResourceCollection
     */
    public function index(Request $request):AnonymousResourceCollection
    {
       $brands = (new Brand())->getAllBrands($request->all());
       return BrandListResource::collection($brands);
    }



    /**
     * @param StoreBrandRequest $request
     * @return JsonResponse
     */
    final public function store(StoreBrandRequest $request):JsonResponse
    {
        $brand = $request->except('logo');
        $brand['slug'] = Str::slug($request->input('slug'));
        $brand['user_id'] = auth()->id();
        if($request->has('logo')){
            $brand['logo'] = $this->processImageUpload($request->input('logo'), $brand['slug']);
        }
        (new Brand())->storeBrand($brand);
        return response()->json(['msg'=>'Brand Created Successfully', 'cls' => 'success']);
    }

    /**
     * @param Brand $brand
     * @return SubCategoryEditResource
     */
    final public function show(Brand $brand):BrandEditResource
    {
        return new BrandEditResource($brand);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $brand_data = $request->except('logo');
        $brand_data['slug'] = Str::slug($request->input('slug'));
        if($request->has('logo')){
            $brand_data['logo']  = $this->processImageUpload($request->input('logo'),  $brand_data['slug'], $brand->logo);
        }
        $brand->update($brand_data);
        return response()->json(['msg'=>'Brand Updated Successfully', 'cls' => 'success']);
    }

   /**
     * Remove the specified resource from storage.
     * @return Brand $brand
     * @return JsonResponse
     */
    final public function destroy(Brand $brand):JsonResponse
    {
        if(!empty($brand->logo)){
            ImageUploadManager::deletePhoto(Brand::IMAGE_UPLOAD_PATH, $brand->logo);
            ImageUploadManager::deletePhoto(Brand::THUMB_IMAGE_UPLOAD_PATH, $brand->logo);
        }
        $brand->delete();
        return response()->json(['msg'=>'Brand Deleted Successfully', 'cls' => 'warning']);
    }

    /**
     * @param string $file
     * @param string $name
     * @param string|null $existing_photo
     * @return string
     */
    private function processImageUpload(string $file, string $name, string|null $existing_photo = null):string
    {
            $width = 800;
            $height = 800;
            $width_thumb = 150;
            $height_thumb = 150;
            $path = Brand::IMAGE_UPLOAD_PATH;
            $path_thumb = Brand::THUMB_IMAGE_UPLOAD_PATH;

            if(!empty($existing_photo)){
                ImageUploadManager::deletePhoto(Brand::IMAGE_UPLOAD_PATH, $existing_photo);
                ImageUploadManager::deletePhoto(Brand::THUMB_IMAGE_UPLOAD_PATH, $existing_photo);
            }

            $photo_name= ImageUploadManager::uploadImage($name, $width, $height, $path, $file);
            ImageUploadManager::uploadImage($name, $width_thumb, $height_thumb, $path_thumb, $file);
            return $photo_name;
        }
}
