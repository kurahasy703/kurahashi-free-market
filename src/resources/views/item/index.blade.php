@extends('layouts.app')

@section('title', '商品一覧')

@section('content')
<div class="item-list">

    <div class="item-list__tabs">
        <a
            class="item-list__tab {{ request('tab') !== 'mylist' ? 'active' : '' }}"
            href="{{ route('item.index', [
                'keyword' => request('keyword'),
            ]) }}">
            おすすめ
        </a>

        <a
            class="item-list__tab {{ request('tab') === 'mylist' ? 'active' : '' }}"
            href="{{ route('item.index', [
                'tab' => 'mylist',
                'keyword' => request('keyword'),
            ]) }}">
            マイリスト
        </a>
    </div>

    <div class="item-list__content">
        @foreach($items as $item)
        <div class="item">

            <a
                class="item__link"
                href="{{ route('item.show', $item) }}">

                <div class="item__image-wrapper">
                    <img
                        class="item__image"
                        src="{{ asset('storage/' . $item->image_url) }}"
                        alt="{{ $item->name }}">

                    @if($item->order)
                    <p class="sold">Sold</p>
                    @endif
                </div>

                <p class="item__name">
                    {{ $item->name }}
                </p>

            </a>

        </div>
        @endforeach
    </div>

</div>
@endsection