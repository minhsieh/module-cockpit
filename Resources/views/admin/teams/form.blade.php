@extends('cockpit::layouts.admin')

@section('page-css')
<link href="{{asset('modules/cockpit/global/plugins/simple-markdown-editor/simplemde.min.css')}}" rel="stylesheet" type="text/css" />
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
                        <i class="fa fa-plus"></i>新增團隊
                        @elseif($form_type == 'edit')
                        <i class="fa fa-pencil"></i>編輯團隊
                        @endif
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#normal" data-toggle="tab">一般設定 </a>
                        </li>
                    </ul>
                </div>
                <div class="portlet-body">
                    <form action="@if($form_type == 'create') {{action('Admin\TeamController@store')}} @elseif($form_type == 'edit') {{action('Admin\TeamController@update' , $team->id)}} @endif" method="POST" class="form-horizontal form-bordered"  enctype="multipart/form-data">
                    <div class="tab-content form-horizontal form-bordered">
                        <div class="tab-pane active" id="normal">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            @if($form_type == 'edit')
                                @method('PUT')
                            @endif
                            <!--編號-->
                            <div class="form-group">
                                <label class="col-md-3 control-label">編號<span class="required" aria-required="true"> * </span></label>
                                <div class="col-md-6">
                                    <input name="tid" type="text" class="form-control" value="{{ old('tid', $team->tid ?? null) }}">
                                </div>
                            </div>
                            <!--名稱-->
                            <div class="form-group">
                                <label class="col-md-3 control-label">名稱<span class="required" aria-required="true"> * </span></label>
                                <div class="col-md-6">
                                    <input name="name" type="text" class="form-control" value="{{ old('name', $team->name ?? null) }}">
                                </div>
                            </div>
                            <!--聯絡人-->
                            <div class="form-group">
                                <label class="col-md-3 control-label">聯絡人<span class="required" aria-required="true"> * </span></label>
                                <div class="col-md-6">
                                    <input name="contact" type="text" class="form-control" value="{{ old('contact', $team->contact ?? null) }}">
                                </div>
                            </div>
                            <!--電話-->
                            <div class="form-group">
                                <label class="col-md-3 control-label">電話<span class="required" aria-required="true"> * </span></label>
                                <div class="col-md-6">
                                    <input name="tel" type="text" class="form-control" value="{{ old('tel', $team->tel ?? null) }}">
                                </div>
                            </div>
                            <!--Email-->
                            <div class="form-group">
                                <label class="col-md-3 control-label">Email<span class="required" aria-required="true"> * </span></label>
                                <div class="col-md-6">
                                    <input name="email" type="text" class="form-control" value="{{ old('email', $team->email ?? null) }}">
                                </div>
                            </div>
                            <!--備註-->
                            <div class="form-group">
                                <label class="col-md-3 control-label">備註<span class="required" aria-required="true"> * </span></label>
                                <div class="col-md-6">
                                    <textarea id="mde-remark" name="remark" class="form-control" rows="5">{{ old('remark', $team->remark ?? null) }}</textarea>
                                </div>
                            </div>
                            <!--帳號啟用-->
                            <div class="form-group">
                                <label class="control-label col-md-3">是否啟用</label>
                                <div class="col-md-3">
                                    <input type="checkbox" name="is_active" class="make-switch" data-on-text="啟用" data-off-text="不啟用" {{ (old('is_active') == 'on') ? "checked" : NULL ?? isset($team->is_active) && $team->is_active == 1 ? "checked": NULL }}>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="adv">
                            
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{action('Admin\TeamController@index')}}" class="btn btn-default"><i class="fa fa-arrow-left"></i> 返回</a>
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