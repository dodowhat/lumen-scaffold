<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Models\AdminRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->all(), [
           'username' => 'bail|required|alpha_num|min:3|unique:admin_users',
           'password' => 'bail|required|min:8'
        ]);
        if ($validator->fails()) {
            $message = join(';', $validator->errors()->all());
            return response()
                ->json(['message' => $message], 422);
        }

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
        return AdminUser::findOrFail($id);
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
            $adminUser = AdminUser::findOrFail($id);
            $adminUser->roles()->detach();
            $adminUser->delete();
        });
    }

    public function assignRoles(Request $request, $id) {
        $adminUser = AdminUser::findOrFail($id);
        if (AdminUser::isAdmin($adminUser))
        {
            $adminRole = AdminRole::where('name', 'admin')->first();
            if (!in_array($adminRole->id, $request->role_ids) && $adminRole->users()->count() < 2)
            {
                return response()
                    ->json(['message' => "Not allowed to detach the last admin role user"], 422);
            }
        }

        $validator = Validator::make($request->all(), [
           'role_ids' => 'bail|array' 
        ]);
        if ($validator->fails()) {
            $message = join(';', $validator->errors()->all());
            return response()
                ->json(['message' => $message], 422);
        }

        $roles = AdminRole::whereIn('id', $request->role_ids)->get();
        $adminUser->roles()->sync($roles->modelKeys());
    }

    public function resetPassword(Request $request, $id) {
        $adminUser = AdminUser::findOrFail($id);
        $password = base64_encode(random_bytes(8));
        $adminUser->password = $password;
        $adminUser->save();
        return ['password' => $password];
    }
}
