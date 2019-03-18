@extends('cockpit::layouts.admin')

@section('page-css')
<link href="{{asset('modules/cockpit/global/plugins/bootstrap-sweetalert/sweetalert.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('page-content')
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title"> 團隊管理
        <small>Team Manage</small>
    </h1>
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- Success Message Alert-->
            @if (session('success'))
                <div class="alert alert-success alert-dismissable" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    {{ session('success') }}
                </div>
            @endif
            <!-- Success Message Alert-->

            <!-- Error Message Alert-->
            @if (session('error'))
                <div class="alert alert-danger alert-dismissable" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    {{ session('error') }}
                </div>
            @endif
            <!-- Error Message Alert-->

            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-body">
                    <div class="table-toolbar">
                        <div class="row">
                            <div class="col-md-12">
                                <a class="btn green" href="{{action('Admin\TeamController@create')}}">新增 <i class="fa fa-plus"></i></a>
                                <!-- <button class="btn red" style="display:none;">刪除 <i class="fa fa-trash"></i></button> -->
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped table-bordered table-hover" id="table_list">
                        <thead>
                            <tr>
                                <th class="table-checkbox">
                                    <input type="checkbox" class="group-checkable" name="ids[]" data-set="#table_list .checkboxes"/>
                                </th>
                                <th>編號</th>
                                <th>名稱</th>
                                <th>Owner</th>
                                <th>聯絡人</th>
                                <th>電話</th>
                                <th>Email</th>
                                <th>角色數</th>
                                <th>團隊成員</th>
                                <th>狀態</th>
                                <th>動作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($teams as $team)
                            <tr class="odd gradeX">
                                <td>
                                    <input type="checkbox" class="checkboxes ids" name="ids[]" value="{{$team->id}}"/>
                                </td>
                                <td><a href="{{ route('cockpit.teams.members.show' , $team->id) }}">{{ $team->tid }}</a></td>
                                <td><a href="{{ route('cockpit.teams.members.show' , $team->id) }}">{{ $team->name }}</a></td>
                                <td>@if(isset($team->owner)){{ $team->owner->name }} @else <span class="label label-default">No Owner</span> @endif</td>
                                <td>{{$team->contact}}</td>
                                <td>{{$team->tel}}</td>
                                <td>{{$team->email}}</td>
                                <td>
                                    @if($team->roles->count() == 0)
                                    <span class="label label-sm label-default"> 0 </span>
                                    @else
                                    <span class="label label-sm label-success"> {{ $team->roles->count() }} </span>
                                    @endif
                                </td>
                                <td>
                                    @if($team->users->count() == 0)
                                    <span class="label label-sm label-default"> 0 </span>
                                    @else
                                    <span class="label label-sm label-primary"> {{ $team->users->count() }} </span>
                                    @endif
                                </td>
                                <td>
                                    @if($team->is_active)
                                    <span class="label label-sm label-success"> 正常 </span>
                                    @else
                                    <span class="label label-sm label-warning"> 未啟用 </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                        動作 <i class="fa fa-angle-down"></i>
                                        </button>
                                        <ul class="dropdown-menu pull-right" role="menu">
                                            <li>
                                                <a href="{{ route('cockpit.teams.members.show' , $team->id) }}"><i class="fa fa-eye"></i> 查看 </a>
                                            </li>
                                            <li>
                                                <a href="{{action('Admin\TeamController@edit',$team->id)}}"><i class="fa fa-pencil"></i> 編輯 </a>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <a class="btn_team_delete"><i class="fa fa-trash-o "></i>刪除</a>
                                                <form class="form_team_delete" action="{{ action('Admin\TeamController@destroy',$team->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $teams->links() !!}
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>
</div>
<!-- END CONTENT BODY -->
@endsection


@section('page-js')
<script src="{{asset('modules/cockpit/global/plugins/bootstrap-sweetalert/sweetalert.min.js')}}" type="text/javascript"></script>
<script>
$(document).on('click','.btn_team_delete',function(){
    var btn = $(this);
    swal({
        title: "刪除",
        text: "您確定要刪除此團隊？",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "確認",
        cancelButtonText: "取消",
        closeOnConfirm: false
    },
    function(){
        console.log('test');
        btn.next('.form_team_delete').submit();
    });
});
</script>
@endsection