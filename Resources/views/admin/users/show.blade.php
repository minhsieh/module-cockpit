@extends('cockpit::layouts.admin')

@section('page-css')
<link href="{{ asset('modules/cockpit/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('modules/cockpit/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('modules/cockpit/global/plugins/bootstrap-toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{asset('modules/cockpit/global/plugins/bootstrap-sweetalert/sweetalert.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('page-content')
    <div class="page-content">
        <div class="row margin-bottom-20">
            <div class="col-md-12">
                <a class="btn btn-default btn-xs" href="{{ action('Admin\UserController@index') }}">Back</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        User imformation "{{ $user->name }}"
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item"> Status:
                            @if($user->is_active)
                            <span class="label label-sm label-success pull-right"> 正常 </span>
                            @else
                            <span class="label label-sm label-warning pull-right"> 未啟用 </span>
                            @endif
                        </li>
                        <li class="list-group-item">Name: <span class="pull-right">{{ $user->name }}</span></li>
                        <li class="list-group-item">Email: <span class="pull-right">{{ $user->email }}</span></li>
                        <li class="list-group-item">Created at: <span class="pull-right">{{ $user->created_at }}</span></li>
                        <li class="list-group-item">Updated at: <span class="pull-right">{{ $user->updated_at }}</span></li>
                        <li class="list-group-item">Last login: <span class="pull-right">{{ $user->last_login }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        User Roles:
                    </div>
                    <div class="panel-body">
                        @foreach($team_roles as $team_k => $one_team_roles)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width:60%">{{ $team_k }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($one_team_roles as $role)
                                <tr>
                                    <td>{{ $role->display_name}}</td>
                                    <td>
                                        <a href="javascript:;" class="btn btn-xs btn-danger btn_role_delete"><i class="fa fa-trash-o"></i> Delete</a>
                                        <form class="form_role_delete" action="{{ action('Admin\UserController@removeRole',['id'=> $user->id , 'role_id' => $role->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endforeach


                        
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        Modify user role
                    </div>
                    <div class="panel-body form">
                        <form class="form" method="post" action="{{ action('Admin\UserController@assignRole',$user->id) }}">
                            {!! csrf_field() !!}
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="control-label">add this role to user</label>
                                    <select name="role_id" class="form-control select2">
                                        @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->team_name }} - {{ $role->display_name }}</option>
                                        @endforeach
                                    </select>
                                </div> 
                                <button type="submit" class="btn green">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        User Teams:
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Display Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->teams as $team)
                                <tr>
                                    <td>{{ $team->name}}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('page-js')
<script src="{{ asset('modules/cockpit/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('modules/cockpit/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
<script src="{{asset('modules/cockpit/global/plugins/bootstrap-sweetalert/sweetalert.min.js')}}" type="text/javascript"></script>
<script>
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

$(document).ready(function(){
    $('.select2').select2();

    @if (session('success'))
    toastr["success"]("{{ session('success') }}");
    @endif

    @if (session('error'))
    toastr["error"]("{{ session('error') }}");
    @endif
});
</script>
@endsection