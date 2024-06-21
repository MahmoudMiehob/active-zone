<?php

namespace App\Http\Controllers\Api;



use App\Models\Rating;
use App\Models\Subsurvice;
use App\Models\Minisurvice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubsurviceController extends Controller
{

    use ApiResponseTrait ;
    use isFavoriteTrait ;


    public function index(){
        $subsurvices = Subsurvice::with('survice')->get();
        if($subsurvices){
            return $this->apiResponse($subsurvices,'جميع الخدمات subsurvice',200);
        }else{
            return $this->apiResponse(null,'غير موجود',404);
        }
    }


    public function show($id){
        $subsurvice = Subsurvice::with('survice')->find($id);
        if($subsurvice){
            $subsurvice->get();
            return $this->apiResponse($subsurvice,'تم جلب الطلب بنجاح',200);
        }else{
            return $this->apiResponse(null,'حدثت مشكلة يرجى المحاولة لاحقا',400);
        }
    }


    public function getAllminisurvices($id){
        $subsurvice = Subsurvice::find($id);
        if($subsurvice){
            $minisurvices = Minisurvice::where('subsurvice_id',$id)->get();
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
