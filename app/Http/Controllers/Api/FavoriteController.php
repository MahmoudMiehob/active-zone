<?php


namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Minisurvice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{
    use ApiResponseTrait;
    use isFavoriteTrait;

    public function update(Request $request)
    {
        $user = User::find($request->userid);
        $minisurvice = Minisurvice::find($request->minisurviceid);

        if (!$user) {
            return $this->apiResponse(null, 'عذرا لم يتم اضافة الى المفضلة يرجى اعادة المحاولة', 201);
        }

        if (!$minisurvice) {
            return $this->apiResponse(null, 'الخدمة غير موجودة', 201);
        }

        if ($user->minisurvices->contains($minisurvice->id)) {
            return $this->apiResponse(null, 'الخدمة مضافة بالفعل للمستخدم', 201);
        }

        $user->minisurvices()->attach($minisurvice->id);
        return $this->apiResponse($user, 'تم اضافة الى المفضلة   بنجاح', 200);
    }


    public function remove(Request $request)
    {
        $user = User::find($request->userid);
        $minisurvice = Minisurvice::find($request->minisurviceid);

        if ($minisurvice) {
            if ($user) {
                $user->minisurvices()->detach($request->minisurviceid);
                return $this->apiResponse(null, 'تم حذف المفضلة بنجاح', 200);
            } else {
                return $this->apiResponse(null, 'عذرا لم يتم حذف المفضلة يرجى اعادة المحاولة', 400);
            }
        } else {
            return $this->apiResponse(null, 'الخدمة غير موجودة', 201);
        }
    }


    public function show(Request $request)
    {
        $favoritesminisurvice = User::where('id', '=', $request->userid)
            ->with('minisurvices')
            ->first();

        if (!$favoritesminisurvice) {
            return $this->apiResponse(null, 'تعذر جلب المفضلة يرجى اعادة المحاولة', 201);
        }

        $favoritesminisurvice->minisurvices->transform(function ($minisurvice) {
            $minisurvice->isFavorite = true;
            return $minisurvice;
        });

        $response = [
            'data' => [
                'user_id' => $favoritesminisurvice->id,
                'minisurvices' => $favoritesminisurvice->minisurvices,
            ],
            'message' => 'جلب المفضلة بنجاح',
            'status' => 200,
        ];

        return response()->json($response);
    }
}
