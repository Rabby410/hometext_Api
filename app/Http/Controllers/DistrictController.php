<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreDistrictRequest;
use App\Http\Requests\UpdateDistrictRequest;

class DistrictController extends Controller
{
     /**
     * @param int $id
     * @return JsonResponse
     */
    final public function index(int $id):JsonResponse
    {
        $districts = (new District())->getDistrictByDivisionId($id);
        return response()->json($districts);
    }


}
