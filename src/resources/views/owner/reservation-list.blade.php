@extends('layouts.owner')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/owner/reservation-list.css') }}" />
@endsection

@section('content')
<div class="all__wrapper">
    <div class="reservation-list">
        <h1 class="reservation-list__heading">予約状況</h1>
        @forelse($reservations as $reservation)
        <div class="reservation-card">
            <div class="reservation-card__cancel-form">
                <div class="reservation-card__heading-container">
                    <img class="reservation-card__image-clock" src="{{ asset('images/clock.png') }}" alt="時計">
                    <h2 class="reservation-card__heading">予約{{ $loop->iteration }}</h2>
                </div>
            </div>
            <div class="reservation-card__info">
                <div class="reservation-card__label">
                    <p class="reservation-card__row-label">Shop</p>
                    <p class="reservation-card__row-label">Date</p>
                    <p class="reservation-card__row-label">Time</p>
                    <p class="reservation-card__row-label">Number</p>
                </div>
                <div class="reservation-card__item">
                    <p class="reservation-card__row-item">{{ $reservation->shop->name }}</p>
                    <p class="reservation-card__row-item">{{ $reservation->date }}</p>
                    <p class="reservation-card__row-item">{{ $reservation->time_formatted }}</p>
                    <p class="reservation-card__row-item">{{ $reservation->people }}人</p>
                </div>
            </div>
        </div>
        @empty
        <p class="no-reservation">(予約はありません)</p>
        @endforelse
    </div>
</div>
@endsection