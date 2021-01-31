<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminUser;
use App\Extensions\JWT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.authorize', ['only' => ['profile']]);
    }

    public function login(Request $request)
    {
        $adminUser = AdminUser::where('username', $request->username)->first();

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

        $adminUser = AdminUser::find($currentUser->id);
        $secretLength = config('jwt.secret_length');
        $adminUser->jwt_secret = random_bytes($secretLength);
        $adminUser->save();
    }

    public function profile()
    {
        $currentUser = Auth::guard('admin_api')->user();
        return $currentUser;
    }
}
