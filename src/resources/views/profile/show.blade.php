@extends('layouts.app')

@section('title', 'マイページ')

@section('content')

<div class="mypage">

    <section class="mypage__profile">

        <div class="mypage__profile-image">
            @if ($user->profile_image)
            <img
                src="{{ asset('storage/' . $user->profile_image) }}"
                alt="{{ $user->name }}">
            @endif
        </div>

        <h1 class="mypage__user-name">
            {{ $user->name }}
        </h1>

        <a
            class="mypage__profile-link"
            href="{{ route('profile.edit') }}">
            プロフィールを編集
        </a>

    </section>

    <nav class="mypage__tabs">

        <a
            class="mypage__tab {{ request('page', 'sell') === 'sell' ? 'active' : '' }}"
            href="{{ route('profile.show', ['page' => 'sell']) }}">
            出品した商品
        </a>

        <a
            class="mypage__tab {{ request('page') === 'buy' ? 'active' : '' }}"
            href="{{ route('profile.show', ['page' => 'buy']) }}">
            購入した商品
        </a>

    </nav>

    <div class="mypage__item-list">

        @forelse ($items as $item)

        <article class="mypage__item">

            <a
                class="mypage__item-link"
                href="{{ route('item.show', $item) }}">

                <div class="mypage__item-image-wrapper">

                    <img
                        class="mypage__item-image"
                        src="{{ asset('storage/' . $item->image_url) }}"
                        alt="{{ $item->name }}">

                    @if ($item->order)
                    <span class="mypage__item-sold">
                        Sold
                    </span>
                    @endif

                </div>

                <p class="mypage__item-name">
                    {{ $item->name }}
                </p>

            </a>

        </article>

        @empty

        <p class="mypage__empty">
            表示する商品がありません。
        </p>

        @endforelse

    </div>

</div>

@endsection