<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

use App\Casts\Hash as PasswordHash;

class AdminUser extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    protected $hidden = [
        'password',
        'jwt_secret',
    ];

    protected $casts = [
        'password' => PasswordHash::class
    ];

    protected $fillable = ['username', 'password'];

    public function roles()
    {
        return $this->belongsToMany(AdminRole::class);
    }

    public function getJwtSecretAttribute($value)
    {
        return base64_decode($value);
    }

    public function setJwtSecretAttribute($value)
    {
        $this->attributes['jwt_secret'] = base64_encode($value);
    }

    public static function isAdmin($adminUser) {
        $adminRole = AdminRole::where('name', 'admin')->first();
        return $adminUser->roles->contains($adminRole);
    }

    public static function generateJWTSecret()
    {
        $secretLength = config('jwt.secret_length');
        return random_bytes($secretLength);
    }
}
