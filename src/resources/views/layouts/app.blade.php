<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>

<body>
    <header class="header">
        <div class="header__inner">

            <a href="/" class="header__logo">
                <img
                    src="{{ asset('img/logo.png') }}"
                    alt="COACHTECH">
            </a>

            @if (!Request::is('login') && !Request::is('register'))

            <form
                class="header__search"
                action="/"
                method="GET">

                @if(request('tab'))
                <input
                    type="hidden"
                    name="tab"
                    value="{{ request('tab') }}">
                @endif

                <input
                    type="text"
                    name="keyword"
                    value="{{ request('keyword') }}"
                    placeholder="なにをお探しですか？">

            </form>

            <nav class="header__nav">
                <ul class="nav__list">

                    @auth
                    <li>
                        <form
                            action="/logout"
                            method="POST">
                            @csrf

                            <button
                                class="header__logout"
                                type="submit">
                                ログアウト
                            </button>
                        </form>
                    </li>
                    @else
                    <li>
                        <a
                            class="nav__item-link"
                            href="/login">
                            ログイン
                        </a>
                    </li>
                    @endauth

                    <li>
                        <a
                            class="nav__item-link"
                            href="/mypage">
                            マイページ
                        </a>
                    </li>

                    <li>
                        <a
                            class="nav__btn-sell"
                            href="/sell">
                            出品
                        </a>
                    </li>

                </ul>
            </nav>

            @endif

        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>