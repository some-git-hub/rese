@extends('layouts.user')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/shop/show.css') }}" />
@endsection

@section('content')
<div class="all__wrapper">
    @if(session('success'))
    <div class="alert__success">
        <div class="alert__inner">
            <p class="alert__message">{{ session('success') }}</p>
            <div class="alert__button-area">
                <button class="alert__button-close" id="alertOkButton">OK</button>
            </div>
        </div>
    </div>
    @endif
    <div class="shop-info">
        <div class="shop-info__name-area">
            @if ($from === 'mypage')
            <a href="{{ route('mypage.show') }}" class="shop-info__button-back">&lt;</a>
            @else
            <a href="{{ route('shop.list') }}" class="shop-info__button-back">&lt;</a>
            @endif
            <span class="shop-info__name">{{ $shop->name }}</span>
        </div>
        <div class="shop-info__image-area">
            <img src="{{ asset('storage/shops/' . $shop->image) }}" alt="{{ $shop->name }}" class="shop-info__image">
        </div>
        <div class="shop-info__tags">
            <span class="shop-info__region">
                @if($shop->region)
                    #{{ $shop->region }}
                @endif
            </span>
            <span class="shop-info__genre">
                @if($shop->genre)
                    #{{ $shop->genre }}
                @endif
            </span>
        </div>
        <div class="shop-info__description">
            {{ $shop->description }}
        </div>

        <!-- レビュー -->
        @if($pendingReview)
        <div id="reviewModal" class="modal">
            <div class="review-form">
                <h2 class="review-form__heading">
                    <span class="review-form__heading-1">{{ $shop->name }}のレビューを</span>
                    <span class="review-form__heading-2">お願いします</span>
                </h2>
                <form class="review-form__inner" id="reviewForm" method="POST" action="{{ route('review.store', $pendingReview->id) }}">
                    @csrf
                    <!-- 評価 -->
                    <label class="review-form__label">評価</label>
                    <div class="review-form__input-area">
                        <input class="review-form__input" type="hidden" name="rating" id="rating" value="{{ old('rating') }}">
                        @for ($i = 1; $i <= 5; $i++)
                        <img src="{{ asset('images/star_inactive.png') }}" class="star-img" data-value="{{ $i }}" id="star-{{ $i }}">
                        @endfor
                    </div>
                    @error('rating')
                    <p class="review-form__error-message--rating">
                        {{ $message }}
                    </p>
                    @enderror

                    <!-- コメント -->
                    <label class="review-form__label">コメント</label>
                    <div class="review-form__textarea-area">
                        <textarea class="review-form__textarea" name="comment">{{ old('comment') }}</textarea>
                    </div>
                    @error('comment')
                    <p class="review-form__error-message">
                        {{ $message }}
                    </p>
                    @enderror
                </form>

                <div class="review-form__buttons">
                    <div class="review-form__buttons-inner">
                        <!-- 後で入力するボタン -->
                        <div class="review-form__button-area">
                            <button class="review-skip-form__button-close" id="reviewModalClose">後で入力する</button>
                        </div>

                        <!-- 評価しないボタン -->
                        <form class="review-skip-form" id="reviewSkipForm" method="POST" action="{{ route('review.skip', $pendingReview->id) }}">
                            @csrf
                            <button class="review-skip-form__button-skip" type="submit" id="reviewSkipButton">評価しない</button>
                        </form>
                    </div>

                    <!-- 送信するボタン -->
                    <div class="review-form__button-area">
                        <button class="review-skip-form__button-submit" type="submit" form="reviewForm">送信</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    <div class="shop-reservation">
        <form action="{{ route('reservation.store') }}" class="reservation-form" method="post">
            @csrf
            <h1 class="reservation-form__heading">予約</h1>
            <div class="reservation-form__input-area">
                <input type="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" class="reservation-form__input-date">
                @error('date')
                <p class="reservation-form__error-message">
                    {{ $message }}
                </p>
                @enderror
            </div>
            <div class="reservation-form__input-area">
                <input type="time" name="time" value="{{ old('time', '17:00') }}" class="reservation-form__input-time">
                @error('time')
                <p class="reservation-form__error-message">
                    {{ $message }}
                </p>
                @enderror
            </div>
            <div class="reservation-form__select-area">
                <select class="reservation-form__select-people" name="people">
                    @for ($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}" {{ old('people', 2) == $i ? 'selected' : '' }}>
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

    // --- 確認テーブル側 ---
    const dateCell = document.getElementById('confirm-date');
    const timeCell = document.getElementById('confirm-time');
    const peopleCell = document.getElementById('confirm-people');

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

    const stars = document.querySelectorAll('.star-img');
    const ratingInput = document.getElementById('rating');
    const onImg = "{{ asset('images/star_active.png') }}";
    const offImg = "{{ asset('images/star_inactive.png') }}";

    stars.forEach(star => {
        star.addEventListener('click', function () {
            const rating = this.dataset.value;
            ratingInput.value = rating;

            // 点灯更新
            stars.forEach(s => {
                if (s.dataset.value <= rating) {
                    s.src = onImg; // 点灯画像
                } else {
                    s.src = offImg; // 消灯画像
                }
            });
        });
    });

    const alertBox = document.querySelector('.alert__success');
    const alertOkButton = document.getElementById('alertOkButton');

    if (alertBox) {
        // OK ボタンで閉じる
        alertOkButton.addEventListener('click', () => {
            alertBox.remove();
        });

        // クリック全体で閉じる
        alertBox.addEventListener('click', (e) => {
            if (e.target === alertBox) {
                alertBox.remove();
            }
        });

        // Enter / Esc / Space ボタンで閉じる
        document.addEventListener('keydown', (e) => {
            if (e.key === "Enter" || e.key === "Escape" || e.key === " " || e.key === "Space") {
                alertBox.remove();
            }
        });
    }
});
</script>
@endsection