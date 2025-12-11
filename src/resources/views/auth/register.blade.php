@extends('layouts.user')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}" />
@endsection

@section('content')
<div class="all__wrapper">

    <!-- 会員登録フォーム -->
    <form class="register-form__wrapper" action="{{ route('register') }}" method="post">
        @csrf
        <h1 class="register-form__heading">
            Registration
        </h1>

        <div class="register-form__container">
            <div class="register-form__input-area">
                <img class="register-form__image-user" src="{{ asset('images/user.png') }}" alt="user">
                <input class="register-form__input" type="text" maxlength="20" name="name" value="{{ old('name') }}" placeholder="User name">
            </div>
            @error('name')
            <p class="register-form__error-message">
                {{ $message }}
            </p>
            @enderror
        </div>

        <div class="register-form__container">
            <div class="register-form__input-area">
                <img class="register-form__image-email" src="{{ asset('images/email.png') }}" alt="email">
                <input class="register-form__input" type="text" maxlength="255" name="email" value="{{ old('email') }}" placeholder="Email">
            </div>
            @error('email')
            <p class="register-form__error-message">
                {{ $message }}
            </p>
            @enderror
        </div>

        <div class="register-form__container">
            <div class="register-form__input-area">
                <img class="register-form__image-password" src="{{ asset('images/password.png') }}" alt="password">
                <input class="register-form__input" type="password" name="password" placeholder="Password">
            </div>
            @error('password')
            <p class="register-form__error-message">
                {{ $message }}
            </p>
            @enderror
        </div>

        <div class="register-form__button-area">
            <button type="submit" class="register-form__button-submit">登録</button>
        </div>
    </form>
</div>
@endsection