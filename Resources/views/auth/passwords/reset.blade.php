@extends('cockpit::layouts.mauth')

@section('content')
    <!-- BEGIN FORGOT PASSWORD FORM -->
    <form class="reset-form" action="{{ route('password.request') }}" method="post">
        <h3 class="font-green">重置新密碼11111</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Email</label>
            <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email"  value="{{ $email ?? old('email') }}" required autofocus/> 
        </div>

        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">新密碼</label>
            <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="新密碼" name="password" required/> 
        </div>

        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">新密碼確認</label>
            <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="新密碼確認" name="password_confirmation" required/> 
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success uppercase">送出</button>
        </div>
    </form>
    <!-- END FORGOT PASSWORD FORM -->
@endsection