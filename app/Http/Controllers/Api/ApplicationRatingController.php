<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ApplicationRating;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ApplicationRatingController extends Controller
{
    use ApiResponseTrait ;


    public function userapplicationrating($id){
        $applicationrating = ApplicationRating::where('user_id','=',$id)->with(['user'])->first();
        if ($applicationrating) {
            return $this->apiResponse($applicationrating, ' التقيمات لهذا المستخدم', 200);
        } else {
            return $this->apiResponse(null, 'غير موجود', 201);
        }
    }


    public function store(Request $request){
        $validate = Validator::make($request->all(),[
            'comment'         => 'string',
            'rating'          => 'integer|between:1,5',
            'user_id'         => 'required|string|unique:application_ratings',
        ]);

        if ($validate->fails()){
            return $this->apiResponse(null,$validate->errors()->first(),500);
        }

        $attributes = [
            'user_id' => $request->user_id,
        ];

        $values = [
            'comment' => $request->comment,
            'rating' => $request->rating,
        ];

        $applicationrating = ApplicationRating::updateOrCreate($attributes, $values);

        if($applicationrating){
            return $this->apiResponse($applicationrating,'تم اضافة التقييم بنجاح',200);
        }else{
            return $this->apiResponse(null,'عذرا لم يتم اضافة التقييم يرجى اعادة المحاولة',201);
        }
    }


}
