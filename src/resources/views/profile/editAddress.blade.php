@extends('layouts.app')

@section('title', '住所の変更')

@section('content')

<div class="address-edit__content">

    <div class="address-edit__heading">
        <h1>住所の変更</h1>
    </div>

    <form
        action="{{ route('profile.address.update', $item) }}"
        method="POST"
        novalidate>

        @csrf

        <div class="address-edit__group">

            <label
                class="address-edit__label"
                for="postal_code">
                郵便番号
            </label>

            <input
                class="address-edit__input"
                id="postal_code"
                type="text"
                name="postal_code"
                value="{{ old('postal_code', Auth::user()->postal_code) }}">

            <div class="address-edit__error">
                @error('postal_code')
                {{ $message }}
                @enderror
            </div>

        </div>

        <div class="address-edit__group">

            <label
                class="address-edit__label"
                for="address">
                住所
            </label>

            <input
                class="address-edit__input"
                id="address"
                type="text"
                name="address"
                value="{{ old('address', Auth::user()->address) }}">

            <div class="address-edit__error">
                @error('address')
                {{ $message }}
                @enderror
            </div>

        </div>

        <div class="address-edit__group">

            <label
                class="address-edit__label"
                for="building_name">
                建物名
            </label>

            <input
                class="address-edit__input"
                id="building_name"
                type="text"
                name="building_name"
                value="{{ old('building_name', Auth::user()->building_name) }}">

            <div class="address-edit__error">
                @error('building_name')
                {{ $message }}
                @enderror
            </div>

        </div>

        <button
            class="address-edit__submit"
            type="submit">
            更新する
        </button>

    </form>

</div>

@endsection