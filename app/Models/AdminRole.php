<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany(AdminUser::class);
    }

    public function actions()
    {
        return $this->belongsToMany(AdminAction::class);
    }

    public static function isAdmin($adminRole) {
        $adminRole->name == "admin";
    }
}
