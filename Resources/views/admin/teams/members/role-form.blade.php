@extends('cockpit::layouts.admin')

@section('page-content')
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title"> Team's Role </h1>
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
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
                        for team "{{ $team->name }}"
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#normal" data-toggle="tab">一般設定 </a>
                        </li>
                    </ul>
                </div>
                <div class="portlet-body form">
                    <form action="@if($form_type == 'create') {{action('Admin\Teams\TeamRoleController@storeRole',$team->id)}} @elseif($form_type == 'edit') {{action('Admin\Teams\TeamRoleController@updateRole',['id' => $team->id , 'role_id' => $role->id])}} @endif" method="POST" class="horizontal-form"  enctype="multipart/form-data">
                    <div class="tab-content">
                        <div class="tab-pane active" id="normal">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            @if($form_type == 'edit')
                                @method('PUT')
                            @endif
                            <div class="row form-body">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Display Name<span class="required" aria-required="true"> * </span></label>
                                        <input name="display_name" type="text" class="form-control" value="{{ old('display_name', $role->display_name ?? null) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Permissions<span class="required" aria-required="true"> * </span></label>
                                        <div class="mt-checkbox-list">
                                            @foreach($permissions as $permission)
                                                <label class="mt-checkbox">
                                                    <input  type="checkbox" 
                                                            name="permissions[]" 
                                                            value="{{ $permission->name }}"
                                                            @if(is_array(old('permissions')) && in_array($permission->name , old('permissions')))
                                                            checked
                                                            @elseif(isset($role->permissions) && $role->permissions->contains('name' , $permission->name))
                                                            checked 
                                                            @endif
                                                            > {{ $permission->display_name }}
                                                    <span></span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{action('Admin\Teams\TeamMemberController@show',$team->id)}}" class="btn btn-default"><i class="fa fa-arrow-left"></i> 返回</a>
                                <button type="submit" class="btn blue-hoki pull-right"><i class="fa fa-check"></i> 送出</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection