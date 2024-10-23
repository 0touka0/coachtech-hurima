@extends('layouts.auth_app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('main')
    <div class="login-page">
        <!-- ログインページのヘッダー -->
        <h1 class="login-page__header">ログイン</h1>

        <!-- ログインフォーム -->
        <div class="login-form">
            <form action="/login" method="POST">
                @csrf

                <!-- メールアドレスの入力フィールド -->
                <div class="login-form__group">
                    <label class="login-form__label" for="email">メールアドレス</label>
                    <input class="login-form__input" id="email" type="email" name="email" autocomplete="username" value="{{ old('email') }}">
                    @if ($errors->has('email'))
                        <p class="error-message">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <!-- パスワードの入力フィールド -->
                <div class="login-form__group">
                    <label class="login-form__label" for="password">パスワード</label>
                    <input class="login-form__input" id="password" type="password" name="password" autocomplete="current-password">
                    @if ($errors->has('password'))
                        <p class="error-message">{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <!-- ログインボタン -->
                <button class="btn login-form__btn" type="submit">ログインする</button>
            </form>
        </div>

        <!-- 会員登録リンク -->
        <nav class="nav">
            <a class="nav__link" href="/register">会員登録はこちら</a>
        </nav>
    </div>
@endsection
