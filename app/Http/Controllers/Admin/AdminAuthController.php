<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminUser;
use App\Extensions\JWT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{

    public function login(Request $request)
    {
        $adminUser = AdminUser::where('username', $request->username)->with('roles')->first();

        if (!$adminUser || !Hash::check($request->password, $adminUser->password))
        {
            return response()
                ->json(['message' => "Invalid username or password"], 401);
        }

        $header = config('jwt.header');
        $prefix = config('jwt.prefix');
        $algorithm = config('jwt.algorithm');
        $audience = config('jwt.audience.admin');

        $jwt = JWT::create($algorithm);
        $jwt->sub = Crypt::encrypt($adminUser->id);
        $jwt->aud = Crypt::encrypt($audience);
        $token = $jwt->encode($adminUser->jwt_secret);

        return response()
            ->json($adminUser)
            ->header($header, $prefix . $token);
    }

    public function logout()
    {
        $currentUser = Auth::guard('admin_api')->user();

        $adminUser = AdminUser::findOrFail($currentUser->id);
        $secretLength = config('jwt.secret_length');
        $adminUser->jwt_secret = random_bytes($secretLength);
        $adminUser->save();
    }

    public function profile()
    {
        $currentUser = Auth::guard('admin_api')->user();
        return $currentUser;
    }

    public function updatePassword(Request $request) {
        $currentUser = Auth::guard('admin_api')->user();

        if (!Hash::check($request->password, $currentUser->password))
        {
            return response()
                ->json(['message' => "Authentication failed"], 422);
        }

        $validator = Validator::make($request->all(), [
            'new_password' => 'bail|required|min:8'
        ]);
        if ($validator->fails()) {
            $message = join(';', $validator->errors()->all());
            return response()
                ->json(['message' => $message], 422);
        }

        $adminUser = AdminUser::findOrFail($currentUser->id);
        $adminUser->password = $request->new_password;
        $adminUser->save();
    }
}
