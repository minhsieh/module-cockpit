@extends('cockpit::layouts.admin')


@section('page-content')
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title"> 使用者管理
        <small>User Manage</small>
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
                        <i class="fa fa-plus"></i>新增使用者
                        @elseif($form_type == 'edit')
                        <i class="fa fa-pencil"></i>編輯使用者
                        @endif
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#normal" data-toggle="tab">一般設定 </a>
                        </li>
                    </ul>
                </div>
                <div class="portlet-body">
                    <form action="@if($form_type == 'create') {{action('Admin\UserController@store')}} @elseif($form_type == 'edit') {{action('Admin\UserController@update' , $user->id)}} @endif" method="POST" class="form-horizontal form-bordered"  enctype="multipart/form-data">
                    <div class="tab-content form-horizontal form-bordered">
                        <div class="tab-pane active" id="normal">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            @if($form_type == 'edit')
                                @method('PUT')
                            @endif
                            <!--姓名-->
                            <div class="form-group">
                                <label class="col-md-3 control-label">姓名<span class="required" aria-required="true"> * </span></label>
                                <div class="col-md-6">
                                    <input name="name" type="text" class="form-control" value="{{ old('name', $user->name ?? null) }}">
                                </div>
                            </div>
                            <!--Email-->
                            <div class="form-group">
                                <label class="col-md-3 control-label">Email<span class="required" aria-required="true"> * </span></label>
                                <div class="col-md-6">
                                    <input name="email" type="text" class="form-control" value="{{ old('email', $user->email ?? null) }}">
                                </div>
                            </div>
                            <!--密碼-->
                            <div class="form-group">
                                <label class="col-md-3 control-label">密碼<span class="required" aria-required="true"> * </span></label>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>
                            <!--密碼確認-->
                            <div class="form-group">
                                <label class="col-md-3 control-label">密碼確認<span class="required" aria-required="true"> * </span></label>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password_confirmation">
                                </div>
                            </div>
                            <!--帳號啟用-->
                            <div class="form-group">
                                <label class="control-label col-md-3">是否啟用</label>
                                <div class="col-md-3">
                                    <input type="checkbox" name="is_active" class="make-switch" data-on-text="啟用" data-off-text="不啟用" {{ (old('is_active') == 'on') ? "checked" : NULL ?? isset($user->is_active) && $user->is_active == 1 ? "checked": NULL }}>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="adv">
                            
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{action('Admin\UserController@index')}}" class="btn btn-default"><i class="fa fa-arrow-left"></i> 返回</a>
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