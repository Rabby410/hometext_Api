<?php

namespace App\Http\Controllers;

use App\Http\Resources\PriceFormulaResourceList;
use App\Models\PriceFormula;
use App\Http\Requests\StorePriceFormulaRequest;
use App\Http\Requests\UpdatePriceFormulaRequest;

class PriceFormulaController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $priceFormulas = (new PriceFormula())->getAllPriceFormulas();
        return PriceFormulaResourceList::collection($priceFormulas);
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
    public function store(StorePriceFormulaRequest $request)
    {
        $priceFormulaData = $request->all();
        (new PriceFormula())->storePriceFormula($priceFormulaData);

        return response()->json(['msg' => 'Price Formula Created Successfully', 'cls' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    public function show(PriceFormula $priceFormula)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PriceFormula $priceFormula)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePriceFormulaRequest $request, PriceFormula $priceFormula)
    {
        $priceFormulaData = $request->all();
        $priceFormula->update($priceFormulaData);

        return response()->json(['msg' => 'Price Formula Updated Successfully', 'cls' => 'success']);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PriceFormula $priceFormula)
    {
        $priceFormula->delete();
        return response()->json(['msg'=>'Price Formula Deleted Successfully', 'cls' => 'warning']);
    }
}
