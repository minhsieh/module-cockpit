<?php

namespace Modules\Cockpit\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Cockpit\Permission\Models\Permission;

class PermissionController extends Controller
{
    protected $view_path = 'cockpit::admin.permissions';

    function __construct()
    {
        $this->middleware(['permission:manage_permissions']);
    }

    public function index()
    {
        $permissions = Permission::orderBy('id','ASC')->paginate(20);
        $i = (request()->input('page', 1) - 1) * 20;
        return view($this->view_path.'.index',compact('permissions'))->with('i', $i);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->view_path.'.form',['form_type' => 'create']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Permission::$rules);
        $validator->setAttributeNames(Permission::$attrs);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        try{
            Permission::create($data);
        }catch(Illuminate\Database\QueryException $e){
            
        }

        return redirect()->action('Admin\PermissionController@index')->with('success','Permission created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        return view($this->view_path.'.show',compact('permission'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        return view($this->view_path.'.form',['permission' => $permission, 'form_type' => 'edit']);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $input = $request->all();

        $validator = Validator::make($input, Permission::$rules);
        $validator->setAttributeNames(Permission::$attrs);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        
        try{
            $permission->update($input);
        }catch(\Illuminate\Database\QueryException $e){
            $validator = Validator::make([],[]);
            $validator->errors()->add("","資料庫更新失敗");
            return redirect()->back()->withErrors($validator)->withInput();
        }

        return redirect()->action('Admin\PermissionController@index')->with('success','Permission updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->action('Admin\PermissionController@index')->with('success','Permission deleted successfully');
    }
}
