@extends('layouts.user')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/reservation/complete.css') }}" />
@endsection

@section('content')
<div class="all__wrapper">
    <div class="reservation-complete__wrapper">
        <p class="reservation-complete__message">
            ご予約ありがとうございます
        </p>

        <div class="reservation-complete__link-area">
            <a href="{{ route('shop.list') }}" class="reservation-complete__link-back">戻る</a>
        </div>
    </div>
</div>
@endsection