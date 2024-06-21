<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Coupon;
use App\Models\Minisurvice;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Clickpaysa\Laravel_package\Facades\paypage;


class ReservationController extends Controller
{
    use ApiResponseTrait;
    use isFavoriteTrait;
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'age' => 'integer|min:1|max:100',
            'start_time' => 'date_format:H:i',
            'end_time' => 'date_format:H:i',
            'start_at' => 'date',
            'end_at' => 'date',
            'coupon' => 'string',
            'individuals' => 'integer',
            'price' => 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'tax_price' => 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'total_price' => 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'status' => 'string',

            'minisurvice_id' => 'integer',
            'region_id' => 'integer',
            'user_id' => 'integer',

        ]);

        if ($validate->fails()) {
            return $this->apiResponse(null, $validate->errors()->first(), 500);
        }
        if ($request->coupon) {
            $coupon = Coupon::whereRaw("BINARY coupon = '$request->coupon'")->whereDate('start_at', '<=', date('Y-m-d'))
                ->whereDate('end_at', '>=', date('Y-m-d'))->first();

            if ($request->coupon) {
                $coupon = Coupon::whereRaw("BINARY coupon = '$request->coupon'")->whereDate('start_at', '<=', date('Y-m-d'))
                    ->whereDate('end_at', '>=', date('Y-m-d'))->first();

                if ($coupon) {
                    if ($coupon->type == 'fix') {
                        $total_price = $request->total_price - $coupon->value;
                    } else if ($coupon->type == 'percent') {
                        $total_price = $request->total_price - (($request->total_price * $coupon->value) / 100);
                    } else {
                        $total_price = $request->total_price;
                    }
                } else {
                    // Handle the case where the coupon is not found
                    // For example, you can return an error response
                    return response()->json(['error' => 'Coupon not found'], 404);
                }
            } else {
                $total_price = $request->total_price;
            }
        } else {
            $total_price = $request->total_price;
        }

        $minisurvice = Minisurvice::where('id', '=', $request->minisurvice_id)->first();

        if ($minisurvice) {
            $points = $minisurvice->points;
        } else {
            $points = 0;
        }

        if ($minisurvice->humannumber - $minisurvice->reservationnumber >= ($request->baby_number + $request->adult_number)) {

            $human_price = ($request->baby_number * $minisurvice->baby_price) + ($request->adult_number * $minisurvice->adult_price);
            $tax = ($human_price * $minisurvice->tax )/100 ;
            if (($human_price + $tax) == $request->total_price) {

                $reservation = Reservation::with('user')->create([
                    'age' => $request->age,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'start_at' => $request->start_at,
                    'end_at' => $request->end_at,
                    'coupon' => $request->coupon,
                    'baby_number' => $request->baby_number,
                    'adult_number' => $request->adult_number,
                    'baby_price' => $minisurvice->baby_price,
                    'adult_price' => $minisurvice->adult_price,
                    'tax_price' => $tax,
                    'total_price' => $total_price,
                    'minisurvice_id' => $request->minisurvice_id,
                    'region_id' => $request->region_id,
                    'user_id' => $request->user_id,
                    'provider_id' => $minisurvice->provider_id,
                    'status' => 'wait',
                ]);

                $user = User::find($request->user_id);
                $minisurvice = Minisurvice::find($request->minisurvice_id);
                $reservation->load('user', 'minisurvice');

                $reservationnumber = Minisurvice::where('id', '=', $request->minisurvice_id)
                    ->update([
                        'reservationnumber' => DB::raw('reservationnumber + ' . $request->baby_number + $request->adult_number),
                    ]);

                if ($reservation) {
                    $pastpoints = User::where('id', '=', $request->user_id)->value('points');
                    $user = User::where('id', '=', $request->user_id)->update(['points' => $pastpoints + $points]);

                    $pointsdetails = DB::table('points')->insert([
                        'point' => $points,
                        'user_id' => $request->user_id,
                        'minisurvice_id' => $request->minisurvice_id,
                    ]);

                    return $this->apiResponse($reservation, ['تم اضافة الحجز بنجاح'], 200);
                } else {
                    return $this->apiResponse(null, 'غير موجود', 201);
                }

            } else {
                return $this->apiResponse(null, 'تأكد من المدفوعات', 201);
            }

        } else {
            return $this->apiResponse(null, 'عذرا لا يوجد عدد كافي من المقاعد المتبقية', 201);

        }
    }

    public function myreservation($userid)
    {
        $reservation = Reservation::where('user_id', '=', $userid)
            ->whereIn('status', ['accept', 'wait'])
            ->with(['region', 'minisurvice'])
            ->get();

        $reservation->map(function ($reservation) {
            $reservation->minisurvice->isFavorite = auth()->check() ? $this->isFavorite(auth()->user()->id, $reservation->minisurvice->id) : false;
            return $reservation;
        });

        $user = User::find($userid);
        $reservation->load('user');

        if ($reservation) {
            return $this->apiResponse($reservation, ['جميع الحجوزات الحالية لهذا المستخدم'], 200);
        } else {
            return $this->apiResponse(null, 'غير موجود', 201);
        }
    }
    public function mypastreservation($userid)
    {
        $reservation = Reservation::where('user_id', '=', $userid)->where('status', '=', 'end')->with(['region', 'minisurvice'])->get();

        $user = User::find($userid);
        $reservation->load('user');

        if ($reservation) {
            $reservation->map(function ($reservation) {
                $reservation->minisurvice->isFavorite = auth()->check() ? $this->isFavorite(auth()->user()->id, $reservation->minisurvice->id) : false;
                return $reservation;
            });
            return $this->apiResponse($reservation, 'جميع الحجوزات المنتهية لهذا المستخدم', 200);
        } else {
            return $this->apiResponse(null, 'غير موجود', 201);
        }
    }
    public function delete($id)
    {
        $reservation = Reservation::find($id);
        if (!$reservation) {
            return $this->apiResponse(null, 'عذرا الحجز غير موجودة', 500);
        } else {
            $reservation->delete($id);
            if ($reservation) {
                return $this->apiResponse(null, 'تم حذف الحجز بنجاح', 200);
            } else {
                return $this->apiResponse(null, 'حدثت مشكلة يرجى المحاولة لاحقا', 201);
            }
        }
    }
}
