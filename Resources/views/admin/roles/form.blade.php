@extends('cockpit::layouts.admin')

@section('page-css')
<link href="{{asset('modules/cockpit/global/plugins/simple-markdown-editor/simplemde.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('page-content')
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title"> 角色管理
        <small>Role Manage</small>
    </h1>
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PORTLET-->
            @if ($errors->any())
                <div id="form_error_alert" class="alert alert-danger">
                <button class="close" data-close="alert"></button>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="portlet box blue-hoki">
                <div class="portlet-title">
                    <div class="caption">
                        @if($form_type == 'create') 
                        <i class="fa fa-plus"></i>新增角色
                        @elseif($form_type == 'edit')
                        <i class="fa fa-pencil"></i>編輯角色
                        @endif
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#normal" data-toggle="tab">一般設定 </a>
                        </li>
                    </ul>
                </div>
                <div class="portlet-body">
                    <form action="@if($form_type == 'create') {{action('Admin\RoleController@store')}} @elseif($form_type == 'edit') {{action('Admin\RoleController@update' , $role->id)}} @endif" method="POST" class="form-horizontal form-bordered"  enctype="multipart/form-data">
                    <div class="tab-content form-horizontal form-bordered">
                        <div class="tab-pane active" id="normal">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            @if($form_type == 'edit')
                                @method('PUT')
                            @endif
                            <!--name-->
                            <div class="form-group">
                                <label class="col-md-3 control-label">Name<span class="required" aria-required="true"> * </span></label>
                                <div class="col-md-6">
                                    <input name="name" type="text" class="form-control" value="{{ old('name', $role->name ?? null) }}">
                                </div>
                            </div>
                            <!--display_name-->
                            <div class="form-group">
                                <label class="col-md-3 control-label">Display Name<span class="required" aria-required="true"> * </span></label>
                                <div class="col-md-6">
                                    <input name="display_name" type="text" class="form-control" value="{{ old('display_name', $role->display_name ?? null) }}">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="adv">
                            
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{action('Admin\RoleController@index')}}" class="btn btn-default"><i class="fa fa-arrow-left"></i> 返回</a>
                                <button type="submit" class="btn blue-hoki pull-right"><i class="fa fa-check"></i> 送出</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <!-- END PORTLET-->
        </div>
    </div>
</div>
<!-- END CONTENT BODY -->
@endsection

@section('page-js')
<script src="{{asset('modules/cockpit/global/plugins/simple-markdown-editor/simplemde.min.js')}}" type="text/javascript"></script>
<script>
var simplemde = new SimpleMDE({ element: document.getElementById("mde-remark") });
</script>
@endsection