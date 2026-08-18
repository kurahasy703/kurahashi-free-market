@extends('layouts.app')

@section('title', '会員登録')

@section('content')
<div class="register-form__content">
    <div class="register-form__heading">
        <h1>会員登録</h1>
    </div>

    <form class="form" action="/register" method="post" novalidate>
        @csrf

        <div class="form__group">
            <div class="form__group-title">
                <label class="form__label--item" for="name">
                    ユーザー名
                </label>
            </div>

            <div class="form__group-content">
                <div class="form__input--text">
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}">
                </div>

                <div class="form__error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="form__group">
            <div class="form__group-title">
                <label class="form__label--item" for="email">
                    メールアドレス
                </label>
            </div>

            <div class="form__group-content">
                <div class="form__input--text">
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}">
                </div>

                <div class="form__error">
                    @error('email')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="form__group">
            <div class="form__group-title">
                <label class="form__label--item" for="password">
                    パスワード
                </label>
            </div>

            <div class="form__group-content">
                <div class="form__input--text">
                    <input
                        id="password"
                        type="password"
                        name="password">
                </div>

                <div class="form__error">
                    @error('password')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="form__group">
            <div class="form__group-title">
                <label class="form__label--item" for="password_confirmation">
                    確認用パスワード
                </label>
            </div>

            <div class="form__group-content">
                <div class="form__input--text">
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation">
                </div>

                <div class="form__error">
                    @error('password_confirmation')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="form__button">
            <button class="form__button-submit" type="submit">
                登録する
            </button>
        </div>
    </form>

    <div class="login__link">
        <a href="{{ route('login') }}">
            ログインはこちら
        </a>
    </div>
</div>
@endsection