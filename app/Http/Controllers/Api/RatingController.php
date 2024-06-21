<?php

namespace App\Http\Controllers\Api;

use App\Models\Rating;
use App\Models\Minisurvice;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{

    use ApiResponseTrait;
    use isFavoriteTrait ;

    public function show($id)
    {
        $rating = Rating::where('minisurvice_id', '=', $id)->get();
        $rowcount = Rating::where('minisurvice_id', '=', $id)->count();
        $ratingsum = Rating::where('minisurvice_id', '=', $id)->sum('rating');

        if ($rowcount == 0) {
            return $this->apiResponse(null, 'لا يوجد تقييمات سابقة', 200);
        }

        $minisurvicerating = $ratingsum / $rowcount;

        $rating->load('user');

        $response = [
            'ratings' => $rating,
            'minisurvicerating' => $minisurvicerating,
        ];

        if ($rating) {
            return $this->apiResponse($response, 'جميع التقيمات لهذه الخدمة', 200);
        } else {
            return $this->apiResponse(null, 'غير موجود', 201);
        }
    }

    public function userrating($id)
    {
        $rating = Rating::where('user_id', '=', $id)->with(['user', 'minisurvice'])->get();

        $rating->transform(function ($rate) {
            $rate->minisurvice->isFavorite = auth()->check() ? $rate->minisurvice->isFavorite(auth()->user()->id, $rate->minisurvice->id) : false;
            return $rate;
        });

        if ($rating) {
            return $this->apiResponse($rating, ['جميع التقيمات لهذا المستخدم'], 200);
        } else {
            return $this->apiResponse(null, 'غير موجود', 201);
        }
    }


    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'comment' => 'string',
            'rating' => 'integer|between:1,5',
            'user_id' => 'required|string',
            'minisurvice_id' => 'required|string',
        ]);

        if ($validate->fails()) {
            return $this->apiResponse(null, $validate->errors()->first(), 500);
        }

        $reservation = Reservation::where('user_id', '=', $request->user_id)
            ->where('minisurvice_id', '=', $request->minisurvice_id)
            ->first();

        if ($reservation) {
            $rating = Rating::where('user_id', '=', $request->user_id)
                ->where('minisurvice_id', '=', $request->minisurvice_id)->first();

            if (!$rating) {
                $minisurvice = Minisurvice::where('id', '=', $request->minisurvice_id)->first();
                $rating = Rating::create([
                    'comment' => $request->comment,
                    'rating' => $request->rating,
                    'user_id' => $request->user_id,
                    'minisurvice_id' => $request->minisurvice_id,
                    'provider_id' => $minisurvice->provider_id,
                ]);

                if ($rating) {
                    return $this->apiResponse($rating, 'تم اضافة التقييم بنجاح', 200);
                } else {
                    return $this->apiResponse(null, 'عذرا لم يتم اضافة التقييم يرجى اعادة المحاولة', 400);
                }
            } else {
                return $this->apiResponse(null, 'عذرا لقد قمت بتقييم هذه الخدمة سابقا', 200);
            }
        } else {
            return $this->apiResponse(null, 'عذرا لا يمكنك التقييم لانك غير مشترك', 200);
        }


    }

    public function edit(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'comment' => 'string',
            'rating' => 'integer|between:1,5',
            'user_id' => 'required|string',
            'minisurvice_id' => 'string',
        ]);

        if ($validate->fails()) {
            return $this->apiResponse(null, $validate->errors()->first(), 500);
        }

        $rating = Rating::find($id);

        if ($rating) {
            $rating->update([
                'comment' => $request->comment,
                'rating' => $request->rating,
            ]);
            return $this->apiResponse($rating, 'تم تعديل التقييم بنجاح', 200);
        } else {
            return $this->apiResponse(null, 'حدثت مشكلة يرجى المحاولة لاحقا', 201);
        }
    }

}
