<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return AdminUser::paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $params = $request->only(['username', 'password']);
        $params['jwt_secret'] = AdminUser::generateJWTSecret();
        AdminUser::create($params);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AdminUser  $adminUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $adminUser = AdminUser::find($id);
        $adminUser->save();
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
        $adminUser = AdminUser::find($id);
        $adminUser->delete();
    }

    public function assignRoles(Request $request, $id) {
        $adminUser = AdminUser::find($id);
        $adminUser->roles()->sync($request->role_ids);
    }
}
