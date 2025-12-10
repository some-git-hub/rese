@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/create.css') }}" />
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

    <form action="{{ route('admin.owner.store') }}" method="post" class="create-form">
        @csrf
        <h2 class="create-form__heading">店舗代表者の作成</h2>

        <div class="create-form__container">
            <p class="create-form__label">名前</p>
            <div class="create-form__input-area">
                <input type="text" name="name" value="{{ old('name') }}" class="create-form__input">
            </div>
            @error('name')
                <p class="create-form__error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="create-form__container">
            <p class="create-form__label">メールアドレス</p>
            <div class="create-form__input-area">
                <input type="email" name="email" value="{{ old('email') }}" class="create-form__input">
                @error('email')
                    <p class="create-form__error-message">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="create-form__container">
            <p class="create-form__label">パスワード</p>
            <div class="create-form__input-area">
                <input type="password" name="password" class="create-form__input">
                @error('password')
                    <p class="create-form__error-message">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="create-form__button-area">
            <button class="create-form__button-create">作成する</button>
        </div>
    </form>
</div>
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const alertBox = document.querySelector('.alert__success');
    const alertInner = document.querySelector('.alert__inner');
    const okButton = document.getElementById('alertOkButton');

    if (!alertBox) return;

    // ---- ① OKボタンで閉じる ----
    okButton.addEventListener('click', function () {
        alertBox.style.display = 'none';
    });

    // ---- ② Enterキーで閉じる ----
    document.addEventListener('keydown', (e) => {
        if (e.key === "Enter" || e.key === "Escape" || e.key === " " || e.key === "Space") {
            alertBox.remove();
        }
    });

    // ---- ③ アラート外クリックで閉じる ----
    alertBox.addEventListener('click', function (e) {
        if (!alertInner.contains(e.target)) {
            alertBox.style.display = 'none';
        }
    });
});
</script>
@endsection