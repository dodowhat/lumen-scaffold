<?php

namespace App\Policies;

use App\Models\AdminUser;
use App\Models\AdminRole;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Builder;

class AdminRBACPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function authorize(AdminUser $adminUser, $action)
    {
        $adminRole = AdminRole::where('name', 'admin')->first();
        if ($adminUser->roles->contains($adminRole))
        {
            return true;
        }

        $roles = AdminRole::whereHas('actions', function (Builder $query) use($action) {
            $query->where('action', $action);
        })->get();
        $intersect = $adminUser->roles->intersect($roles);
        return count($intersect) > 0;
    }
}
