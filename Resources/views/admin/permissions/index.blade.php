@extends('cockpit::layouts.admin')

@section('page-css')
<link href="{{asset('modules/cockpit/global/plugins/bootstrap-sweetalert/sweetalert.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('page-content')
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title"> 權限管理
        <small>Permission Manage</small>
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
                                <a class="btn green" href="{{action('Admin\PermissionController@create')}}">新增 <i class="fa fa-plus"></i></a>
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
                                <th>id</th>
                                <th>Name</th>
                                <th>Display Name</th>
                                <th>Type</th>
                                <th>Guard</th>
                                <th>動作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $permission)
                            <tr class="odd gradeX">
                                <td>
                                    <input type="checkbox" class="checkboxes ids" name="ids[]" value="{{$permission->id}}"/>
                                </td>
                                <td>{{$permission->id}}</td>
                                <td>{{$permission->name}}</td>
                                <td>{{$permission->display_name}}</td>
                                <td>
                                    @if($permission->type == 'admin')
                                    <span class="label label-sm label-warning"> Admin </span>
                                    @elseif($permission->type == 'team')
                                    <span class="label label-sm label-primary"> Team </span>
                                    @endif
                                </td>
                                <td>{{$permission->guard_name}}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                        動作 <i class="fa fa-angle-down"></i>
                                        </button>
                                        <ul class="dropdown-menu pull-right" role="menu">
                                            <li>
                                                <a href="{{action('Admin\PermissionController@edit',$permission->id)}}"><i class="fa fa-pencil"></i> 編輯 </a>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <a class="btn_permission_delete"><i class="fa fa-trash-o "></i>刪除</a>
                                                <form class="form_permission_delete" action="{{ action('Admin\PermissionController@destroy',$permission->id) }}" method="POST">
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
                    {!! $permissions->links() !!}
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
$(document).on('click','.btn_permission_delete',function(){
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
        console.log('test');
        btn.next('.form_permission_delete').submit();
    });
});
</script>
@endsection