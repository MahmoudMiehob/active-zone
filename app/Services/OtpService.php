<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\PHPStan\AbstractMacro;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Http;

class OtpService
{

    public static function otpVerified($user): bool
    {
        return $user->otp_verified;
    }

    public static function generateAndSendOtp($user, $phone): bool
    {
        self::generate($user);
//        self::sendSms($user, $phone); //TODO

        return true;
    }

    public static function hasValidOtp($user): bool
    {
        if (is_null($user->otp))
            return false;
        if ($user->otp_expires_at < Carbon::now())
            return false;
        return true;
    }

    public static function generate($user): ?int
    {
//        $user->otp = rand(100000, 999999); //TODO
        $user->otp = 111111;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->otp_verified = false;
        $user->save();
        return $user->otp;
    }

    public static function sendSms($user, $phone): bool
    {

        $baseUrl = 'https://mora-sa.com/api/v1/sendsms';
        $params = [
            'api_key' => env('MORA_API_KEY'),
            'username' => env('MORA_USERNAME'),
        ];
        $uri = $baseUrl . '?' . http_build_query($params);

        $response = Http::post($uri,
            [
                'message' => 'Your OTP code is: ' . $user->otp,
//                'sender' => env('APP_NAME'),
                'sender' => 'Future C', // TODO
                'numbers' => $phone,
            ]
        );

        $statusCode = $response->status();
        if ($statusCode != 200)
            return -1;
        $responseData = json_decode($response->getBody(), true)['data'];
        if ($responseData['code'] != 100)
            return -1;
        return 0;
    }

    public static function verifyOtp($user, string $otp): int
    {
        if ($user->otp_expires_at < Carbon::now())
            return -2;
        if ($user->otp != $otp)
            return -1;
        $user->otp_verified = true;
        $user->save();

        return 0;
    }

}
