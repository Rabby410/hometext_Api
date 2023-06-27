<?php

namespace App\Http\Controllers;

use App\Http\Resources\BrandListResource;
use App\Http\Resources\PriceFormulaVariablesEditResource;
use App\Http\Resources\PriceFormulaVariablesResourceList;
use App\Models\PriceFormulaVariables;
use App\Http\Requests\StorePriceFormulaVariablesRequest;
use App\Http\Requests\UpdatePriceFormulaVariablesRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PriceFormulaVariablesController extends Controller
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request):AnonymousResourceCollection
    {
        $priceFormulaVariables = ( new PriceFormulaVariables())->getAllPriceFormulaVariables($request->all());
        return PriceFormulaVariablesResourceList::collection($priceFormulaVariables);
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
    public function store(StorePriceFormulaVariablesRequest $request)
    {
        $priceFormulaVariables = $request->all();
        (new PriceFormulaVariables())->storePriceFormulaVariables($priceFormulaVariables);
        return response()->json(['msg' => 'Price Formula Variables Created Successfully', 'cls' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    public function show(PriceFormulaVariables $priceFormulaVariables)
    {
        return new PriceFormulaVariablesEditResource($priceFormulaVariables);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PriceFormulaVariables $priceFormulaVariables)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePriceFormulaVariablesRequest $request, PriceFormulaVariables $priceFormulaVariables)
    {
        $priceFormulaVariablesData = $request->all();
        $priceFormulaVariables->update($priceFormulaVariablesData);

        return response()->json(['msg' => 'Price Formula Variables Updated Successfully', 'cls' => 'success']);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PriceFormulaVariables $priceFormulaVariables)
    {
        $priceFormulaVariables->delete();
        return response()->json(['msg'=>'Price Formula Variable Deleted Successfully', 'cls' => 'warning']);
    }
}
