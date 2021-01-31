<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminAction extends Model
{
    use HasFactory;

    protected $fillable = ['action', 'description'];

    public function roles()
    {
        return $this->belongsToMany(AdminRole::class);
    }
}
