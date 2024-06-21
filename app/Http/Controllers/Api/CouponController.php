<?php

namespace App\Http\Controllers\Api;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CouponController extends Controller
{
    use ApiResponseTrait ;


    public function index(){
        $coupon = Coupon::whereDate('start_at', '<=', date('Y-m-d'))
        ->whereDate('end_at', '>=', date('Y-m-d'))
        ->get();

        if($coupon){
            return $this->apiResponse($coupon,'جميع الكوبونات',200);
        }else{
            return $this->apiResponse(null,'غير موجود',201);
        }
    }

    public function endcoupon(){
        $coupon = Coupon::whereDate('end_at', '<=', date('Y-m-d'))->get();;

        if($coupon){
            return $this->apiResponse($coupon,' الكوبونات المنتهية',200);
        }else{
            return $this->apiResponse(null,'غير موجود',201);
        }
    }


    public function checkcoupon($coupon){
        $coupon = Coupon::whereRaw("BINARY coupon = '$coupon'")->get();

        if ($coupon->isNotEmpty()) {
            return $this->apiResponse($coupon, 'الكوبون موجود', 200);
        } else {
            return $this->apiResponse(null, 'غير موجود', 201);
        }
    }
}
