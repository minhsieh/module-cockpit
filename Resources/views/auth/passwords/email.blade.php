@extends('layouts.mauth')

@section('content')
    <!-- BEGIN FORGOT PASSWORD FORM -->
    <form class="forget-form" action="{{ route('password.email') }}" method="post">
        <h3 class="font-green">忘記密碼了嗎?11111</h3>
        @csrf
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
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
        <p> 請輸入你的Email來重置你的密碼 </p>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Email</label>
            <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email"  value="{{ old('email') }}" /> 
        </div>

        <div class="form-actions">
            <a type="button" id="back-btn" class="btn green btn-outline" href="{{ route('login') }}">返回</a>
            <button type="submit" class="btn btn-success uppercase pull-right">送出</button>
        </div>
    </form>
    <!-- END FORGOT PASSWORD FORM -->
@endsection