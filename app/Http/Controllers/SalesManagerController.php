<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Support\Str;
use App\Models\SalesManager;
use App\Http\Requests\StoreSalesManagerRequest;
use App\Http\Requests\UpdateSalesManagerRequest;
use App\Manager\ImageUploadManager;
use Illuminate\Support\Facades\DB;
use Throwable;

class SalesManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSalesManagerRequest $request)
    {
        $sales_manager = (new SalesManager())->prepareData($request->all(), auth());
        $address = (new Address())->prepareData($request->all());
        if($request->has('photo')){
            $name=Str::slug($sales_manager['name'].now().'-photo');
            $sales_manager['photo'] =
            ImageUploadManager::processImageUpload(
                $request->input('photo'),
                $name,
                SalesManager::PHOTO_UPLOAD_PATH,
                SalesManager::PHOTO_WIDTH,
                SalesManager::PHOTO_HEIGHT,
                SalesManager::THUMB_PHOTO_UPLOAD_PATH,
                SalesManager::PHOTO_THUMB_WIDTH,
                SalesManager::PHOTO_THUMB_HEIGHT,

            );
        }
        if($request->has('nid_photo')){
            $name=Str::slug($sales_manager['name'].now().'-nid');
            $sales_manager['nid_photo'] =
            ImageUploadManager::processImageUpload(
                $request->input('nid_photo'),
                $name,
                SalesManager::PHOTO_UPLOAD_PATH,
                SalesManager::PHOTO_WIDTH,
                SalesManager::PHOTO_HEIGHT,
                SalesManager::THUMB_PHOTO_UPLOAD_PATH,
                SalesManager::PHOTO_THUMB_WIDTH,
                SalesManager::PHOTO_THUMB_HEIGHT,

            );
        }
        try{
            DB::beginTransaction();
            $sales_manager = SalesManager::create($sales_manager);
            $sales_manager->address()->create($address);
            DB::commit();
            return response()->json(['msg'=>'SalesManager Added Successfully', 'cls' => 'success']);
        }catch(Throwable $e){
            if(isset($sales_manager['photo'])){
                ImageUploadManager::deletePhoto(SalesManager::PHOTO_UPLOAD_PATH, $sales_manager['photo'] );
                ImageUploadManager::deletePhoto(SalesManager::THUMB_PHOTO_UPLOAD_PATH, $sales_manager['photo'] );
            }
            if(isset($sales_manager['nid_photo'])){
                ImageUploadManager::deletePhoto(SalesManager::PHOTO_UPLOAD_PATH, $sales_manager['nid_photo'] );
                ImageUploadManager::deletePhoto(SalesManager::THUMB_PHOTO_UPLOAD_PATH, $sales_manager['nid_photo'] );
            }
            info('SALES_MANAGER_STORE_FAILED', ['SalesManager' => $sales_manager, 'address'=> $address, $e]);
            DB::rollBack();
            return response()->json(['msg'=>'Have Validation Error', 'cls' => 'warning', 'flag' =>'true' ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesManager $salesManager)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalesManager $salesManager)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSalesManagerRequest $request, SalesManager $salesManager)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesManager $salesManager)
    {
        //
    }
}
