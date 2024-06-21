<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\QuestionAnswer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class QuestionAnswerController extends Controller
{
    use ApiResponseTrait ;

    public function index(){

        $questionAnswer = QuestionAnswer::all();
        if($questionAnswer){
            return $this->apiResponse($questionAnswer,'get all questions and answer',200);
        }else{
            return $this->apiResponse(null,'عذرا حدث خطأ يرجى اعادة المحاولة',201);
        }
    }

}
