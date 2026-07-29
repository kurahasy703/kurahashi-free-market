@extends('layouts.app')

@section('title', 'メール認証')

@section('content')

<div class="verify-email">
    <div class="verify-email__content">

        <p class="verify-email__message">
            登録していただいたメールアドレスに認証メールを送付しました。<br>
            メール認証を完了してください。
        </p>

        <a
            href="http://localhost:8025"
            target="_blank"
            class="verify-email__button">
            認証はこちらから
        </a>

        <form
            class="verify-email__form"
            action="{{ route('verification.send') }}"
            method="POST">
            @csrf

            <button
                type="submit"
                class="verify-email__resend">
                認証メールを再送する
            </button>
        </form>

        @if (session('status') === 'verification-link-sent')
        <p class="verify-email__success">
            認証メールを再送しました。
        </p>
        @endif

    </div>
</div>

@endsection