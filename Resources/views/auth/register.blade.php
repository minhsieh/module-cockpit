@extends('layouts.mauth')

@section('content')
    <!-- BEGIN REGISTRATION FORM -->
    <form class="register-form" action="{{ route('register') }}" method="post">
        @csrf
        <h3 class="font-green">{{ __('會員註冊11111') }}</h3>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Email -->
        <div class="form-group">
            <label class="control-label">Email
                <span class="required" aria-required="true"> * </span>
            </label>
            <input type="text" class="form-control placeholder-no-fix" name="email" value="{{old('email')}}"/>
            <span class="hint"> 請填寫正確Email，此為登入系統之帳號 </span> 
        </div>
        <!-- Password -->
        <div class="form-group">
            <label class="control-label">密碼
                <span class="required" aria-required="true"> * </span>
            </label>
            <input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="register_password" name="password" /> 
        </div>
        <!-- Re-Password -->
        <div class="form-group">
            <label class="control-label">密碼確認
                <span class="required" aria-required="true"> * </span>
            </label>
            <input class="form-control placeholder-no-fix" type="password" autocomplete="off" name="password_confirmation" /> 
        </div>
        <!-- 姓名 -->
        <div class="form-group">
            <label class="control-label">姓名
                <span class="required" aria-required="true"> * </span>
            </label>
            <input class="form-control placeholder-no-fix" type="text" name="name"  value="{{old('name')}}"/>
        </div>
        <div class="form-actions">
            <a type="button" id="register-back-btn" class="btn green btn-outline" href="{{ route('login') }}">返回</a>
            <button type="submit" id="register-submit-btn" class="btn btn-success uppercase pull-right">送出</button>
        </div>
    </form>
    <!-- END REGISTRATION FORM -->
@endsection