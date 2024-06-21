<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Point;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PointController extends Controller
{
    use ApiResponseTrait;

    public function points($userid){
        $points = User::where('id',$userid)->first()->points ;

        $pointdetails = Point::where('user_id','=',$userid)->with('minisurvice')->get();

        $response = [
            'points' => $points,
            'pointsdetails' => $pointdetails,
        ];

        if($points){
            return $this->apiResponse($response,'جميع النقاط لهذا المستخدم',200);
        }else if($points == null || $points == 0){
            return $this->apiResponse(0,'جميع النقاط لهذا المستخدم',200);
        }
        else{
            return $this->apiResponse(null,'غير موجود',201);
        }
    }
}
