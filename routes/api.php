<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttributeValueController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPhotoController;
use App\Http\Controllers\SalesManagerController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SubCategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//

// Route::get('test', [scriptManager::class, 'getCountry']);
Route::post('login', [AuthController::class, 'login']);
Route::get('divisions', [DivisionController::class, 'index']);
Route::get('district/{division_id}', [DistrictController::class, 'index']);
Route::get('area/{district_id}', [AreaController::class, 'index']);


Route::group(['middleware' => 'auth:sanctum', 'auth:users'], static function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('get-attribute-list', [AttributeController::class, 'get_attribute_list']);
Route::get('get-supplier-list', [SupplierController::class, 'get_provider_list']);
Route::get('get-country-list', [CountryController::class, 'get_country_list']);
Route::get('get-brand-list', [BrandController::class, 'get_brand_list']);
Route::get('get-category-list', [CategoryController::class, 'get_category_list']);
Route::get('get-shop-list', [ShopController::class, 'get_shop_list']);
Route::get('get-sub-category-list/{category_id}', [SubCategoryController::class, 'get_sub_category_list']);
Route::post('product-photo-upload/{id}', [ProductPhotoController::class, 'store']);
Route::apiResource('category', CategoryController::class);
Route::apiResource('sub-category', SubCategoryController::class);
Route::apiResource('brand', BrandController::class);
Route::apiResource('supplier', SupplierController::class);
Route::apiResource('attribute', AttributeController::class);
Route::apiResource('attribute-value', AttributeValueController::class);
Route::apiResource('product', ProductController::class);
Route::apiResource('photo', ProductPhotoController::class);
Route::apiResource('shop', ShopController::class);
});


Route::group(['middleware' => ['auth:sanctum', 'auth:sales_manager']], function(){
    Route::apiResource('sales-manager', SalesManagerController::class);

});
