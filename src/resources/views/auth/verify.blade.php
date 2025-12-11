@extends('layouts.user')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/verify.css') }}" />
@endsection

@section('content')
<div class="all__wrapper">

    <!-- メール認証誘導ページ -->
    <div class="email-verification__wrapper">
        <h1 class="email-verification__heading">Email Verification</h1>
        <p class="email-verification__link-area">
            <a class="email-verification__link-verify" href="http://localhost:8025/" target="_blank">メール認証はこちらから</a>
        </p>

        <form class="email-verification__form-resend" method="post" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="email-verification__button-resend">認証メールを再送する</button>
        </form>
    </div>
</div>
@endsection