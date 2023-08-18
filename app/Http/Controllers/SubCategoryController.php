<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use App\Http\Requests\StoreSubCategoryRequest;
use App\Http\Requests\UpdateSubCategoryRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Manager\ImageUploadManager;
use App\Http\Resources\SubCategoryEditResource;
use App\Http\Resources\SubCategoryListResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *@return AnonymousResourceCollection
     */
    public function index(Request $request):AnonymousResourceCollection
    {
        $categories = (new SubCategory())->getAllSubCategories($request->all());
        return SubCategoryListResource::collection($categories);
    }

    /**
     * @param StoreSubCategoryRequest $request
     * @return JsonResponse
     */
    final public function store(StoreSubCategoryRequest $request):JsonResponse
    {
        $sub_category = $request->except('photo');
        $sub_category['slug'] = Str::slug($request->input('slug'));
//        $sub_category['user_id'] = auth()->id;
        if($request->has('photo')){
            $sub_category['photo'] = $this->processImageUpload($request->input('photo'), $sub_category['slug']);
        }
        (new SubCategory())->storeSubCategory($sub_category);
        return response()->json(['msg'=>'Sub Category Created Successfully', 'cls' => 'success']);
    }

    /**
     * @param Category $category
     * @return SubCategoryEditResource
     */
    final public function show(SubCategory $subCategory):SubCategoryEditResource
    {
        return new SubCategoryEditResource($subCategory);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubCategory $subCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *@param UpdateSubCategoryRequest $request
     *@param SubCategory $subCategory
     *@return JsonResponse
     */
    final public function update(UpdateSubCategoryRequest $request, SubCategory $subCategory):JsonResponse
    {
        $sub_category_data = $request->except('photo');
        $sub_category_data['slug'] = Str::slug($request->input('slug'));
        if($request->has('photo')){
            $sub_category_data['photo']  = $this->processImageUpload($request->input('photo'),  $sub_category_data['slug'], $subCategory->photo);
        }
        $subCategory->update($sub_category_data);
        return response()->json(['msg'=>'SUb Category Updated Successfully', 'cls' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     * @return Category $category
     * @return JsonResponse
     */
    public function destroy(SubCategory $subCategory):JsonResponse
    {
        if(!empty($subCategory->photo)){
            ImageUploadManager::deletePhoto(SubCategory::IMAGE_UPLOAD_PATH, $subCategory->photo);
            ImageUploadManager::deletePhoto(SubCategory::THUMB_IMAGE_UPLOAD_PATH, $subCategory->photo);
        }
        $subCategory->delete();
        return response()->json(['msg'=>'Sub Category Deleted Successfully', 'cls' => 'warning']);
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
            $path = SubCategory::IMAGE_UPLOAD_PATH;
            $path_thumb = SubCategory::THUMB_IMAGE_UPLOAD_PATH;

            if(!empty($existing_photo)){
                ImageUploadManager::deletePhoto(SubCategory::IMAGE_UPLOAD_PATH, $existing_photo);
                ImageUploadManager::deletePhoto(SubCategory::THUMB_IMAGE_UPLOAD_PATH, $existing_photo);
            }

            $photo_name= ImageUploadManager::uploadImage($name, $width, $height, $path, $file);
            ImageUploadManager::uploadImage($name, $width_thumb, $height_thumb, $path_thumb, $file);
            return $photo_name;
        }
    /**
     * @return JsonResponse
     */
    final public function get_sub_category_list_fc():JsonResponse
    {
        $subCategories = (new SubCategory())->getSubCategoryIdAndNameFC();
        return response()->json($subCategories);
    }

        /**
         * @param int $category_id
         * @return JsonResponse
         */
        final public function get_sub_category_list(int $category_id):JsonResponse
        {
            $subCategories = (new SubCategory())->getSubCategoryIdAndName($category_id);
            return response()->json($subCategories);
        }
}
