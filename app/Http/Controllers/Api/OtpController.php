<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiResponseTrait;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class OtpController extends Controller
{
    use ApiResponseTrait;

    private function verifyOtp($user, $otp)
    {

        $result = OtpService::verifyOtp($user, $otp);
        if ($result == -2) {

            // OTP expired, should be resent
            OtpService::generateAndSendOtp($user);
            return $this->apiResponse(null, 'OTP expired', 401);
        }
        if ($result == -1)
            return $this->apiResponse(null, 'wrong OTP', 401);

        return $this->apiResponse(null, 'otp verified successfully', 200);
    }

    public function verifyOtpForRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'postal_code' => 'required|numeric',
            'phone' => 'required|numeric|digits:9|unique:users,phone',
            'otp' => 'required|string|size:6',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors()->first(), 500);
        }

        $user = User::where('temp_phone', $request->phone)->where('temp_postal_code', $request->postal_code)->first();


        $response = $this->verifyOtp($user, $request->otp);
        if ($response->status() == 200) {
            $user->update([
                'phone' => $user->temp_phone,
                'postal_code' => $user->temp_postal_code,
                'email' => $user->temp_email,
            ]);

            $token = JWTAuth::fromUser($user);

            return $this->apiResponse([
                'user' => $user,
                'token' => $token,
            ], 'User successfully verified', 200);
        }
        return $response;
    }

    public function verifyOtpForPasswordReset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'postal_code' => 'required|numeric',
            'phone' => 'required|numeric|digits:9',
            'otp' => 'required|string|size:6',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors()->first(), 500);
        }

        $user = User::where('phone', $request->phone)->where('postal_code', $request->postal_code)->first();

        return $this->verifyOtp($user, $request->otp);
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'postal_code' => 'required|numeric',
            'phone' => 'required|numeric|digits:9',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors()->first(), 500);
        }

        $phone = $request->phone;
        $pc = $request->postal_code;
        $user = User::where('phone', $phone)->where('postal_code', $pc)->first();

        if (is_null($user)) {
            $user = User::where('temp_phone', $phone)->where('temp_postal_code', $pc)->first();
            if (is_null($user))
                return $this->apiResponse(null, 'No user with such phone', 404);
        }

        OtpService::generateAndSendOtp($user, $pc . $phone);
        return $this->apiResponse(null, 'OTP resent', 200);
    }
}
