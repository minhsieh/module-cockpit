<?php

namespace Modules\Cockpit\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Cockpit\Entities\User;
use Modules\Cockpit\Entities\Team;

class IndexController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:superadmin']);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $count['users'] = User::all()->count();
        $count['teams'] = Team::all()->count();
        return view('cockpit::admin.index', ['count' => $count]);
    }
}
