<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Distric;
use App\Models\Province;
use App\Models\Subdistric;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    public function getAllProvinces()
    {
        $provinces = Province::all();
        // dd($provinces);

        // dd($this->success($provinces, "Get All Provinces Success"));
        return $this->success($provinces, "Get All Provinces Success");
    }

    public function getAllCities(int $prov_id)
    {
        // Find the province by its ID
        $province = Province::findOrFail($prov_id);

        // Get all cities associated with the province
        $cities = $province->cities;

        return $this->success($cities, 'Get All Cities');
    }

    public function getAllDistricts(int $city_id)
    {
        // Find the province by its ID
        $city = City::findOrFail($city_id);

        // Get all  associated with the city
        $districts = $city->districts;

        return $this->success($districts, 'Get All Districts');
    }
    public function getAllSubDistricts(int $dis_id)
    {
        // Find the province by its ID
        $districts = Distric::findOrFail($dis_id);

        // Get all  associated with the districts
        $subdistricts = $districts->subdistricts;

        return $this->success($subdistricts, 'Get All SubDistricts');
    }

    public function getAllDataLocation(Subdistric $subdistrict) {
        $district = $subdistrict->district;
        $city = $district->city;
        $province = $city->province;

        return $this->success($subdistrict);

    }
}
