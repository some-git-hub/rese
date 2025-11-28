@extends('layouts.user')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/shop/show.css') }}" />
@endsection

@section('content')
<div class="all__wrapper">
    <div class="shop-info">
        <div class="shop-info__name-area">
            <a href="{{ route('shop.list') }}" class="shop-info__button-back">&lt;</a>
            <span class="shop-info__name">{{ $shop->name }}</span>
        </div>
        <div class="shop-info__image-area">
            <img src="{{ asset('storage/shops/' . $shop->image) }}" alt="{{ $shop->name }}" class="shop-info__image">
        </div>
        <div class="shop-info__tags">
            <span class="shop-info__region">#{{ $shop->region }}</span>
            <span class="shop-info__genre">#{{ $shop->genre }}</span>
        </div>
        <div class="shop-info__description">
            {{ $shop->description }}
        </div>
        @if($pendingReview)
        <div id="reviewModal" class="modal">
            <div class="modal-content">
                <h2>{{ $shop->name }}の評価をお願いします</h2>
                <form id="reviewForm" method="POST" action="{{ route('review.store', $pendingReview->id) }}">
                    @csrf
                    <label>評価:</label>
                    <select name="rating">
                        <option value="" disabled hidden {{ old('rating') ? '' : 'selected' }}>選択してください</option>
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                {{ $i }}★
                            </option>
                        @endfor
                    </select>
                    @error('rating')
                        <p class="error">{{ $message }}</p>
                    @enderror
                    <label>コメント:</label>
                    <textarea name="comment">{{ old('comment') }}</textarea>
                    <button type="submit">送信</button>
                </form>
                <button id="reviewModalClose">後で入力する</button>
                <form id="reviewSkipForm" method="POST" action="{{ route('review.skip', $pendingReview->id) }}">
                    @csrf
                    <button type="submit" id="reviewSkipButton">評価しない</button>
                </form>
            </div>
        </div>
        @endif
    </div>
    <div class="shop-reservation">
        <form action="{{ route('reservation.store') }}" class="reservation-form" method="post">
            @csrf
            <h1 class="reservation-form__heading">予約</h1>
            <div class="reservation-form__input-area">
                <input type="date" name="date" value="{{ old('date') }}" class="reservation-form__input-date">
                @error('date')
                <p class="reservation-form__error-message">
                    {{ $message }}
                </p>
                @enderror
            </div>
            <div class="reservation-form__input-area">
                <input type="time" name="time" value="{{ old('time') }}" class="reservation-form__input-time">
                @error('time')
                <p class="reservation-form__error-message">
                    {{ $message }}
                </p>
                @enderror
            </div>
            <div class="reservation-form__select-area">
                <select class="reservation-form__select-people" name="people">
                    <option value="" disabled selected hidden>選択してください</option>
                    @for ($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}" {{ old('people') == $i ? 'selected' : '' }}>
                        {{ $i }}人
                    </option>
                    @endfor
                </select>
                @error('people')
                <p class="reservation-form__error-message">
                    {{ $message }}
                </p>
                @enderror
            </div>
            <table class="reservation-confirm-table">
                <tr class="reservation-confirm-table__row-shop">
                    <th class="reservation-confirm-table__label reservation-confirm-table__label-first">Shop</th>
                    <td class="reservation-confirm-table__item reservation-confirm-table__item-first" id="confirm-shop">{{ $shop->name }}</td>
                </tr>
                <tr class="reservation-confirm-table__row-date">
                    <th class="reservation-confirm-table__label">Date</th>
                    <td class="reservation-confirm-table__item" id="confirm-date">未選択</td>
                </tr>
                <tr class="reservation-confirm-table__row-time">
                    <th class="reservation-confirm-table__label">Time</th>
                    <td class="reservation-confirm-table__item" id="confirm-time">未選択</td>
                </tr>
                <tr class="reservation-confirm-table__row-people">
                    <th class="reservation-confirm-table__label reservation-confirm-table__label-last">Number</th>
                    <td class="reservation-confirm-table__item reservation-confirm-table__item-last" id="confirm-people">未選択</td>
                </tr>
            </table>
            <div class="reservation-form__button-area">
                <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                <button type="submit" class="reservation-form__button-submit">予約する</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // --- 入力欄（input / select） ---
    const dateInput = document.querySelector('.reservation-form__input-date');
    const timeInput = document.querySelector('.reservation-form__input-time');
    const peopleSelect = document.querySelector('.reservation-form__select-people');

    // --- 確認テーブル側（td） ---
    const dateCell = document.getElementById('confirm-date');
    const timeCell = document.getElementById('confirm-time');
    const peopleCell = document.getElementById('confirm-people');

    // --- 入力されたらリアルタイム反映 ---

    // 日付
    dateInput.addEventListener('input', function () {
        dateCell.textContent = this.value;
    });

    // 時刻
    timeInput.addEventListener('input', function () {
        timeCell.textContent = this.value;
    });

    // 人数
    peopleSelect.addEventListener('change', function () {
        // value=""（選択してください）の場合は空白にする
        peopleCell.textContent = this.value ? this.value + '人' : '';
    });

    // --- ページ読み込み時（old() の値がある場合）は即反映 ---
    if (dateInput.value) dateCell.textContent = dateInput.value;
    if (timeInput.value) timeCell.textContent = timeInput.value;
    if (peopleSelect.value) peopleCell.textContent = peopleSelect.value + '人';

    // --- モーダル ---
    const reviewModal = document.getElementById('reviewModal');
    if (reviewModal) {
        reviewModal.style.display = 'block';

        document.getElementById('reviewModalClose').addEventListener('click', () => {
            reviewModal.style.display = 'none';
        });
    }

    const skipForm = document.getElementById('reviewSkipForm');
    if (skipForm) {
        skipForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(skipForm);

            const response = await fetch(skipForm.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            });

            if (response.ok) {
                alert('評価をスキップしました');
                reviewModal.style.display = 'none';
            }
        });
    }
});
</script>
@endsection