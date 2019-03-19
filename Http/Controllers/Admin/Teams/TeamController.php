<?php

namespace Modules\Cockpit\Http\Controllers\Admin\Teams;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Cockpit\Entities\Team;
use Modules\Cockpit\Entities\TeamRole;

class TeamController extends Controller
{
    protected $view_path = 'cockpit::admin.teams';

    function __construct()
    {
        $this->middleware(['permission:manage_teams']);
    }

    public function index()
    {
        $teams = Team::orderBy('updated_at','DESC')->paginate(20);
        $i = (request()->input('page', 1) - 1) * 20;
        return view($this->view_path.'.index',compact('teams'))->with('i', $i);
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
        
        $validator = Validator::make($request->all(), Team::$rules);
        $validator->setAttributeNames(Team::$attrs);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();

        $data['is_active'] = (!empty($data['is_active']) && $data['is_active'] == 'on')? true : false;

        try{
            Team::create($data);
        }catch(Illuminate\Database\QueryException $e){
            
        }

        return redirect()->action('Admin\Teams\TeamController@index')->with('success','Team created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        return view($this->view_path.'.show',compact('team'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team)
    {
        return view($this->view_path.'.form',['team' => $team, 'form_type' => 'edit']);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        $input = $request->all();

        $update_rules = Team::$rules;
        $update_rules['tid'] = $update_rules['tid'] . ',NULL,' . $team->id.',id';

        $validator = Validator::make($input, $update_rules);
        $validator->setAttributeNames(Team::$attrs);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $input['is_active'] = (!empty($input['is_active']) && $input['is_active'] == 'on')? true : false;

        
        try{
            $team->update($input);
        }catch(\Illuminate\Database\QueryException $e){
            $validator = Validator::make([],[]);
            $validator->errors()->add("","資料庫更新失敗");
            return redirect()->back()->withErrors($validator)->withInput();
        }

        return redirect()->action('Admin\Teams\TeamController@index')->with('success','Team updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        $team->roles()->delete();
        TeamRole::where('team_id',$team->id)->delete();
        $team->delete();

        return redirect()->action('Admin\Teams\TeamController@index')->with('success','Team "'.$team->name.'" deleted successfully');
    }
}
