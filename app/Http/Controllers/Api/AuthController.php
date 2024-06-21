<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use phpseclib3\File\ASN1\Maps\OtherPrimeInfo;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class AuthController extends Controller
{

    use ApiResponseTrait;
    use UploadImageTrait;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' =>
            [
                'login',
                'register',
                'startPasswordReset',
                'setNewPassword'
            ]]);
    }

    //login function
    public function login(Request $request)
    {
        // Request Validation
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|digits:9|exists:users,phone',
            'postal_code' => 'required|numeric',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors()->first(), 400);
        }

        $user = User::where('phone', $request->phone)->where('postal_code', $request->postal_code)->first();

        if (!$user) {
            return $this->apiResponse(null, 'User not found', 404);
        }

        $credentials = [
            'phone' => $request->phone,
            'password' => $request->password,
        ];

        if (!$token = auth()->attempt($credentials)) {
            return $this->apiResponse(null, 'Invalid password', 401);
        }

        if (!OtpService::otpVerified($user)) {
            OtpService::generateAndSendOtp($user);
            return $this->apiResponse(null, 'User not otp-verified, otp message resent to phone', 401);
        }

        return $this->apiResponse([
            'token' => $token,
            'user' => $user,
        ], 'User successfully logged in', 200);
    }


    //register function
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'postal_code' => 'required|numeric',
            'phone' => 'required|numeric|digits:9|unique:users,phone',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors()->first(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'temp_email' => $request->email,
            'temp_postal_code' => $request->postal_code,
            'temp_phone' => $request->phone,
            'password' => bcrypt($request->password),
        ]);

        // Start OTP verification

        // Sending is happening for the first time though :)
        OtpService::generateAndSendOtp($user, $request->postal_code . $request->phone);

        return $this->apiResponse(null, 'User successfully registered, otp verification pending', 201);

    }

    //logout function
    public function logout()
    {
        auth()->logout();
        return $this->apiResponse(null, 'User successfully signed out', 401);

    }


    //show user profile
    public function userProfile()
    {
        return $this->apiResponse(auth()->user(), 'profile info', 200);
    }

    //refresh token
    public function refresh()
    {
        $token = auth()->refresh();
        return $this->apiResponse([
            'user' => auth()->user(),
            'token' => $token,
        ], 'token refreshed', 200);
    }


    public function editinfo(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'nullable|string|between:2,100',
            'email' => 'nullable|string|email|max:100',
            'phone' => 'nullable|numeric|digits:9',
            'postal_code' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sex' => 'nullable|string',
            'birthday' => 'nullable|string',
        ]);

        if ($validate->fails()) {
            return $this->apiResponse(null, $validate->errors()->first(), 500);
        }

        $user = User::find($request->userid);

        if ($user) {
            $updateData = [];

            if ($request->filled('name')) {
                $updateData['name'] = $request->name;
            }

            if ($request->filled('email')) {
                $updateData['email'] = $request->email;
            }

            if ($request->filled('phone')) {
                $updateData['phone'] = $request->phone;
            }

            if ($request->filled('postal_code')) {
                $updateData['postal_code'] = $request->postal_code;
            }

            if ($request->filled('sex')) {
                $updateData['sex'] = $request->sex;
            }

            if ($request->filled('birthday')) {
                $updateData['birthday'] = $request->birthday;
            }

            if ($request->filled('image')) {
                $updateData['image'] = $request->image;
                $imagePath = $this->uploadImage($updateData['image']);
            }

            if ($user->image != null) {
                $image = 'images/' . $user->image;
            } else {
                $image = '';
            }


            if ($request->file('image')) {
                $folder_path = 'user/' . $request->userid;

                $updateData['image'] = 'public/images/' . $this->uploadfile($request, $folder_path);
            }

            $user->update($updateData);

            return $this->apiResponse($user, 'تم تعديل البيانات بنجاح', 200);
        } else {
            return $this->apiResponse(null, 'حدثت مشكلة يرجى المحاولة لاحقا', 400);
        }
    }

    public function startPasswordReset(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'postal_code' => 'required|numeric',
            'phone' => 'required|numeric|digits:9',
        ]);
        if ($validate->fails()) {
            return $this->apiResponse(null, $validate->errors()->first(), 500);
        }

        $user = User::where('phone', $request->phone)->where('postal_code', $request->postal_code)->first();

        if (is_null($user))
            return $this->apiResponse(null, 'No user with such phone number', 404);

        OtpService::generateAndSendOtp($user, $request->postal_code . $request->phone);

        return $this->apiResponse(null, 'Password reset started, otp sent to phone', 200);
    }

    public function setNewPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_password' => 'required|string|min:8|confirmed',
            'postal_code' => 'required|numeric',
            'phone' => 'required|numeric|digits:9',
        ]);
        if ($validator->fails())
            return $this->apiResponse(null, $validator->errors()->first(), 500);

        $user = User::where('phone', $request->phone)->where('postal_code', $request->postal_code)->first();
        if (is_null($user))
            return $this->apiResponse(null, 'No user with such phone number', 404);

        $user->password = bcrypt($request->new_password);
        $user->save();

        $credentials = [
            'phone' => $request->phone,
            'password' => $request->new_password,
        ];
        $token = auth()->attempt($credentials);

        return $this->apiResponse([
            'user' => $user,
            'token' => $token,
        ], 'password reset successfully', 200);
    }

    public function updatePassword(Request $request, $userid)
    {

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::find($userid);

        // Verify the current password
        if (!Hash::check($request->current_password, $user->password)) {
            return $this->apiResponse(null, 'كلمات السر المدخلة غير صحيحة', 400);
        }

        $updateData['password'] = bcrypt($request->new_password);

        $user->update($updateData);

        return $this->apiResponse($user, 'تم تغيير كلمة السر بنجاح', 200);
    }

}
