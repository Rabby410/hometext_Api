<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Supplier;
use Illuminate\Support\Str;
use App\Manager\ImageUploadManager;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        $supplier = (new Supplier())->prepareData($request->all(), auth());
        $address = (new Address())->prepareData($request->all());
        if($request->has('logo')){
            $name=Str::slug($supplier['name']);
            $supplier['logo']=
            ImageUploadManager::processImageUpload(
                $request->has('logo'),
                $name,
                Supplier::IMAGE_UPLOAD_PATH,
                Supplier::LOGO_WIDTH,
                Supplier::LOGO_HEIGHT,
                Supplier::THUMB_IMAGE_UPLOAD_PATH,
                Supplier::LOGO_THUMB_WIDTH,
                Supplier::LOGO_THUMB_HEIGHT,

            );
        }
        $supplier = Supplier::create($supplier);
        $supplier->address()->create($address);
        return response()->json(['msg'=>'Category Added Successfully', 'cls' => 'success']);
        // return $request->all();
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        //
    }
}
