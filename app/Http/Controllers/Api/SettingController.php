<?php

namespace App\Http\Controllers\Api;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    use ApiResponseTrait ;

    public function index(){
        $settings = Setting::all();
        if($settings){
            return $this->apiResponse($settings,'جميع الاعدادات',200);
        }else{
            return $this->apiResponse(null,'غير موجود',201);
        }
    }


}
