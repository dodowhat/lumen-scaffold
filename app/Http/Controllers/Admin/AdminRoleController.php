<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminRole;
use Facade\Ignition\Middleware\AddLogs;
use Illuminate\Http\Request;

class AdminRoleController extends Controller
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
        return AdminRole::paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $params = $request->only(['name']);
        AdminRole::create($params);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AdminRole  $adminRole
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return AdminRole::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AdminRole  $adminRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = AdminRole::find($id);
        $role->name = $request->name;
        $role->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdminRole  $adminRole
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = AdminRole::find($id);
        $role->delete();
    }

    public function assignActions(Request $request, $id)
    {
        $role = AdminRole::find($id);
        $role->actions()->sync($request->action_ids);
    }
}
