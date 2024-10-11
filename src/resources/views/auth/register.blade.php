@extends('layouts/auth_app')

@section('main')
	<div class="register-page">
		<h1 class="register-page__header">会員登録</h1>
		<div class="register-form">
			<form action="/register" method="POST">
				@csrf
				<!-- ユーザー名の入力 -->
				<div class="register-form__group">
					<label class="register-form__label">ユーザー名</label>
					<input class="register-form__input" type="text" name="name" value="{{ old('name') }}">
					@if ($errors->has('name'))
						<p class="error-message">{{ $errors->first('name') }}</p>
					@endif
				</div>

				<!-- メールアドレスの入力 -->
				<div class="register-form__group">
					<label class="register-form__label">メールアドレス</label>
					<input class="register-form__input" type="email" name="email" value="{{ old('email') }}">
					@if ($errors->has('email'))
						<p class="error-message">{{ $errors->first('email') }}</p>
					@endif
				</div>

				<!-- パスワードの入力 -->
				<div class="register-form__group">
					<label class="register-form__label">パスワード</label>
					<input class="register-form__input" type="password" name="password">
					@if ($errors->has('password'))
						<p class="error-message">{{ $errors->first('password') }}</p>
					@endif
				</div>

				<!-- 確認用パスワードの入力 -->
				<div class="register-form__group">
					<label class="register-form__label">確認用パスワード</label>
					<input class="register-form__input" type="password" name="password_confirmation">
				</div>

				<!-- 登録ボタン -->
				<button class="register-form__btn btn" type="submit">登録する</button>
			</form>
		</div>
		<nav class="nav">
			<a class="nav__link" href="/login">ログインはこちら</a>
		</nav>
	</div>
@endsection