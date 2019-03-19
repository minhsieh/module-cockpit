@extends('cockpit::layouts.admin')

@section('page-css')
<link href="{{ asset('modules/cockpit/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('modules/cockpit/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('modules/cockpit/global/plugins/bootstrap-toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('modules/cockpit/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-content')
    <div class="page-content">
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        Team Information "{{ $team->name }}"
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item"> Status:
                            @if($team->is_active)
                            <span class="label label-sm label-success pull-right"> 正常 </span>
                            @else
                            <span class="label label-sm label-warning pull-right"> 未啟用 </span>
                            @endif
                        </li>
                        <li class="list-group-item"> Owner:
                            @if(isset($team->owner))
                            <span style="float:right;"> {{ $team->owner->name }} </span>
                            @else
                            <span class="badge badge-default"> No Owner</span>
                            @endif
                        </li>
                        <li class="list-group-item"> Members:
                            <span class="badge badge-success"> {{ $team->users->count() }} </span>
                        </li>
                        <li class="list-group-item"> Invites:
                            <span class="badge badge-warning"> {{ $team->invites->count() }} </span>
                        </li>
                    </ul>
                    @if($team->remark)
                    <div class="panel-body">
                        {!! app('parsedown')->text($team->remark) !!}
                    </div>
                    @endif
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        Add Member
                    </div>
                    <div class="panel-body form">
                        <form class="form" method="post" action="{{ route('cockpit.teams.members.addmember',$team->id) }}">
                            {!! csrf_field() !!}
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="control-label">Add member into this team</label>
                                    <select name="user_id" class="form-control select2-memberlist"></select>
                                </div> 
                                <button type="submit" class="btn green">Add</button>
                            </div>
                        </form>
                    </div>
                </div>


                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        Set Owner
                    </div>
                    <div class="panel-body form">
                        <form class="form" method="post" action="{{ route('cockpit.teams.members.setowner',$team->id) }}">
                            {!! csrf_field() !!}
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="control-label">Set this team owner</label>
                                    <select name="user_id" class="form-control select2-memberlist"></select>
                                </div> 
                                <button type="submit" class="btn green">Set</button>
                            </div>
                        </form>
                    </div>
                </div>


            </div>
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        Members of team "{{$team->name}}"
                        <div class="pull-right">
                            <a href="{{action('Admin\Teams\TeamController@edit',$team->id)}}" class="btn btn-xs btn-primary">
                                <i class="fa fa-pencil"></i> Edit
                            </a>
                            <a href="{{route('cockpit.teams.index')}}" class="btn btn-xs btn-default">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            @foreach($team->users AS $user)
                                <tr>
                                    <td><a href="{{ action('Admin\UserController@show',$user->id) }}">{{$user->name}}</a></td>
                                    <td>
                                        @if((auth()->user()->isOwnerOfTeam($team) && auth()->user()->getKey() !== $user->getKey()) || auth()->user()->can('manage_teams'))
                                            <form style="display: inline-block;" action="{{route('cockpit.teams.members.destroy', [$team, $user])}}" method="post">
                                                {!! csrf_field() !!}
                                                <input type="hidden" name="_method" value="DELETE" />
                                                <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Remove</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">Pending invitations</div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>E-Mail</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            @foreach($team->invites as $invite)
                                <tr>
                                    <td>{{$invite->email}}</td>
                                    <td>
                                        <a href="{{route('cockpit.teams.members.resend_invite', $invite)}}" class="btn btn-sm btn-default">
                                            <i class="fa fa-envelope-o"></i> Resend invite
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>


                <div class="panel panel-default">
                    <div class="panel-heading clearfix">Invite to team "{{$team->name}}"</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" action="{{route('cockpit.teams.members.invite', $team)}}">
                            {!! csrf_field() !!}
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">E-Mail Address</label>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-envelope-o"></i>Invite to Team
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Team's roles manger -->
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">Team's roles
                        <div class="pull-right">
                            <a href="{{ route('cockpit.teams.members.addrole',$team->id) }}" class="btn btn-xs btn-success">
                                <i class="fa fa-plus"></i> Add
                            </a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Permissions</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            @if(!empty($team->roles))
                            @foreach($team->roles as $role)
                                <tr>
                                    <td>{{$role->display_name}}</td>
                                    <td>
                                        @if($role->permissions->count() == 0)
                                        <span class="label label-sm label-default"> 0 </span>
                                        @else
                                        <span class="label label-sm label-primary"> {{ $role->permissions->count() }} </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ action('Admin\Teams\TeamRoleController@editRole',['id' => $team->id , 'role_id' => $role->id]) }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-pencil"></i> Edit
                                        </a>
                                        <a href="javascript:;" class="btn btn-sm btn-danger btn_role_delete"><i class="fa fa-trash-o"></i> Delete</a>
                                        <form class="form_role_delete" action="{{ action('Admin\Teams\TeamRoleController@deleteRole',['id'=> $team->id, 'role_id' => $role->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @endif
                        </table>
                    </div>
                </div>
                <!-- Team's roles manger -->
            </div>
        </div>
    </div>
@endsection

@section('page-js')
<script src="{{ asset('modules/cockpit/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('modules/cockpit/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
<script src="{{asset('modules/cockpit/global/plugins/bootstrap-sweetalert/sweetalert.min.js')}}" type="text/javascript"></script>
<script>
$(document).ready(function() {
    $('.select2-memberlist').select2({
        
        ajax: {
            url: "{{ route('cockpit.teams.members.memberlist') }}",
            delay: 250,
            data: function (params) {
                return params;
            },
            processResults: function (data) {
                var results = new Array();
                results.push({
                    'id': 0,
                    'text': '[Disable Owner]'
                })
                $.each(data.data, function(){
                    results.push({
                        'id': this.id,
                        'text': this.name+"["+this.email+"]"
                    });
                });

                return {
                    results: results
                };
            },
        }
    });

    @if (session('success'))
    toastr["success"]("{{ session('success') }}");
    @endif

    @if (session('error'))
    toastr["error"]("{{ session('error') }}");
    @endif
});

$(document).on('click','.btn_role_delete',function(){
    var btn = $(this);
    swal({
        title: "刪除",
        text: "您確定要刪除此項目？",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "確認",
        cancelButtonText: "取消",
        closeOnConfirm: false
    },
    function(){
        btn.next('.form_role_delete').submit();
    });
});
</script>
@endsection