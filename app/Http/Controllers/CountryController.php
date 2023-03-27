<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
     /**
     * @return JsonResponse
     */
    final public function get_country_list():JsonResponse
        {
            $countries = (new Country())->getCountryIdAndName();
            return response()->json($countries);
        }
}
