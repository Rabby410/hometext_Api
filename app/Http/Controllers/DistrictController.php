<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreDistrictRequest;
use App\Http\Requests\UpdateDistrictRequest;

class DistrictController extends Controller
{
     /**
     * @param int $division_id
     * @return JsonResponse
     */
    final public function index(int $division_id):JsonResponse
    {
        $districts = (new District())->getDistrictByDivisionId($division_id);
        return response()->json($districts);
    }


}
