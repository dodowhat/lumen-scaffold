<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActionGroup;
use Illuminate\Http\Request;

class AdminActionController extends Controller
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
        return AdminActionGroup::with('actions')->get();
    }

}
