<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppUser;
use App\Extensions\JWT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class AppController extends Controller
{
    public function login(Request $request)
    {
        $appUser = AppUser::where('username', $request->username)->first();

        if (!$appUser || !Hash::check($request->password, $appUser->password))
        {
            return response()
                ->json(['message' => "Invalid username or password"], 401);
        }

        $header = config('jwt.header');
        $prefix = config('jwt.prefix');
        $algorithm = config('jwt.algorithm');
        $audience = config('jwt.audience.app');

        $jwt = JWT::create($algorithm);
        $jwt->sub = Crypt::encrypt($appUser->id);
        $jwt->aud = Crypt::encrypt($audience);
        $token = $jwt->encode($appUser->jwt_secret);

        return response()
            ->json($appUser)
            ->header($header, $prefix . $token);
    }

    public function logout()
    {
        $currentUser = Auth::guard('app_api')->user();

        $appUser = AppUser::find($currentUser->id);
        $secretLength = config('jwt.secret_length');
        $appUser->jwt_secret = random_bytes($secretLength);
        $appUser->save();
    }

    public function profile()
    {
        $currentUser = Auth::guard('app_api')->user();
        return $currentUser;
    }
}
