<?php

namespace App\Manager;

use App\Models\Area;
use App\Models\District;
use App\Models\Division;
use Illuminate\Support\Facades\Http;

class scriptManager
{
    public function getLocationData()
    {
        $url = 'https://member.daraz.com.bd/locationtree/api/getSubAddressList?countryCode=BD&page=addressEdit';

        $response = Http::get($url);
        $divisions = json_decode($response->body(), true);
        foreach ($divisions['module'] as $key => $division) {
            if ($key == 1) {

                $division_data['name'] = $division['name'];
                $division_data['original_id'] = $division['id'];

                $created_div =  Division::create($division_data);

                $dist_url = 'https://member.daraz.com.bd/locationtree/api/getSubAddressList?countryCode=BD&addressId=' . $division['id'] . '&page=addressEdit';

                $dist_response = Http::get($dist_url);
                $districts = json_decode($dist_response->body(), true);

                foreach ($districts['module'] as $district) {
                    $district_data['name'] = $district['name'];
                    $district_data['division_id'] = $created_div->id;
                    $district_data['original_id'] = $district['id'];

                    $created_dist = District::create($district_data);

                    $area_url = 'https://member.daraz.com.bd/locationtree/api/getSubAddressList?countryCode=BD&addressId=' . $district['id'] . '&page=addressEdit';

                    $area_response = Http::get($area_url);
                    $areas = json_decode($area_response->body(), true);

                    foreach ($areas['module'] as $area) {
                        $area_data['name'] = $area['name'];
                        $area_data['district_id'] = $created_dist->id;
                        $area_data['original_id'] = $area['id'];

                        Area::create($area_data);
                    }
                }
            }
        }
        echo'success 1';
    }
}
