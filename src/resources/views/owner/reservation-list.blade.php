@extends('layouts.owner')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/owner/reservation-list.css') }}" />
@endsection

@section('content')
<div class="all__wrapper">
    <div class="reservation-list">
        <h1 class="reservation-list__heading">{{ $shop->name ?? '' }}の予約状況</h1>
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
                    <p class="reservation-card__row-label">Date</p>
                    <p class="reservation-card__row-label">Time</p>
                    <p class="reservation-card__row-label">Number</p>
                </div>
                <div class="reservation-card__item">
                    <p class="reservation-card__row-item">{{ $reservation->date }}</p>
                    <p class="reservation-card__row-item">{{ $reservation->time_formatted }}</p>
                    <p class="reservation-card__row-item">{{ $reservation->people }}人</p>
                </div>
            </div>
            <div class="reservation-card__button-area">
                <button type="button" class="reservation-card__button-complete" data-reservation-id="{{ $reservation->id }}" data-shop-id="{{ $reservation->shop->id }}">利用済み</button>
            </div>
        </div>
        @empty
        <p class="no-reservation">(予約はありません)</p>
        @endforelse
    </div>
</div>
@endsection

@section('js')
<script>

function showAlert(message) {
    const alertBox = document.createElement("div");
    alertBox.classList.add("alert__success");
    alertBox.innerHTML = `
        <div class="alert__inner">
            <p class="alert__message">${message}</p>
            <div class="alert__button-area">
                <button class="alert__button-close">OK</button>
            </div>
        </div>
    `;
    document.body.appendChild(alertBox);

    alertBox.querySelector(".alert__button-close").addEventListener("click", () => alertBox.remove());
    alertBox.addEventListener('click', (e) => { if (e.target === alertBox) alertBox.remove(); });

    const keyHandler = (e) => {
        if (["Enter", "Escape", " ", "Space"].includes(e.key)) {
            alertBox.remove();
            document.removeEventListener('keydown', keyHandler);
        }
    };
    document.addEventListener('keydown', keyHandler);
}

document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('.reservation-card__button-complete').forEach(button => {

        button.addEventListener('click', async () => {

            if (!confirm('この予約の利用を確認しましたか？')) return;

            const reservationId = button.dataset.reservationId;

            const response = await fetch(
                "{{ route('owner.reservation.complete', ':id') }}".replace(':id', reservationId),
                {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    }
                }
            );

            if (response.ok) {

                // カード削除
                const card = button.closest(".reservation-card");
                if (card) card.remove();

                // 予約なしメッセージ
                const reservationList = document.querySelector(".reservation-list");
                if (!reservationList.querySelector(".reservation-card")) {
                    const p = document.createElement("p");
                    p.classList.add("no-reservation");
                    p.textContent = "(予約はありません)";
                    reservationList.appendChild(p);
                }

                showAlert("予約状況を利用完了に更新しました");
            }
        });

    });

});
</script>
@endsection
