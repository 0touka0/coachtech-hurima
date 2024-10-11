@extends('layouts/app')

@section('css')
	<link rel="stylesheet" href="{{ asset('css/item/detail.css') }}">
	<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('main')
	<div class="item-detail-page">
		<div class="item__image-area">
			<!-- 商品画像表示 -->
			<div class="item__image-wrapper">
				<img src="{{ $item->image_path }}" alt="{{ $item->name }}">
			</div>
		</div>
		<div class="item__detail-area">
			<!-- 商品の詳細表示 -->
			<div class="item__title">
				<h1 class="item__name">{{ $item->name }}</h1>
				<p class="item__price">￥{{ number_format($item->price) }}<span class="item__price-span">(税込み)</span></p>
				<div class="item-actions">
					<div class="item__mylist" id="mylist" data-id="{{ $item->id }}">
						<i class="fa-star {{ $isFavorited ? 'fa-solid active' : 'fa-regular' }}" id="mylist-icon"></i>
						<span id="mylist-count"></span>
					</div>
					<div class="item__comment">
						<i class="fa-regular fa-comment"></i>
						<span class="item-comments__count" data-item-id="{{ $item->id }}"></span>
					</div>
				</div>
			</div>

			<!-- 購入リンク -->
			<div class="item-Purchase">
				<a href="{{ route('purchase.show', ['item_id' => $item->id]) }}">購入手続きへ</a>
			</div>

			<!-- 商品説明 -->
			<div class="item-description">
				<h2 class="item-description__header">商品説明</h2>
				<p class="item-description__condition">{{ $item->description }}。</p>
			</div>

			<!-- 商品の詳細情報 -->
			<div class="item-info">
				<h2 class="item-info__header">商品の情報</h2>
				<div class="item-info__category">
					<span class="item-info__category-span">カテゴリー</span>
					<ul class="item-info__category-lists">
						@foreach ($item->categories as $category)
							<li>{{ $category->category }}</li>
						@endforeach
					</ul>
				</div>
				<p class="item-info__condition"><span class="item-info__condition-span">商品の状態</span>{{ $item->condition }}</p>
			</div>

			<!-- コメントセクション -->
			<div class="item-comments">
				<h2 class="item-comments__header">コメント (<span class="item-comments__count" data-item-id="{{ $item->id }}"></span>)</h2>
				@foreach ($comments as $comment)
					<article class="item-comments__comment">
						<div class="item-comments__user-info">
							<img class="item-comments__user-image" src="{{ $comment->user->image_path }}" alt="プロフィール画像">
							<h3 class="item-comments__user-name">{{ $comment->user->name }}</h3>
						</div>
						<p class="item-comments__content">{{ $comment->comment }}</p>
					</article>
				@endforeach
				<div class="item-comments__form">
					<h3>商品へのコメント</h3>
					<form action="{{ route('comment.send', ['item_id' => $item->id]) }}" method="post">
						@csrf
						@if (auth()->check())
							<input type="hidden" name="id" value="{{ $user->id }}">
						@endif
						<textarea name="comment" cols="30" rows="10"></textarea>
						<button type="submit">コメントを送信する</button>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('js/mylist.js') }}"></script>
	<script src="{{ asset('js/comment.js') }}"></script>
@endsection