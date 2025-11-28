@extends('layouts.owner')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/owner/shop-info.css') }}" />
@endsection

@section('content')
<div class="all__wrapper">
    <form action="{{ $isEdit ? route('owner.shop.update', $shop->id) : route('owner.shop.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif
        <h2>{{ $isEdit ? '店舗情報の編集' : '店舗情報の登録' }}</h2>

        <div class="form-group">
            <label>店舗名</label>
            <input type="text" name="name" value="{{ old('name', $shop->name ?? '') }}">
            @error('name')
            <p class="reservation-form__error-message">
                {{ $message }}
            </p>
            @enderror
        </div>

        <div class="form-group">
            <label>地域</label>
            <input type="text" name="region" value="{{ old('region', $shop->region ?? '') }}">
            @error('region')
            <p class="reservation-form__error-message">
                {{ $message }}
            </p>
            @enderror
        </div>

        <div class="form-group">
            <label>ジャンル</label>
            <input type="text" name="genre" value="{{ old('genre', $shop->genre ?? '') }}">
            @error('genre')
            <p class="reservation-form__error-message">
                {{ $message }}
            </p>
            @enderror
        </div>

        <div class="form-group">
            <label>説明</label>
            <textarea name="description">{{ old('description', $shop->description ?? '') }}</textarea>
            @error('description')
            <p class="reservation-form__error-message">
                {{ $message }}
            </p>
            @enderror
        </div>

        <div class="form-group">
            <label>画像</label>
            <input type="file" name="image">
            @if($isEdit && $shop->image)
                <img src="{{ asset('storage/shops/' . $shop->image) }}" class="preview-img">
            @endif
            @error('image')
            <p class="reservation-form__error-message">
                {{ $message }}
            </p>
            @enderror
        </div>

        <button class="btn-primary">
            {{ $isEdit ? '更新する' : '登録する' }}
        </button>
    </form>
</div>
@endsection