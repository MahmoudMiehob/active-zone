<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Rating;
use App\Models\Survice;
use App\Models\Subsurvice;
use App\Models\Minisurvice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SurviceController extends Controller
{

    use ApiResponseTrait ;
    use isFavoriteTrait ;
    public function index(){
        $survices = Survice::all();
        if($survices){
            return $this->apiResponse($survices,'جميع الخدمات',200);
        }else{
            return $this->apiResponse(null,'غير موجود',404);
        }
    }


    public function show($id){
        $survice = Survice::find($id);
        if($survice){
            $survice->get();
            return $this->apiResponse($survice,'تم جلب الطلب بنجاح',200);
        }else{
            return $this->apiResponse(null,'حدثت مشكلة يرجى المحاولة لاحقا',400);
        }
    }


    public function getAllsubsurvices($id){
        $survice = Survice::find($id);
        if($survice){
            $subsurvices = Subsurvice::where('survice_id',$id)->get();
            if($subsurvices){
                return $this->apiResponse($subsurvices,'جميع الخدمات للخدمة المحددة',200);
            }else{
                return $this->apiResponse(null,'عذرا لم يتم العثور على خدمات  بهذه  الخدمة',400);
            }
        }else{
            return $this->apiResponse(null,'عذرا لم يتم العثور على الخدمة المصغرة',400);
        }
    }


    public function getAllminisurvices($id){
        $survice = Survice::find($id);
        if($survice){
            $minisurvices = Minisurvice::where('survice_id',$id)->get();

            if($minisurvices){
                $minisurvices->transform(function ($minisurvice) use ($id) {
                    $ratingnumber = Rating::where('minisurvice_id','=',$minisurvice->id)->count();
                    $minisurvice->ratingnumber = $ratingnumber;
                    $minisurvice->isFavorite = auth()->check() ? $this->isFavorite(auth()->user()->id, $minisurvice->id) : false;
                    return $minisurvice;
                });
                return $this->apiResponse($minisurvices,'جميع الخدمات المصغرة للخدمة المحددة',200);
            }else{
                return $this->apiResponse(null,'عذرا لم يتم العثور على خدمات مصغرة  بهذه  الخدمة',201);
            }
        }else{
            return $this->apiResponse(null,'عذرا لم يتم العثور على الخدمة المصغرة',201);
        }
    }
}
