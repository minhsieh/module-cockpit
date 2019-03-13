<?php

namespace Modules\Cockpit\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Cockpit\Permission\Models\Role;
use Modules\Cockpit\Permission\Models\Permission;

class RoleController extends Controller
{
    protected $view_path = 'cockpit::admin.roles';

    function __construct()
    {
        $this->middleware(['permission:manage_roles']);
    }

    public function index()
    {
        $roles = Role::orderBy('id','ASC')->paginate(20);
        $i = (request()->input('page', 1) - 1) * 20;
        return view($this->view_path.'.index',compact('roles'))->with('i', $i);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy('type')->toArray();
        return view($this->view_path.'.form',['form_type' => 'create', 'permissions' => $permissions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = Role::$rules;
        $rules['name'] = $rules['name']."|unique:".config('permission.table_names.roles');

        $validator = Validator::make($request->all(), $rules);

        $validator->setAttributeNames(Role::$attrs);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        unset($data['permissions']);

        try{
            $role = Role::create($data);
            $role->syncPermissions($request->input('permissions'));
        }catch(Illuminate\Database\QueryException $e){
            
        }

        return redirect()->action('Admin\RoleController@index')->with('success','Role created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        return view($this->view_path.'.show',compact('role'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy('type')->toArray();
        return view($this->view_path.'.form',['role' => $role, 'form_type' => 'edit', 'permissions' => $permissions]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $input = $request->all();

        $validator = Validator::make($input, Role::$rules);
        $validator->setAttributeNames(Role::$attrs);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        unset($input['permissions']);

        try{
            $role->update($input);
            $role->syncPermissions($request->input('permissions'));
        }catch(\Illuminate\Database\QueryException $e){
            $validator = Validator::make([],[]);
            $validator->errors()->add("","資料庫更新失敗");
            return redirect()->back()->withErrors($validator)->withInput();
        }

        return redirect()->action('Admin\RoleController@index')->with('success','Role updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->action('Admin\RoleController@index')->with('success','Role deleted successfully');
    }
}
