@extends('layouts.app')

@section('title', 'プロフィール設定')

@section('content')

<div class="profile-edit">

    <div class="profile-edit__content">

        <div class="profile-edit__heading">
            <h1>プロフィール設定</h1>
        </div>

        <form
            class="profile-edit__form"
            action="{{ route('profile.update') }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate>

            @csrf
            @method('PATCH')

            <div class="profile-edit__image">

                <div class="profile-edit__image-preview">

                    <img
                        id="preview"
                        src="{{ $user->profile_image
                            ? asset('storage/' . $user->profile_image)
                            : asset('img/default-user.png') }}"
                        alt="">

                </div>

                <label
                    class="profile-edit__image-button"
                    for="profile_image">
                    画像を選択する
                </label>

                <input
                    class="profile-edit__file-input"
                    type="file"
                    id="profile_image"
                    name="profile_image"
                    accept=".jpg,.jpeg,.png">

            </div>

            <div class="profile-edit__error">
                @error('profile_image')
                {{ $message }}
                @enderror
            </div>

            <div class="profile-edit__group">

                <label
                    class="profile-edit__label"
                    for="name">
                    ユーザー名
                </label>

                <input
                    class="profile-edit__input"
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $user->name) }}">

                <div class="profile-edit__error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>

            </div>

            <div class="profile-edit__group">

                <label
                    class="profile-edit__label"
                    for="postal_code">
                    郵便番号
                </label>

                <input
                    class="profile-edit__input"
                    type="text"
                    id="postal_code"
                    name="postal_code"
                    value="{{ old('postal_code', $user->postal_code) }}">

                <div class="profile-edit__error">
                    @error('postal_code')
                    {{ $message }}
                    @enderror
                </div>

            </div>

            <div class="profile-edit__group">

                <label
                    class="profile-edit__label"
                    for="address">
                    住所
                </label>

                <input
                    class="profile-edit__input"
                    type="text"
                    id="address"
                    name="address"
                    value="{{ old('address', $user->address) }}">

                <div class="profile-edit__error">
                    @error('address')
                    {{ $message }}
                    @enderror
                </div>

            </div>

            <div class="profile-edit__group">

                <label
                    class="profile-edit__label"
                    for="building_name">
                    建物名
                </label>

                <input
                    class="profile-edit__input"
                    type="text"
                    id="building_name"
                    name="building_name"
                    value="{{ old(
                        'building_name',
                        $user->building_name
                    ) }}">

                <div class="profile-edit__error">
                    @error('building_name')
                    {{ $message }}
                    @enderror
                </div>

            </div>

            <button
                class="profile-edit__submit"
                type="submit">
                更新する
            </button>

        </form>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput =
            document.getElementById('profile_image');

        const imagePreview =
            document.getElementById('preview');

        if (!imageInput || !imagePreview) {
            return;
        }

        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (!file) {
                return;
            }

            const reader = new FileReader();

            reader.addEventListener('load', function(event) {
                imagePreview.src = event.target.result;
            });

            reader.readAsDataURL(file);
        });
    });
</script>

@endsection