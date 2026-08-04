@extends('layouts.app')

@section('title', '商品出品')

@section('content')

<div class="exhibition-form__content">

    <div class="exhibition-form__heading">
        <h1>商品の出品</h1>
    </div>

    <form
        class="exhibition-form"
        action="{{ route('item.store') }}"
        method="POST"
        enctype="multipart/form-data"
        novalidate>

        @csrf

        <div class="exhibition-form__group">
            <label
                class="exhibition-form__label"
                for="image_url">
                商品画像
            </label>

            <div class="exhibition-form__image-upload">

                <img
                    id="image-preview"
                    class="exhibition-form__image-preview"
                    src=""
                    alt="選択した商品画像"
                    style="display: none;">

                <label
                    class="exhibition-form__image-button"
                    for="image_url">
                    画像を選択する
                </label>

                <input
                    class="exhibition-form__file-input"
                    id="image_url"
                    type="file"
                    name="image_url"
                    accept=".jpg,.jpeg,.png">
            </div>

            <div class="exhibition-form__error">
                @error('image_url')
                {{ $message }}
                @enderror
            </div>
        </div>

        <section class="exhibition-form__section">

            <h2 class="exhibition-form__section-title">
                商品の詳細
            </h2>

            <div class="exhibition-form__group">
                <p class="exhibition-form__label">
                    カテゴリー
                </p>

                <div class="exhibition-form__categories">
                    @foreach ($categories as $category)
                    <label class="exhibition-form__category">
                        <input
                            type="checkbox"
                            name="categories[]"
                            value="{{ $category->id }}"
                            {{ in_array(
                                $category->id,
                                old('categories', [])
                            ) ? 'checked' : '' }}>

                        <span>
                            {{ $category->content }}
                        </span>
                    </label>
                    @endforeach
                </div>

                <div class="exhibition-form__error">
                    @error('categories')
                    {{ $message }}
                    @enderror

                    @error('categories.*')
                    {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="exhibition-form__group">
                <label
                    class="exhibition-form__label"
                    for="condition_id">
                    商品の状態
                </label>

                <select
                    class="exhibition-form__select"
                    id="condition_id"
                    name="condition_id">

                    <option
                        value=""
                        disabled
                        hidden
                        {{ old('condition_id') ? '' : 'selected' }}>
                        選択してください
                    </option>

                    @foreach ($conditions as $condition)
                    <option
                        value="{{ $condition->id }}"
                        {{ old('condition_id') == $condition->id
                            ? 'selected'
                            : '' }}>
                        {{ $condition->content }}
                    </option>
                    @endforeach

                </select>

                <div class="exhibition-form__error">
                    @error('condition_id')
                    {{ $message }}
                    @enderror
                </div>
            </div>

        </section>

        <section class="exhibition-form__section">

            <h2 class="exhibition-form__section-title">
                商品名と説明
            </h2>

            <div class="exhibition-form__group">
                <label
                    class="exhibition-form__label"
                    for="name">
                    商品名
                </label>

                <input
                    class="exhibition-form__input"
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}">

                <div class="exhibition-form__error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="exhibition-form__group">
                <label
                    class="exhibition-form__label"
                    for="brand_name">
                    ブランド名
                </label>

                <input
                    class="exhibition-form__input"
                    id="brand_name"
                    type="text"
                    name="brand_name"
                    value="{{ old('brand_name') }}">

                <div class="exhibition-form__error">
                    @error('brand_name')
                    {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="exhibition-form__group">
                <label
                    class="exhibition-form__label"
                    for="description">
                    商品の説明
                </label>

                <textarea
                    class="exhibition-form__textarea"
                    id="description"
                    name="description"
                    rows="6">{{ old('description') }}</textarea>

                <div class="exhibition-form__error">
                    @error('description')
                    {{ $message }}
                    @enderror
                </div>
            </div>

        </section>

        <div class="exhibition-form__group">
            <label
                class="exhibition-form__label"
                for="price">
                販売価格
            </label>

            <div class="exhibition-form__price">
                <span>¥</span>

                <input
                    id="price"
                    type="number"
                    name="price"
                    min="0"
                    value="{{ old('price') }}">
            </div>

            <div class="exhibition-form__error">
                @error('price')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="exhibition-form__button">
            <button type="submit">
                出品する
            </button>
        </div>

    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput =
            document.getElementById('image_url');

        const imagePreview =
            document.getElementById('image-preview');

        const imageButton =
            document.querySelector(
                '.exhibition-form__image-button'
            );

        if (!imageInput || !imagePreview || !imageButton) {
            return;
        }

        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (!file) {
                imagePreview.src = '';
                imagePreview.style.display = 'none';
                imageButton.style.display = 'block';
                return;
            }

            const reader = new FileReader();

            reader.addEventListener('load', function(event) {
                imagePreview.src = event.target.result;
                imagePreview.style.display = 'block';
                imageButton.style.display = 'none';
            });

            reader.readAsDataURL(file);
        });
    });
</script>

@endsection