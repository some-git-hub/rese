@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/create.css') }}" />
@endsection

@section('content')
<div class="all__wrapper">

    <!-- 送信成功メッセージ -->
    @if (session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('admin.owner.store') }}" method="post">
        @csrf
        <h2>店舗代表者の作成</h2>

        <div class="form-group">
            <label>名前</label>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label>メールアドレス</label>
            <input type="email" name="email" value="{{ old('email') }}">
            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label>パスワード</label>
            <input type="password" name="password">
            @error('password')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button class="btn btn-primary">店舗代表者を作成</button>
    </form>
</div>
@endsection