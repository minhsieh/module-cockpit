<?php

namespace Modules\Cockpit\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Cockpit\Entities\User;
use Modules\Cockpit\Entities\Team;
use Modules\Cockpit\Permission\Models\Role;

class UserController extends Controller
{
    protected $view_path = 'cockpit::admin.users';

    function __construct()
    {
        $this->middleware(['permission:manage_users']);
    }

    /**
     * Index for All User list
     * 
     * @return Response
     */
    public function index()
    {
        $users = User::orderBy('updated_at','DESC')->paginate(20);
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
        $role_table       = config('permission.table_names.roles');
        $team_table       = config('teamwork.teams_table');
        $team_user_table  = config('teamwork.team_user_table');
        $model_role_table = config('permission.table_names.model_has_roles');
        $team_roles = DB::table($role_table)
                    ->join( 'team_roles' , $role_table.'.id' , 'team_roles.role_id' )
                    ->join( $team_table , 'team_roles.team_id' , $team_table.'.id' )
                    ->join( $model_role_table , $role_table.'.id' , $model_role_table.'.role_id')
                    ->join( $team_user_table , $team_user_table.'.team_id' , $team_table.'.id' )
                    ->where( $model_role_table.'.model_id' , $user->id )
                    ->where( $team_user_table.'.user_id' , $user->id )
                    ->select(
                        $role_table.'.id' ,
                        $role_table.'.name' , 
                        $role_table.'.display_name' ,
                        $team_table.'.id AS team_id', 
                        $team_table.'.name AS team_name'
                    )
                    ->get()
                    ->groupBy('team_name')
                    ->toArray();

        $roles = DB::table($role_table)
                    ->join( 'team_roles' , $role_table.'.id' , 'team_roles.role_id')
                    ->join( $team_user_table , $team_user_table.'.team_id' , 'team_roles.team_id'  )
                    ->join( $team_table , $team_table.'.id' , $team_user_table.'.team_id' )
                    ->where( $team_user_table.'.user_id' , $user->id )
                    ->select( $role_table.'.*' , $team_table.'.name as team_name')
                    ->get();
        
        $teams = Team::select('id','name')->where('is_active',true)->get();

        return view($this->view_path.'.show',compact('user','team_roles','roles','teams'));
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

    /**
     * Assign Role to a User
     * 
     * @param $user_id
     * @return \Illuminate\Http\Response
     */
    public function assignRole(Request $request , $user_id)
    {
        $user = User::findOrFail($user_id);
        $role = Role::findOrFail($request->input('role_id'));

        $user->assignRole($role);

        return redirect()->action('Admin\UserController@show',$user_id)->with('success','Assign role "'.$role->display_name.'" success');
    }

    /**
     * Remove a Role from User
     * 
     * @param $user_id
     * @param $role_id
     * @return \Illuminate\Http\Response
     */
    public function removeRole($user_id , $role_id)
    {
        $user = User::findOrFail($user_id);
        $role = Role::findOrFail($role_id);

        $user->removeRole($role);

        return redirect()->action('Admin\UserController@show',$user_id)->with('success','Remove role "'.$role->display_name.'" success');
    }

    /**
     * Attach Team to this user
     * 
     * @param $user_id
     * @return response
     */
    public function attachTeam(Request $request , $user_id)
    {
        $teamModel = config('teamwork.team_model');
        $team = $teamModel::findOrFail($request->input('team_id'));
        
        $userModel = config('teamwork.user_model');
        $user = $userModel::findOrFail($user_id);

        $user->attachTeam($team);

        return redirect()->action('Admin\UserController@show',$user_id)->with('success','Attach user "'.$user->name.'" to team "'.$team->name.'" success');
    }

    /**
     * Detach this user from team_id
     */
    public function detachTeam($user_id , $team_id)
    {
        $teamModel = config('teamwork.team_model');
        $team = $teamModel::findOrFail($team_id);
        if (!auth()->user()->isOwnerOfTeam($team) && !auth()->user()->hasPermissionTo('manage_teams')) {
            abort(403);
        }

        $userModel = config('teamwork.user_model');
        $user = $userModel::findOrFail($user_id);
        if ($user->getKey() === auth()->user()->getKey() && !auth()->user()->hasPermissionTo('manage_teams') ) {
            abort(404);
        }

        $user->detachTeam($team);

        return redirect()->action('Admin\UserController@show',$user_id)->with('success','Detach user "'.$user->name.'" from team "'.$team->name.'" success');
    }
}
