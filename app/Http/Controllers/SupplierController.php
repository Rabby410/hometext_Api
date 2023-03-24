<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Supplier;
use Illuminate\Support\Str;
use App\Manager\ImageUploadManager;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Http\Resources\SupplierEditResource;
use App\Http\Resources\SupplierListResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Throwable;

class SupplierController extends Controller
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    final public function index(Request $request):AnonymousResourceCollection
    {
        $suppliers =(new Supplier())->getSupplierList($request->all());
        return SupplierListResource::collection($suppliers);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        $supplier = (new Supplier())->prepareData($request->all(), auth());
        $address = (new Address())->prepareData($request->all());
        if($request->has('logo')){
            $name=Str::slug($supplier['name'].now());
            $supplier['logo'] =
            ImageUploadManager::processImageUpload(
                $request->input('logo'),
                $name,
                Supplier::IMAGE_UPLOAD_PATH,
                Supplier::LOGO_WIDTH,
                Supplier::LOGO_HEIGHT,
                Supplier::THUMB_IMAGE_UPLOAD_PATH,
                Supplier::LOGO_THUMB_WIDTH,
                Supplier::LOGO_THUMB_HEIGHT,

            );
        }
        try{
            DB::beginTransaction();
            $supplier = Supplier::create($supplier);
            $supplier->address()->create($address);
            DB::commit();
            return response()->json(['msg'=>'Supplier Added Successfully', 'cls' => 'success']);
        }catch(Throwable $e){
            if(isset($supplier['logo'])){
                ImageUploadManager::deletePhoto(Supplier::IMAGE_UPLOAD_PATH, $supplier['logo'] );
                ImageUploadManager::deletePhoto(Supplier::THUMB_IMAGE_UPLOAD_PATH, $supplier['logo'] );
            }
            info('SUPPLIER_STORE_FAIL', ['supplier' => $supplier, 'address'=> $address, $e]);
            DB::rollBack();
            return response()->json(['msg'=>'Have Validation Error', 'cls' => 'warning', 'flag' =>'true' ]);
        }
    }

    /**
     * @param Supplier $supplier
     * @return SupplierEditResource
     */
    final public function show(Supplier $supplier):SupplierEditResource
    {
        $supplier->load('address');
        return new SupplierEditResource($supplier);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $supplier_data = (new Supplier())->prepareData($request->all(), auth());
        $address_data = (new Address())->prepareData($request->all());
        if($request->has('logo')){
            $name=Str::slug($supplier_data['name'].now());
            $supplier_data['logo'] =
            ImageUploadManager::processImageUpload(
                $request->input('logo'),
                $name,
                Supplier::IMAGE_UPLOAD_PATH,
                Supplier::LOGO_WIDTH,
                Supplier::LOGO_HEIGHT,
                Supplier::THUMB_IMAGE_UPLOAD_PATH,
                Supplier::LOGO_THUMB_WIDTH,
                Supplier::LOGO_THUMB_HEIGHT,
                $supplier->logo

            );
        }
        try{
            DB::beginTransaction();
            $supplier_data = $supplier->update($supplier_data);
            $supplier->address()->update($address_data);
            DB::commit();
            return response()->json(['msg'=>'Supplier Updated Successfully', 'cls' => 'success']);
        }catch(Throwable $e){
            info('SUPPLIER_STORE_FAIL', ['supplier' => $supplier_data, 'address'=> $address_data, $e]);
            DB::rollBack();
            return response()->json(['msg'=>'Have Validation Error', 'cls' => 'warning', 'flag' =>'true' ]);
        }
    }

    /**
     * @param Supplier $supplier
     * @return JsonResponse
     */
    public function destroy(Supplier $supplier):JsonResponse
    {
        if(!empty($supplier->logo)){
            ImageUploadManager::deletePhoto(Supplier::IMAGE_UPLOAD_PATH, $supplier['logo'] );
            ImageUploadManager::deletePhoto(Supplier::THUMB_IMAGE_UPLOAD_PATH, $supplier['logo'] );
        }
        (new Address())->deleteAddressBySupplierId($supplier);
        $supplier->delete();
        return response()->json(['msg'=>'Supplier deleted Successfully', 'cls' => 'warning']);
    }
}
