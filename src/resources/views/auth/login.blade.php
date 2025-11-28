@extends('layouts.user')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}" />
@endsection

@section('content')
<div class="all__wrapper">
    <form class="login-form__wrapper" action="{{ route('login') }}" method="post">
        @csrf
        <h1 class="login-form__heading">
            Login
        </h1>

        <!-- メールアドレスの入力欄 -->
        <div class="login-form__container">
            <div class="login-form__input-area">
                <img class="login-form__image-email" src="{{ asset('images/email.png') }}" alt="email">
                <input class="login-form__input" type="text" maxlength="255" name="email" value="{{ old('email') }}" placeholder="Email">
            </div>
            @error('email')
            <p class="login-form__error-message">
                {{ $message }}
            </p>
            @enderror
        </div>

        <!-- パスワードの入力欄 -->
        <div class="login-form__container">
            <div class="login-form__input-area">
                <img class="login-form__image-password" src="{{ asset('images/password.png') }}" alt="password">
                <input class="login-form__input" type="password" name="password" placeholder="Password">
            </div>
            @error('password')
            <p class="login-form__error-message">
                {{ $message }}
            </p>
            @enderror
        </div>

        <!-- ログインボタン -->
        <div class="login-form__button-area">
            <button type="submit" class="login-form__button-submit">ログイン</button>
        </div>
    </form>
</div>
@endsection