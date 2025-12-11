@extends('layouts.user')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/reservation/complete.css') }}" />
@endsection

@section('content')
<div class="all__wrapper">

    <!-- 予約完了ページ -->
    <div class="reservation-complete__wrapper">
        <p class="reservation-complete__message">
            <span class="reservation-complete__message-1">ご予約</span>
            <span class="reservation-complete__message-2">ありがとうございます</span>
        </p>

        <div class="reservation-complete__link-area">
            <a href="{{ route('shop.list') }}" class="reservation-complete__link-back">戻る</a>
        </div>
    </div>
</div>
@endsection