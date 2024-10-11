<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>coachtechフリマ</title>
	<link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
	<link rel="stylesheet" href="{{ asset('css/layouts/common.css') }}">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
	@yield('css')
</head>
<body>
	<header class="header">
		<div class="header-logo">
			<a href="/">
				<img class="header-logo__image" src="{{ asset('images/logo.svg') }}" alt="coachtech">
			</a>
		</div>
	</header>

	<main class="main">
		@yield('main')
	</main>

	@yield('scripts')
</body>
</html>