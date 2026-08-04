@extends('layouts.app')

@section('title', '商品詳細')

@section('content')

<div class="item-detail">

    <div class="item-detail__image-area">
        <div class="item-detail__image-wrapper">
            <img
                class="item-detail__image"
                src="{{ asset('storage/' . $item->image_url) }}"
                alt="{{ $item->name }}">

            @if ($item->order)
            <span class="item-detail__sold">
                Sold
            </span>
            @endif
        </div>
    </div>

    <div class="item-detail__content">

        <section class="item-detail__summary">

            <h1 class="item-detail__name">
                {{ $item->name }}
            </h1>

            @if ($item->brand_name)
            <p class="item-detail__brand">
                {{ $item->brand_name }}
            </p>
            @endif

            <p class="item-detail__price">
                ¥{{ number_format($item->price) }}

                <span class="item-detail__tax">
                    （税込）
                </span>
            </p>

            <div class="item-detail__actions">

                <div class="item-detail__action-item">

                    @auth

                    @if (
                    $item->favorites
                    ->where('user_id', auth()->id())
                    ->isNotEmpty()
                    )

                    <form
                        class="item-detail__favorite-form"
                        action="{{ route('favorite.destroy', $item) }}"
                        method="POST">
                        @csrf
                        @method('DELETE')

                        <button
                            class="item-detail__favorite-button"
                            type="submit"
                            aria-label="お気に入りから削除">

                            <img
                                src="{{ asset('img/heart-pink.png') }}"
                                alt="お気に入り済み">

                        </button>
                    </form>

                    @else

                    <form
                        class="item-detail__favorite-form"
                        action="{{ route('favorite.store', $item) }}"
                        method="POST">
                        @csrf

                        <button
                            class="item-detail__favorite-button"
                            type="submit"
                            aria-label="お気に入りに追加">

                            <img
                                src="{{ asset('img/heart-default.png') }}"
                                alt="お気に入り">

                        </button>
                    </form>

                    @endif

                    @else

                    <img
                        class="item-detail__favorite-icon"
                        src="{{ asset('img/heart-default.png') }}"
                        alt="お気に入り">

                    @endauth

                    <span class="item-detail__action-count">
                        {{ $item->favorites->count() }}
                    </span>

                </div>

                <div class="item-detail__action-item">

                    <img
                        class="item-detail__comment-icon"
                        src="{{ asset('img/comment.png') }}"
                        alt="コメント">

                    <span class="item-detail__action-count">
                        {{ $item->comments->count() }}
                    </span>

                </div>

            </div>

            @if ($item->order)

            <div
                class="item-detail__purchase-button
                    item-detail__purchase-button--disabled">
                売り切れました
            </div>

            @elseif (
            auth()->check()
            && $item->user_id === auth()->id()
            )

            <div
                class="item-detail__purchase-button
                    item-detail__purchase-button--disabled">
                自分の商品です
            </div>

            @else

            <a
                class="item-detail__purchase-button"
                href="{{ route('order.create', $item) }}">
                購入手続きへ
            </a>

            @endif

        </section>

        <section class="item-detail__section">

            <h2 class="item-detail__section-title">
                商品説明
            </h2>

            <p class="item-detail__description">
                {!! nl2br(e($item->description)) !!}
            </p>

        </section>

        <section class="item-detail__section">

            <h2 class="item-detail__section-title">
                商品の情報
            </h2>

            <div class="item-detail__info">

                <div class="item-detail__info-row">

                    <p class="item-detail__info-label">
                        カテゴリー
                    </p>

                    <div class="item-detail__categories">

                        @foreach ($item->categories as $category)
                        <span class="item-detail__category">
                            {{ $category->content }}
                        </span>
                        @endforeach

                    </div>

                </div>

                <div class="item-detail__info-row">

                    <p class="item-detail__info-label">
                        商品の状態
                    </p>

                    <p class="item-detail__condition">
                        {{ $item->condition->content }}
                    </p>

                </div>

            </div>

        </section>

        <section class="item-detail__comments">

            <h2 class="item-detail__comments-title">
                コメント（{{ $item->comments->count() }}）
            </h2>

            <div class="item-detail__comment-list">

                @forelse ($item->comments as $comment)

                <article class="item-detail__comment">

                    <div class="item-detail__comment-user">

                        <div class="item-detail__comment-avatar">

                            @if ($comment->user->profile_image)
                            <img
                                src="{{ asset(
                                    'storage/'
                                    . $comment->user->profile_image
                                ) }}"
                                alt="{{ $comment->user->name }}">
                            @endif

                        </div>

                        <strong class="item-detail__comment-name">
                            {{ $comment->user->name }}
                        </strong>

                    </div>

                    <div class="item-detail__comment-box">
                        <p class="item-detail__comment-content">
                            {{ $comment->content }}
                        </p>
                    </div>

                </article>

                @empty
                {{-- コメントがない場合は何も表示しない --}}
                @endforelse

            </div>

            @auth

            <form
                class="item-detail__comment-form"
                action="{{ route('comment.store', $item) }}"
                method="POST"
                novalidate>
                @csrf

                <label
                    class="item-detail__comment-label"
                    for="content">
                    商品へのコメント
                </label>

                <textarea
                    class="item-detail__comment-textarea"
                    id="content"
                    name="content"
                    rows="5">{{ old('content') }}</textarea>

                <div class="item-detail__comment-error">
                    @error('content')
                    {{ $message }}
                    @enderror
                </div>

                <button
                    class="item-detail__comment-submit"
                    type="submit">
                    コメントを送信する
                </button>

            </form>

            @else

            <a
                class="item-detail__comment-submit"
                href="{{ route('login') }}">
                ログインしてコメントする
            </a>

            @endauth

        </section>

    </div>

</div>

@endsection