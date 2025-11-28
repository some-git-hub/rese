<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    @yield('css')
</head>

<body>
    <header class="header">
        <!-- メニューボタン -->
        <button class="header__button-open-menu" id="menu-button-open">
            <img class="header__image-menu" src="{{ asset('images/menu.png') }}" alt="メニュー">
        </button>

        <!-- メニューを閉じるボタン -->
        <button class="header__button-close-menu hidden" id="menu-button-close">
            <img class="header__image-close" src="{{ asset('images/close.png') }}" alt="閉じる">
        </button>

        <h1 class="header__app-title" id="header-title">Rese</h1>
    </header>

    <div class="menu__wrapper hidden" id="menu-drawer">
        @auth
        <a href="{{ route('owner.shop.info') }}" class="nav-button">
            @isset($shop)
                Shop-Edit
            @else
                Shop-Create
            @endif
        </a>
        <a href="{{ route('owner.reservation.list') }}" class="nav-button">Reservation-List</a>
        <form method="post" action="{{ route('logout') }}">
            @csrf
            <button class="nav-button" type="submit">Logout</button>
        </form>
        @else
        <a href="{{ route('shop.list') }}" class="nav-button">Home</a>
        <a href="{{ route('register') }}" class="nav-button">Register</a>
        <a href="{{ route('login') }}" class="nav-button">Login</a>
        @endauth
    </div>

    <script defer>
        document.addEventListener("DOMContentLoaded", () => {
            const menuDrawer = document.getElementById("menu-drawer");
            const openButton  = document.getElementById("menu-button-open");
            const closeButton = document.getElementById("menu-button-close");
            const headerTitle = document.getElementById("header-title");
            const body = document.body;

            const openMenu = (e) => {
                e.stopPropagation();
                menuDrawer.classList.remove("hidden");
                openButton.classList.add("hidden");
                closeButton.classList.remove("hidden");
                headerTitle.classList.add("hidden");
                body.classList.add("menu-open");
            };

            const closeMenu = () => {
                menuDrawer.classList.add("hidden");
                closeButton.classList.add("hidden");
                openButton.classList.remove("hidden");
                headerTitle.classList.remove("hidden");
                body.classList.remove("menu-open");
            };

            // 開閉処理
            openButton.addEventListener("click", openMenu);
            closeButton.addEventListener("click", (e) => {
                e.stopPropagation();
                closeMenu();
            });

            // メニュー外クリック
            document.addEventListener("click", () => {
                if (!menuDrawer.classList.contains("hidden")) {
                    closeMenu();
                }
            });

            // メニュー内クリックは閉じない
            menuDrawer.addEventListener("click", (e) => e.stopPropagation());
        });
    </script>

    <main>
        @yield('content')
    </main>

    @yield('js')
</body>
</html>