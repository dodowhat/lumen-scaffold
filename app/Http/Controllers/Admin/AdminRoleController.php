<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminRole;
use App\Models\AdminAction;
use Facade\Ignition\Middleware\AddLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        return AdminRole::with('actions')->get();
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
           'name' => 'bail|required|alpha_num|min:3|unique:admin_roles' 
        ]);
        if ($validator->fails()) {
            $message = join(';', $validator->errors()->all());
            return response()
                ->json(['message' => $message], 422);
        }
        $params = $request->only(['name']);
        AdminRole::create($params);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdminRole  $adminRole
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = AdminRole::findOrFail($id);

        if ($role->name == 'admin')
        {
            return response()
                ->json(['message' => "Not allowed to delete role 'admin'"], 422);
        }

        if ($role->users()->count() > 0)
        {
            return response()
                ->json(['message' => "There are users assigned to this role.\nDetach first"], 422);
        }

        DB::transaction(function() use($role) {
            $role->users()->detach();
            $role->actions()->detach();
            $role->delete();
        });
    }

    public function assignActions(Request $request, $id)
    {
        $role = AdminRole::findOrFail($id);
        if ($role->name == 'admin') {
            return response()
                ->json(['message' => "Role 'admin' no need to assign actions"], 422);
        }

        $validator = Validator::make($request->all(), [
           'action_ids' => 'bail|array' 
        ]);
        if ($validator->fails()) {
            $message = join(';', $validator->errors()->all());
            return response()
                ->json(['message' => $message], 422);
        }

        $actions = AdminAction::whereIn('id', $request->action_ids)->get();
        $role->actions()->sync($actions->modelKeys());
    }
}
