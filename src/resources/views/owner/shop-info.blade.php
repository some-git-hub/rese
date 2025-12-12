@extends('layouts.owner')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/owner/shop-info.css') }}" />
@endsection

@section('content')
<div class="all__wrapper">

    <!-- 送信成功メッセージ -->
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

    <!-- 店舗情報の作成および変更フォーム -->
    <form class="create-form" action="{{ $isEdit ? route('owner.shop.update', $shop->id) : route('owner.shop.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif
        <h2 class="create-form__heading">{{ $isEdit ? '店舗情報の更新' : '店舗情報の登録' }}</h2>

        <div class="create-form__container">
            <p class="create-form__label">店舗画像</p>
            <div class="create-form__image-area">
                <input type="file" name="image" id="imageInput" class="create-form__image-input-hidden">
                <img id="imagePreview" src="{{ $isEdit && $shop->image ? asset('storage/shops/' . $shop->image) : '' }}" class="preview-img" style="{{ $isEdit && $shop->image ? '' : 'display:none;' }}">
            </div>
            <div class="create-form__image-label-area">
                <label for="imageInput" class="create-form__image-upload-label">画像を選択する</label>
            </div>
            @error('image')
                <p class="create-form__error-message-image">{{ $message }}</p>
            @enderror
        </div>

        <div class="create-form__container">
            <p class="create-form__label">店舗名</p>
            <div class="create-form__input-area">
                <input class="create-form__input" type="text" name="name" value="{{ old('name', $shop->name ?? '') }}">
            </div>
            @error('name')
                <p class="create-form__error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="create-form__container">
            <p class="create-form__label">地域</p>
            <div class="create-form__input-area">
                <input class="create-form__input" type="text" name="region" value="{{ old('region', $shop->region ?? '') }}">
            </div>
            @error('region')
                <p class="create-form__error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="create-form__container">
            <p class="create-form__label">ジャンル</p>
            <div class="create-form__input-area">
                <input class="create-form__input" type="text" name="genre" value="{{ old('genre', $shop->genre ?? '') }}">
            </div>
            @error('genre')
                <p class="create-form__error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="create-form__container">
            <p class="create-form__label">店舗概要</p>
            <div class="create-form__textarea-area">
                <textarea class="create-form__textarea" name="description">{{ old('description', $shop->description ?? '') }}</textarea>
            </div>
            @error('description')
                <p class="create-form__error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="create-form__button-area">
            <button class="create-form__button-submit">{{ $isEdit ? '更新する' : '登録する' }}</button>
        </div>
    </form>
</div>
@endsection

@section('js')
<script>

document.getElementById('imageInput').addEventListener('change', function (event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        // 画像が解除された場合は非表示
        preview.src = '';
        preview.style.display = 'none';
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const alertBox = document.querySelector('.alert__success');
    const alertInner = document.querySelector('.alert__inner');
    const okButton = document.getElementById('alertOkButton');

    if (!alertBox) return;

    // ---- OKボタンで閉じる ----
    okButton.addEventListener('click', function () {
        alertBox.style.display = 'none';
    });

    // ---- Enterキーで閉じる ----
    document.addEventListener('keydown', (e) => {
        if (e.key === "Enter" || e.key === "Escape" || e.key === " " || e.key === "Space") {
            alertBox.remove();
        }
    });

    // ---- アラート外クリックで閉じる ----
    alertBox.addEventListener('click', function (e) {
        if (!alertInner.contains(e.target)) {
            alertBox.style.display = 'none';
        }
    });
});

</script>
@endsection