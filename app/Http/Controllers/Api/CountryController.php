<?php

namespace App\Http\Controllers\Api;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CountryController extends Controller
{

    use ApiResponseTrait;

    public function index(){
        $Getcountrylist = Country::all();

        if ($Getcountrylist) {
            return $this->apiResponse($Getcountrylist, 'جميع الدول', 200);
        } else {
            return $this->apiResponse(null, 'لا يوجد دول', 201);
        }
    }
}
