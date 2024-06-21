<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Country;
use App\Models\Minisurvice;
use Illuminate\Http\Request;
use App\Models\SearchArchive;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class Search extends Controller
{
    use ApiResponseTrait;
    use isFavoriteTrait ;

    public function searchminisurvice($search)
    {
        $result = Minisurvice::where(function ($query) use ($search) {
            $query->where('name_ar', 'like', '%' . $search . '%')
                ->orWhere('name_en', 'like', '%' . $search . '%');
        })
        ->orderBy('rating', 'DESC')
        ->get();

        $result->transform(function ($minisurvice) {
            $minisurvice->isFavorite = auth()->check() ? $this->isFavorite(auth()->user()->id, $minisurvice->id) : false;
            return $minisurvice;
        });

        if ($result) {
            return $this->apiResponse($result, ['نتائج البحث'], 200);
        } else {
            return $this->apiResponse(null, 'غير موجود', 201);
        }
    }


    public function specialsearch(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'survice' => 'integer',
            'country' => 'integer',
            'date' => 'date',
            'price_start' => 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'price_end' => 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'rating' => 'integer',
        ]);

        if ($validate->fails()) {
            return $this->apiResponse(null, $validate->errors()->first(), 500);
        }

        // Assuming you want to allow searching by multiple services
        $query = Minisurvice::query();

        $query->when($request->has('survice'), function ($query) use ($request) {
            return $query->where('survice_id', '=', $request->survice);
        });

        $query->when($request->has('country'), function ($query) use ($request) {
            return $query->where('country_id', 'like', '%' . $request->country . '%');
        });

        $query->when($request->has('date'), function ($query) use ($request) {
            return $query->where('start_at', '=', $request->date);
        });

        $query->when($request->has('price_start') && $request->has('price_end'), function ($query) use ($request) {
            return $query->whereBetween('adult_price', [$request->price_start, $request->price_end]);
        });

        $query->when($request->has('rating'), function ($query) use ($request) {
            return $query->where('rating', '=', $request->rating);
        });



        $results = $query->orderBy('rating', 'DESC')->with('survice','subsurvice')->get();
        $results->map(function ($result) {
            $result->isFavorite = auth()->check() ? $this->isFavorite(auth()->user()->id, $result->id) : false;
            return $result;
        });


            $countryName = Country::where('id','=',$request->country)->first()->name;

            if(isset($request->user_id) && $request->user_id !=null){
                $searcharchive = SearchArchive::create([
                    'survice' => $request->survice,
                    'country' => $countryName,
                    'start_at' => $request->date,
                    'price_start' => $request->price_start,
                    'price_end' => $request->price_end,
                    'rating' => $request->rating,
                    'user_id' => $request->user_id,
                ]);
        }

        if ($results) {
            return $this->apiResponse($results,'نتائج البحث', 200);
        } else {
            return $this->apiResponse(null, 'غير موجود', 201);
        }
    }




    public function searcharchive($id){
        $user = User::find($id);
        if($user){
            $searcharchive = SearchArchive::where('user_id','=',$id)->get();
            if($searcharchive){
                return $this->apiResponse($searcharchive,'تم جلب الطلب بنجاح',200);
            }else{
                return $this->apiResponse(null,'لا يوجد بحث سابق',201);
            }
        }else{
            return $this->apiResponse(null,'حدثت مشكلة يرجى المحاولة لاحقا',201);
        }
    }

}
