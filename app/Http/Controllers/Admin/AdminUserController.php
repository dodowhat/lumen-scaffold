<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Models\AdminRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.authorize');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AdminUser::with('roles')->paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $adminUser = new AdminUser;
        $adminUser->username = $request->username;
        $adminUser->password = $request->password;
        $adminUser->jwt_secret = AdminUser::generateJWTSecret();
        $adminUser->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AdminUser  $adminUser
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return AdminUser::find($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdminUser  $adminUser
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $currentUser = Auth::guard('admin_api')->user();
        if ($currentUser->id == $id)
        {
            return response()
                ->json(['message' => "Cannot delete your self"], 422);
        }
        DB::transaction(function() use($id) {
            $adminUser = AdminUser::find($id);
            $adminUser->roles()->detach();
            $adminUser->delete();
        });
    }

    public function assignRoles(Request $request, $id) {
        $adminUser = AdminUser::find($id);
        if (AdminUser::isAdmin($adminUser))
        {
            $adminRole = AdminRole::where('name', 'admin')->first();
            if (!in_array($adminRole->id, $request->role_ids) && count($adminRole->users) < 2)
            {
                return response()
                    ->json(['message' => "Not allowed to detach the last admin role user"], 422);
            }
        }
        $adminUser->roles()->sync($request->role_ids);
    }

    public function resetPassword(Request $request, $id) {
        $adminUser = AdminUser::find($id);
        $password = base64_encode(random_bytes(8));
        $adminUser->password = $password;
        $adminUser->save();
        return ['password' => $password];
    }
}
