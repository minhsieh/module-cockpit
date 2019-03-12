@extends('layouts.mauth')

@section('content')
    <!-- BEGIN LOGIN FORM -->
    <form class="login-form" action="{{ route('login') }}" method="post">
        @csrf
        <h3 class="form-title font-green">會員登入1111111</h3>
        @if (session('registed-status'))
            <div class="alert alert-success alert-dismissable" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                {{ session('registed-status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div id="login-error" class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
            <span> 請輸入正確Email及密碼 </span>
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Email</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" value="{{ old('email') }}"  required autofocus/>
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">密碼</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="密碼" name="password" />
        </div>
        <div class="form-actions">
            <button type="submit" class="btn green uppercase">登入</button>
            <label class="rememberme check mt-checkbox mt-checkbox-outline">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}/>記住我
                <span></span>
            </label>
            <a href="{{ route('password.request') }}" id="forget-password" class="forget-password">忘記密碼</a>
        </div>
        <div class="create-account">
            <p>
                <a href="{{ route('register') }}" id="register-btn" class="uppercase">註冊新使用者</a>
            </p>
        </div>
    </form>
    <!-- END LOGIN FORM -->
@endsection