@extends('layouts.user')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage/show.css') }}" />
@endsection

@section('content')
<div class="all__wrapper">
    <p class="user-name">{{ $user->name }}さん</p>
    <div class="mypage__wrapper">
        <div class="reservation-list">
            <h1 class="reservation-list__heading">予約状況</h1>
            @forelse($reservations as $reservation)
            <div class="reservation-card">
                <div class="reservation-card__cancel-form">
                    <div class="reservation-card__heading-container">
                        <img class="reservation-card__image-clock" src="{{ asset('images/clock.png') }}" alt="時計">
                        <h2 class="reservation-card__heading">予約{{ $loop->iteration }}</h2>
                    </div>
                    <button type="button" class="reservation-card__button-cancel" data-reservation-id="{{ $reservation->id }}">
                        <img class="reservation-card__image-close" src="{{ asset('images/close-2.png') }}" alt="キャンセル">
                    </button>
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
                <div class="reservation-card__button-area">
                    <button type="button" class="reservation-card__button-edit" data-reservation-id="{{ $reservation->id }}" data-shop-id="{{ $reservation->shop->id }}">変更する</button>
                </div>
            </div>
            @empty
            <p class="no-reservation">(予約はありません)</p>
            @endforelse

            <div id="editModal" class="modal hidden">
                <form class="edit-form" id="editForm">
                    @csrf
                    @method('PATCH')
                    <div class="edit-form__heading-container">
                        <img class="edit-form__image-clock" src="{{ asset('images/clock.png') }}" alt="時計">
                        <h2 class="edit-form__heading">予約内容の変更</h2>
                        <button class="edit-form__button-close" type="button" id="modalClose">閉じる</button>
                    </div>

                    <input type="hidden" name="reservation_id" id="modal_reservation_id">
                    <input type="hidden" name="shop_id" id="modal_shop_id">

                    <div class="edit-form__name-area">
                        <label class="edit-form__label">Shop</label>
                        <span class="edit-form__shop-name" id="modal_shop_name"></span>
                    </div>

                    <div class="edit-form__input-area">
                        <label class="edit-form__label">Date</label>
                        <input class="edit-form__input-date" type="date" name="date" id="modal_date">
                        <div class="error-text" id="error_date"></div>
                    </div>

                    <div class="edit-form__input-area">
                        <label class="edit-form__label">Time</label>
                        <input class="edit-form__input-time" type="time" name="time" id="modal_time">
                        <div class="error-text" id="error_time"></div>
                    </div>

                    <div class="edit-form__input-area">
                        <label class="edit-form__label">Number</label>
                        <select class="edit-form__select-people" type="number" name="people" id="modal_people">
                            @for ($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ old('people') == $i ? 'selected' : '' }}>
                                {{ $i }}人
                            </option>
                            @endfor
                        </select>
                        <div class="error-text" id="error_people"></div>
                    </div>

                    <div class="edit-form__button-area">
                        <button class="edit-form__button-update" type="submit">更新する</button>
                    </div>
                </form>
            </div>

        </div>
        <div class="favorite-shop-list__wrapper">
            <h1 class="favorite-shop-list__heading">お気に入り店舗</h1>
            <div class="favorite-shop-list__container">
                @forelse($favoriteShops as $favoriteShop)
                <div class="favorite-shop-card">
                    <img src="{{ asset('storage/shops/' . $favoriteShop->image) }}" alt="{{ $favoriteShop->name }}" class="favorite-shop-card__image">
                    <div class="favorite-shop-card__info">
                        <p class="favorite-shop-card__name">{{ $favoriteShop->name }}</p>
                        <div class="favorite-shop-card__tags">
                            <span class="favorite-shop-card__region">
                                @if($favoriteShop->region)
                                    #{{ $favoriteShop->region }}
                                @endif
                            </span>
                            <span class="favorite-shop-card__genre">
                                @if($favoriteShop->genre)
                                    #{{ $favoriteShop->genre }}
                                @endif
                            </span>
                        </div>
                        <div class="favorite-shop-card__link-area">
                            <a class="favorite-shop-card__link-detail" href="{{ route('shop.show', ['shop' => $favoriteShop->id, 'from' => 'mypage']) }}">詳しくみる</a>
                            <button type="button" class="favorite-shop-card__button-favorite" data-shop-id="{{ $favoriteShop->id }}">
                                @if(auth()->check() && auth()->user()->favorites()->where('shop_id', $favoriteShop->id)->exists())
                                <img src="{{ asset('images/favorite_active.png') }}" alt="favorite_active" class="favorite-image">
                                @else
                                <img src="{{ asset('images/favorite_inactive.png') }}" alt="favorite_inactive" class="favorite-image">
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <p class="no-favorite">(お気に入り店舗はありません)</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener("DOMContentLoaded", () => {

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

    const closeAlert = () => {
        alertBox.remove();
        location.reload();
    };

    // OKボタン
    alertBox.querySelector(".alert__button-close").addEventListener("click", closeAlert);

    // 背景クリック
    alertBox.addEventListener("click", (e) => {
        if (e.target === alertBox) closeAlert();
    });

    // Enter / Esc / Space キー
    const keyHandler = (e) => {
        if (["Enter", "Escape", " ", "Space"].includes(e.key)) {
            closeAlert();
            document.removeEventListener("keydown", keyHandler);
        }
    };
    document.addEventListener("keydown", keyHandler);
}



// 予約キャンセル
document.querySelectorAll(".reservation-card__button-cancel").forEach(button => {
    button.addEventListener("click", async () => {
        if(!confirm("本当にキャンセルしますか？")) return; // 確認ダイアログ

        const id = button.dataset.reservationId;

        const response = await fetch("{{ route('reservation.cancel', ':id') }}".replace(':id', id), {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            }
        });

        if (response.ok) {
            const card = button.closest(".reservation-card");
            if (card) card.remove();

            // すべての予約が削除された場合にメッセージ表示
            const reservationList = document.querySelector(".reservation-list");
            if (!reservationList.querySelector(".reservation-card")) {
                const p = document.createElement("p");
                p.classList.add("no-reservation");
                p.textContent = "(予約はありません)";
                reservationList.appendChild(p);
            }

            // --- キャンセル成功アラート ---
            showAlert("予約をキャンセルしました");
        }
    });
});


    // お気に入り切り替え
    document.querySelectorAll(".favorite-shop-card__button-favorite").forEach(button => {
        button.addEventListener("click", async () => {

            const shopId = button.dataset.shopId;

            const response = await fetch("{{ route('favorites.toggle', ':id') }}".replace(':id', shopId), {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                }
            });

            if (response.ok) {
                const img = button.querySelector("img");
                const card = button.closest(".favorite-shop-card");

                if (img.src.includes("favorite_active")) {
                    img.src = "{{ asset('images/favorite_inactive.png') }}";

                    if (card) card.remove();

                    // --- ここで空になったらメッセージを追加 ---
                    const container = document.querySelector(".favorite-shop-list__container");
                    if (!container.querySelector(".favorite-shop-card")) {
                        const p = document.createElement("p");
                        p.classList.add("no-favorite");
                        p.textContent = "(お気に入り店舗はありません)";
                        container.appendChild(p);
                    }

                } else {
                    img.src = "{{ asset('images/favorite_active.png') }}";

                    // 空メッセージが残っていたら削除
                    const noFavorite = document.querySelector(".no-favorite");
                    if (noFavorite) noFavorite.remove();
                }
            }
        });
    });

    // --- 編集モーダルを開く ---
    document.querySelectorAll(".reservation-card__button-edit").forEach(button => {
        button.addEventListener("click", () => {
            const card = button.closest(".reservation-card");
            const items = card.querySelectorAll(".reservation-card__row-item");

            document.getElementById("modal_shop_id").value = button.dataset.shopId;
            document.getElementById("modal_reservation_id").value = button.dataset.reservationId;

            document.getElementById("modal_shop_name").textContent = items[0].innerText.trim();
            document.getElementById("modal_date").value = items[1].innerText.trim();
            document.getElementById("modal_time").value = items[2].innerText.trim();
            document.getElementById("modal_people").value = items[3].innerText.replace("人","").trim();

            document.getElementById("editModal").classList.remove("hidden");
        });
    });

    // --- モーダル送信 ---
    document.getElementById("editForm").addEventListener("submit", async (e) => {
        e.preventDefault();

        const id = document.getElementById("modal_reservation_id").value;
        const formData = new FormData(e.target);
        formData.set('_method', 'PATCH');

        const response = await fetch(`{{ route('reservation.update', ':id') }}`.replace(':id', id), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();

if (response.ok) {
    const modal = document.getElementById("editModal");
    modal.classList.add("hidden");

    // DOM 更新
    const id = document.getElementById("modal_reservation_id").value;
    const card = document.querySelector(`.reservation-card__button-edit[data-reservation-id="${id}"]`).closest(".reservation-card");
    const items = card.querySelectorAll(".reservation-card__row-item");

    items[1].textContent = document.getElementById("modal_date").value; // Date
    items[2].textContent = document.getElementById("modal_time").value; // Time
    items[3].textContent = document.getElementById("modal_people").value + "人"; // People

    // フォームリセット
    document.getElementById("modal_date").value = "";
    document.getElementById("modal_time").value = "";
    document.getElementById("modal_people").value = "1";
    document.getElementById("error_date").textContent = "";
    document.getElementById("error_time").textContent = "";
    document.getElementById("error_people").textContent = "";

// 成功アラート表示
showAlert("予約内容を変更しました");

}



        // エラー表示
        document.getElementById("error_date").textContent = data.errors?.date?.join(" / ") || "";
        document.getElementById("error_time").textContent = data.errors?.time?.join(" / ") || "";
        document.getElementById("error_people").textContent = data.errors?.people?.join(" / ") || "";
    });


    // --- モーダル閉じる ---
    document.getElementById("modalClose").addEventListener("click", () => {
        document.getElementById("editModal").classList.add("hidden");

        document.getElementById("error_date").textContent = "";
        document.getElementById("error_time").textContent = "";
        document.getElementById("error_people").textContent = "";

        document.getElementById("modal_date").value = "";
        document.getElementById("modal_time").value = "";
        document.getElementById("modal_people").value = "1";
    });

});
</script>
@endsection