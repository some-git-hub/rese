@extends('layouts.user')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/thanks.css') }}" />
@endsection

@section('content')
<div class="all__wrapper">
    <div class="register-thanks__wrapper">
        <p class="register-thanks__message">
            会員登録ありがとうございます
        </p>

        <div class="register-thanks__link-area">
            <a href="{{ route('shop.list') }}" class="register-thanks__link-shop-list">ログインする</a>
        </div>
    </div>
</div>
@endsection