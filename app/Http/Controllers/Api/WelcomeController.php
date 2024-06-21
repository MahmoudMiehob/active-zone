<?php


namespace App\Http\Controllers\Api;

use App\Models\Welcome;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WelcomeController extends Controller
{
    use ApiResponseTrait ;

    public function index(){
        $welcomes = Welcome::all();
        if($welcomes){
            return $this->apiResponse($welcomes,'جميع صفحات الترحيب',200);
        }else{
            return $this->apiResponse(null,'غير موجود',201);
        }
    }


    public function show($id){
        $welcome = Welcome::find($id);
        if($welcome){
            $welcome->get();
            return $this->apiResponse($welcome,'تم جلب الطلب بنجاح',200);
        }else{
            return $this->apiResponse(null,'حدثت مشكلة يرجى المحاولة لاحقا',400);
        }
    }



}
