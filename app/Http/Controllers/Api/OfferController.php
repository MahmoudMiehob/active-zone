<?php

namespace App\Http\Controllers\Api;

use App\Models\Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OfferController extends Controller
{
    use ApiResponseTrait;

    public function index(){
        $offers = Offer::all();

        if($offers){
            return $this->apiResponse($offers,'جميع العروض',200);
        }else{
            return $this->apiResponse(null,'غير موجود',201);
        }
    }
}
