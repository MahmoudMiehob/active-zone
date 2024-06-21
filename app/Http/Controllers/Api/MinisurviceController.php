<?php

namespace App\Http\Controllers\Api;

use App\Models\Minisurvice;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\Controller;

class MinisurviceController extends Controller
{

    use ApiResponseTrait ;
    use isFavoriteTrait ;

    public function index(){
        $minisurvices = Minisurvice::all();
        if($minisurvices){
            $minisurvices->transform(function ($minisurvice) {
                $minisurvice->isFavorite = auth()->check() ? $this->isFavorite(auth()->user()->id, $minisurvice->id) : false;
                return $minisurvice;
            });
            return $this->apiResponse($minisurvices,['جميع خدمات المزودين'],200);
        }else{
            return $this->apiResponse(null,'غير موجود',201);
        }
    }

    public function show($id){
        $minisurvices = Minisurvice::with('region','country','survice','subsurvice')->find($id);
        if($minisurvices){
            $minisurvices->get();
            $minisurvices->isFavorite = auth()->check() ? $this->isFavorite(auth()->user()->id, $minisurvices->id) : false;
            return $this->apiResponse($minisurvices,'تم جلب الطلب بنجاح',200);
        }else{
            return $this->apiResponse(null,'حدثت مشكلة يرجى المحاولة لاحقا',201);
        }
    }

    public function popularminisurvice(){
        $minisurvices = Minisurvice::with('region','country','survice','subsurvice')->orderBy('rating', 'DESC')->limit(8)->get();
        if($minisurvices){
            $minisurvices->transform(function ($minisurvice) {
                $minisurvice->isFavorite = auth()->check() ? $this->isFavorite(auth()->user()->id, $minisurvice->id) : false;
                return $minisurvice;
            });
            return $this->apiResponse($minisurvices,' الخدمات الاكثر طلبا',200);
        }else{
            return $this->apiResponse(null,'غير موجود',201);
        }
    }
}
