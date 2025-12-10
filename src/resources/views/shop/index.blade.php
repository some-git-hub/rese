@extends('layouts.user')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/shop/index.css') }}" />
@endsection

@section('content')
<div class="all__wrapper">
    <!-- 検索フォーム -->
    <form class="search-form__wrapper" id="search-form" action="{{ route('shop.list') }}" method="get">
        <div class="search-form__container">
            <!-- エリア -->
            <select class="search-form__region" name="region" onchange="document.getElementById('search-form').submit()">
                <option class="search-form__option" value="">All area</option>
                @foreach ($regions as $region)
                <option class="search-form__option" value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>
                    {{ $region }}
                </option>
                @endforeach
            </select>

            <!-- ジャンル -->
            <select class="search-form__genre" name="genre" onchange="document.getElementById('search-form').submit()">
                <option class="search-form__option" value="">All genre</option>
                @foreach ($genres as $genre)
                <option class="search-form__option" value="{{ $genre }}" {{ request('genre') == $genre ? 'selected' : '' }}>
                    {{ $genre }}
                </option>
                @endforeach
            </select>

            <!-- 店名 -->
            <span class="search-form__image-area">
                <img src="{{ asset('images/search.png') }}" alt="search" class="search-form__image-search">
            </span>
            <div class="search-form__input-area">
                <input class="search-form__shop-name" type="text" name="keyword" placeholder="Search..." value="{{ request('keyword') }}" onchange="document.getElementById('search-form').submit()">
            </div>
        </div>
    </form>

    <!-- 飲食店一覧 -->
    <div class="shop-list">
        @if($shops->isEmpty())
            <p class="no-shop">(店舗はありません)</p>
        @else
            @foreach($shops as $shop)
            <div class="shop-card">
                <img src="{{ asset('storage/shops/' . $shop->image) }}" alt="{{ $shop->name }}" class="shop-card__image">
                <div class="shop-card__info">
                    <p class="shop-card__name">{{ $shop->name }}</p>
                    <div class="shop-card__tags">
                        <span class="shop-card__region">
                            @if($shop->region)
                                #{{ $shop->region }}
                            @endif
                        </span>
                        <span class="shop-card__genre">
                            @if($shop->genre)
                                #{{ $shop->genre }}
                            @endif
                        </span>
                    </div>
                    <div class="shop-card__link-area">
                        <a class="shop-card__link-detail" href="{{ route('shop.show', ['shop' => $shop->id, 'from' => 'list']) }}">詳しくみる</a>
                        @auth
                        <button type="button" class="shop-card__button-favorite" data-shop-id="{{ $shop->id }}">
                            @if(auth()->check() && auth()->user()->favorites()->where('shop_id', $shop->id)->exists())
                            <img src="{{ asset('images/favorite_active.png') }}" alt="favorite_active" class="favorite-image">
                            @else
                            <img src="{{ asset('images/favorite_inactive.png') }}" alt="favorite_inactive" class="favorite-image">
                            @endif
                        </button>
                        @else
                        <a class="shop-card__button-favorite" href="{{ route('login') }}">
                            <img src="{{ asset('images/favorite_inactive.png') }}" alt="favorite_inactive" class="favorite-image">
                        </a>
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".shop-card__button-favorite").forEach(button => {

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
                // 現在の画像を取得
                const img = button.querySelector("img");
                const currentSrc = img.getAttribute("src");

                // 画像を切り替え
                if (currentSrc.includes("favorite_active")) {
                    img.setAttribute("src", "{{ asset('images/favorite_inactive.png') }}");
                } else {
                    img.setAttribute("src", "{{ asset('images/favorite_active.png') }}");
                }
            }
        });
    });
});
</script>
@endsection