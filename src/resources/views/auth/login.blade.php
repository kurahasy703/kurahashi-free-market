@extends('layouts.app')

@section('title', 'ログイン')

@section('content')
<div class="login-form__content">
    <div class="login-form__heading">
        <h2>ログイン</h2>
    </div>

    <form class="form" action="/login" method="post" novalidate>
        @csrf

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

        <div class="form__button">
            <button class="form__button-submit" type="submit">
                ログインする
            </button>
        </div>
    </form>

    <div class="register__link">
        <a href="/register">会員登録はこちら</a>
    </div>
</div>
@endsection