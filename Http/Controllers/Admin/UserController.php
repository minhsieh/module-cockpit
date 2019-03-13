<?php

namespace Modules\Cockpit\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Cockpit\Entities\User;

class UserController extends Controller
{
    protected $view_path = 'cockpit::admin.users';

    function __construct()
    {
        //$this->middleware(['permission:manage_users']);
        
    }

    public function index()
    {
        $users = User::latest()->paginate(20);
        $i = (request()->input('page', 1) - 1) * 20;
        return view($this->view_path.'.index',compact('users'))->with('i', $i);
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
        $validator = Validator::make($request->all(), User::$rules);
        $validator->setAttributeNames(User::$attrs);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();

        $data['is_active'] = (!empty($data['is_active']) && $data['is_active'] == 'on')? true : false;

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'is_active' => $data['is_active'],
        ]);

        return redirect()->action('Admin\UserController@index')->with('success','User created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view($this->view_path.'.show',compact('user'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view($this->view_path.'.form',['user' => $user, 'form_type' => 'edit']);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $input = $request->all();

        $update_rules = User::$rules;
        $update_rules['email'] = $update_rules['email'] . ',NULL,' . $user->id.',id';
        if(empty($request->input('password'))){
            unset($update_rules['password']);
            unset($input['password']);
        }

        $validator = Validator::make($input, $update_rules);
        $validator->setAttributeNames(User::$attrs);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }
        $input['is_active'] = (!empty($input['is_active']) && $input['is_active'] == 'on')? true : false;

        $user->update($input);

        return redirect()->action('Admin\UserController@index')->with('success','User updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->action('Admin\UserController@index')->with('success','User deleted successfully');
    }
}
