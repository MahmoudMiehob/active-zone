<?php

namespace App\Http\Controllers\Api;

use App\Models\Region;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegionController extends Controller
{

    use ApiResponseTrait ;
    public function index(){
        $regions = Region::all();
        if($regions){
            return $this->apiResponse($regions,'جميع المناطق',200);
        }else{
            return $this->apiResponse(null,'غير موجود',201);
        }
    }
}
