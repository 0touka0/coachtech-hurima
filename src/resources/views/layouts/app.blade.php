<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>coachtechフリマ</title>
	<link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
	<link rel="stylesheet" href="{{ asset('css/layouts/common.css') }}">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
	@yield('css')
</head>
<body>
	<header class="header">
		<!-- ロゴ -->
		<div class="header-logo">
			<a href="/">
				<img class="header-logo__image" src="{{ asset('images/logo.svg') }}" alt="coachtech">
			</a>
		</div>

		<!-- 検索フォーム -->
		<div class="header__search-form">
			<form action="/search" method="get">
				<input class="search-form__input" type="text" name="keyword" placeholder="なにをお探しですか？" value="{{ old('keyword', session('keyword')) }}">
				<input type="hidden" name="tab" value="{{ request('tab', 'recommend') }}">
			</form>
		</div>

		<!-- ナビゲーション -->
		<nav class="header-nav">
			<ul class="header-nav__list">
				@if (auth()->check())
					<li class="header-nav__item">
						<form class="header-nav__logout-form" action="/logout" method="post">
							@csrf
							<button class="logout-form__btn" type="submit">ログアウト</button>
						</form>
					</li>
					<li class="header-nav__item">
						<a href="/mypage" class="header-nav__mypage-link">マイページ</a>
					</li>
				@else
					<li class="header-nav__item">
						<form class="header-nav__login-form" action="/login" method="get">
							@csrf
							<button class="login-form__btn" type="submit">ログイン</button>
						</form>
					</li>
				@endif
					<li class="header-nav__item">
						<a href="{{ route('sell.show') }}" class="header-nav__sell-link">出品</a>
					</li>
				</ul>
			</nav>
	</header>

	<main class="main">
		@yield('main')
	</main>

	@yield('scripts')
</body>
</html>