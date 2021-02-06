<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminUser;
use App\Models\AppUser;
use App\Extensions\JWT;

use Lindelius\JWT\Exception\InvalidSignatureException;
use Lindelius\JWT\Exception\JwtException;
use Lindelius\JWT\Exception\InvalidJwtException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        // $this->app['auth']->viaRequest('api', function ($request) {
        //     if ($request->input('api_token')) {
        //         return User::where('api_token', $request->input('api_token'))->first();
        //     }
        // });

        Auth::viaRequest('admin_api', function ($request) {
            $token = $request->bearerToken();

            if (empty($token)) {
                Log::error('Invalid Token: empty token');
                return null;
            }

            try {
                $jwt = JWT::decode($token);

                $audience = config('jwt.audience.admin');
                $aud = Crypt::decrypt($jwt->aud);
                if ($aud != $audience) {
                    Log::error('Invalid Token: wrong audience');
                    return null;
                }

                $subject = Crypt::decrypt($jwt->sub);
                $adminUser = AdminUser::with('roles')->find($subject);

                $jwt->verify($adminUser->jwt_secret);
            } catch (InvalidSignatureException | JwtException | InvalidJwtException | DecryptException $e) {
                Log::error('Invalid Token: ' . $e->getMessage());
                return null;
            }

            return $adminUser;
        });

        Auth::viaRequest('app_api', function ($request) {
            $token = $request->bearerToken();

            if (empty($token)) {
                Log::error('Invalid Token: empty token');
                return null;
            }

            try {
                $jwt = JWT::decode($token);

                $audience = config('jwt.audience.app');
                $aud = Crypt::decrypt($jwt->aud);
                if ($aud != $audience) {
                    Log::error('Invalid Token: wrong audience');
                    return null;
                }

                $subject = Crypt::decrypt($jwt->sub);
                $appUser = AppUser::find($subject);

                $jwt->verify($appUser->jwt_secret);
            } catch (InvalidSignatureException | JwtException | InvalidJwtException | DecryptException $e) {
                Log::error('Invalid Token: ' . $e->getMessage());
                return null;
            }

            return $appUser;
        });

        Gate::define('admin-rbac', [\App\Policies\AdminRBACPolicy::class, 'authorize']);
    }
}
