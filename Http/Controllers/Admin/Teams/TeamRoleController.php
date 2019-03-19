<?php

namespace Modules\Cockpit\Http\Controllers\Admin\Teams;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Cockpit\Permission\Models\Permission;
use Modules\Cockpit\Permission\Models\Role;
use Modules\Cockpit\Entities\Team;
use Modules\Cockpit\Entities\TeamRole;

class TeamRoleController extends Controller
{
    protected $view_path = 'cockpit::admin.teams';

    function __construct()
    {
        $this->middleware(['permission:manage_teams']);
    }

    public function addRole($team_id)
    {
        $team = Team::findOrFail($team_id);
        $permissions = Permission::where('type','team')->get();
        return view('cockpit::admin.teams.members.role-form',['team' => $team , 'permissions' => $permissions ,'form_type' => 'create']);
    }

    public function storeRole(Request $request, $team_id)
    {
        $rules = Role::$rules;
        $input = $request->all();
        $rules['name'] = $rules['name']."|unique:".config('permission.table_names.roles');
        $input['name'] = $team_id."_".$input['display_name'];
        $validator = Validator::make($input, $rules);

        $validator->setAttributeNames(Role::$attrs);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        unset($input['permissions']);

        try{
            $role = Role::create($input);
            $role->syncPermissions($request->input('permissions'));
            TeamRole::create(['team_id' => $team_id , 'role_id' => $role->id]);
        }catch(Illuminate\Database\QueryException $e){
            
        }
        return redirect()->action('Admin\Teams\TeamMemberController@show', $team_id )->with('success','Role "'.$role->display_name.'" created successfully.');
    }

    public function editRole($team_id, $role_id)
    {
        $team = Team::findOrFail($team_id);
        $role = Role::findOrFail($role_id);
        $permissions = Permission::where('type','team')->get();
        return view('cockpit::admin.teams.members.role-form',['team' => $team , 'role' => $role , 'permissions' => $permissions ,'form_type' => 'edit']);
    }

    public function updateRole(Request $request, $team_id, $role_id)
    {
        $role = Role::findOrFail($role_id);
        $rules = Role::$rules;
        $input = $request->all();
        $rules['name'] = $rules['name']."|unique:".config('permission.table_names.roles').",NULL,".$role->id.",id";
        $input['name'] = $team_id."_".$input['display_name'];
        $validator = Validator::make($input, $rules);
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

        return redirect()->action('Admin\Teams\TeamMemberController@show', $team_id )->with('success','Role "'.$role->display_name.'" updated successfully.');
    }

    public function deleteRole($team_id, $role_id)
    {
        $role = Role::findOrFail($role_id);
        TeamRole::where('role_id',$role_id)->delete();
        $role->delete();
        return redirect()->action('Admin\Teams\TeamMemberController@show', $team_id )->with('success','Role "'.$role->display_name.'" delete successfully.');
    }
}
