<?php

namespace Modules\Cockpit\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Modules\Cockpit\Services\Teamwork\Facades\Teamwork;
use Modules\Cockpit\Services\Teamwork\TeamInvite;
use Modules\Cockpit\Permission\Models\Permission;
use Modules\Cockpit\Permission\Models\Role;
use Modules\Cockpit\Entities\Team;
use Modules\Cockpit\Entities\TeamRole;

class TeamMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the members of the given team.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $teamModel = config('teamwork.team_model');
        $team = $teamModel::findOrFail($id);

        return view('cockpit::admin.teams.members.list')->withTeam($team);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $team_id
     * @param int $user_id
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy($team_id, $user_id)
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


        return redirect(action('Admin\TeamMemberController@show',$team->id))->with('success', 'Detach member "'.$user->name.'" from team "'.$team->name.'" success');
    }

    public function memberlist(Request $request)
    {
        $userModel = config('teamwork.user_model');

        $search = $request->input('term');

        $users = $userModel::select('id','name','email')->where('email','like',$search."%")->orWhere('name','like',$search."%")->paginate(10);

        return response()->json($users,200);
    }

    public function addMember(Request $request, $team_id)
    {
        $teamModel = config('teamwork.team_model');
        $team = $teamModel::findOrFail($team_id);
        
        $userModel = config('teamwork.user_model');
        $user = $userModel::findOrFail($request->input('user_id'));

        $user->attachTeam($team);

        return redirect(route('cockpit.teams.members.show',$team->id))->with('success', 'Add member "'.$user->name.'" to team "'.$team->name.'" success');
    }

    public function setOwner(Request $request, $team_id)
    {
        $teamModel = config('teamwork.team_model');
        $team = $teamModel::findOrFail($team_id);
        
        $userModel = config('teamwork.user_model');
        $user = $userModel::findOrFail($request->input('user_id'));

        $team->setOwner($user);

        return redirect(route('cockpit.teams.members.show',$team->id))->with('success', 'Set member "'.$user->name.'" as team "'.$team->name.'" owner success');
    }

    /**
     * @param Request $request
     * @param int $team_id
     * @return $this
     */
    public function invite(Request $request, $team_id)
    {
        $teamModel = config('teamwork.team_model');
        $team = $teamModel::findOrFail($team_id);

        if( !Teamwork::hasPendingInvite( $request->email, $team) )
        {
            Teamwork::inviteToTeam( $request->email, $team, function( $invite )
            {
                Mail::send('cockpit::admin.teams.emails.invite', ['team' => $invite->team, 'invite' => $invite], function ($m) use ($invite) {
                    $m->to($invite->email)->subject('Invitation to join team '.$invite->team->name);
                });
            });
        } else {
            return redirect()->back()->withErrors([
                'email' => 'The email address is already invited to the team.'
            ]);
        }
        
        return redirect(action('Admin\TeamMemberController@show', $team->id))->with('success', 'Invite "'.$request->email.' success');
    }

    /**
     * Resend an invitation mail.
     * 
     * @param $invite_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function resendInvite($invite_id)
    {
        $invite = TeamInvite::findOrFail($invite_id);
        Mail::send('cockpit::admin.teams.emails.invite', ['team' => $invite->team, 'invite' => $invite], function ($m) use ($invite) {
            $m->to($invite->email)->subject('Invitation to join team '.$invite->team->name);
        });

        return redirect(action('Admin\TeamMemberController@show', $invite->team))->with('success', 'Resend invite "'.$request->email.' success');
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
        return redirect()->action('Admin\TeamMemberController@show', $team_id )->with('success','Role "'.$role->display_name.'" created successfully.');
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

        return redirect()->action('Admin\TeamMemberController@show', $team_id )->with('success','Role "'.$role->display_name.'" updated successfully.');
    }

    public function deleteRole($team_id, $role_id)
    {
        $role = Role::findOrFail($role_id);
        TeamRole::where('role_id',$role_id)->delete();
        $role->delete();
        return redirect()->action('Admin\TeamMemberController@show', $team_id )->with('success','Role "'.$role->display_name.'" delete successfully.');
    }

}
