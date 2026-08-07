@extends('layouts.app')

@section('title', '商品購入')

@section('content')

<div class="purchase-form__content">

    <form
        id="purchase-form"
        class="purchase-form"
        action="{{ route('order.store', $item) }}"
        method="POST"
        novalidate>

        @csrf

        <div class="purchase-form__main">

            <section class="purchase-form__item">

                <div class="purchase-form__item-image">
                    <img
                        src="{{ asset('storage/' . $item->image_url) }}"
                        alt="{{ $item->name }}">
                </div>

                <div class="purchase-form__item-info">

                    <h1 class="purchase-form__item-name">
                        {{ $item->name }}
                    </h1>

                    <p class="purchase-form__item-price">
                        ¥{{ number_format($item->price) }}
                    </p>

                </div>

            </section>

            <section class="purchase-form__section">

                <label
                    class="purchase-form__section-title"
                    for="payment_method">
                    支払い方法
                </label>

                <select
                    class="purchase-form__select"
                    id="payment_method"
                    name="payment_method">

                    <option value="">
                        選択してください
                    </option>

                    <option
                        value="konbini"
                        {{ old('payment_method') === 'konbini' ? 'selected' : '' }}>
                        コンビニ支払い
                    </option>

                    <option
                        value="card"
                        {{ old('payment_method') === 'card' ? 'selected' : '' }}>
                        カード支払い
                    </option>

                </select>

                @error('payment_method')
                <p class="purchase-form__error">
                    {{ $message }}
                </p>
                @enderror

            </section>

            <section class="purchase-form__section purchase-form__address">

                <div class="purchase-form__section-heading">

                    <h2 class="purchase-form__section-title">
                        配送先
                    </h2>

                    <a
                        class="purchase-form__address-link"
                        href="{{ route('profile.address.edit', ['item' => $item->id]) }}">
                        変更する
                    </a>

                </div>

                <div class="purchase-form__address-text">

                    <p>
                        〒{{ $user->postal_code }}
                    </p>

                    <p>
                        {{ $user->address }}
                    </p>

                    @if($user->building_name)
                    <p>
                        {{ $user->building_name }}
                    </p>
                    @endif

                </div>

            </section>

        </div>

        <aside class="purchase-form__summary">

            <div class="purchase-form__summary-box">

                <div class="purchase-form__summary-row">

                    <span>
                        商品代金
                    </span>

                    <span>
                        ¥{{ number_format($item->price) }}
                    </span>

                </div>

                <div class="purchase-form__summary-row">

                    <span>
                        支払い方法
                    </span>

                    <span id="selected-payment-method">
                        選択してください
                    </span>

                </div>

            </div>

            @if(session('error'))
            <div class="purchase-form__session-error">
                {{ session('error') }}
            </div>
            @endif

            <button
                class="purchase-form__submit"
                type="submit">
                購入する
            </button>

        </aside>

    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentSelect =
            document.getElementById('payment_method');

        const paymentDisplay =
            document.getElementById('selected-payment-method');

        if (!paymentSelect || !paymentDisplay) {
            return;
        }

        const paymentLabels = {
            konbini: 'コンビニ支払い',
            card: 'カード支払い'
        };

        function updatePaymentDisplay() {
            paymentDisplay.textContent =
                paymentLabels[paymentSelect.value] ??
                '選択してください';
        }

        paymentSelect.addEventListener(
            'change',
            updatePaymentDisplay
        );

        updatePaymentDisplay();
    });
</script>

@endsection