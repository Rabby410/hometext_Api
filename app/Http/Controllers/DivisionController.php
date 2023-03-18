<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Http\Requests\StoreDivisionRequest;
use App\Http\Requests\UpdateDivisionRequest;
use Illuminate\Http\JsonResponse;

class DivisionController extends Controller
{
    /**
     * @return JsonResponse
     */
    final public function index():JsonResponse
    {
        $divisions =(new Division())->getDivisionList();
        return response()->json($divisions);
    }

}
