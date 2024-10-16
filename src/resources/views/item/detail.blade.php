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
				<img class="item__image" src="{{ $item->image_path }}" alt="{{ $item->name }}">
			</div>
		</div>
		<div class="item__detail-area">
			<!-- 商品の詳細表示 -->
			<div class="item__title">
				<h1 class="item__name">{{ $item->name }}</h1>
				<p class="item__price">¥<span class="item__price-span">4{{ number_format($item->price) }}</span>(税込)</p>
				<div class="item-actions">
					<div class="item-mylist" id="mylist" data-id="{{ $item->id }}">
						<img class="item-mylist__icon" src="{{ asset('images/star-icon.png') }}" id="mylist-icon" alt="マイリストアイコン">
						<span class="item-mylist__count" id="mylist-count"></span>
					</div>
					<div class="item-comment">
						<img class="item-comment__icon" src="{{ asset('images/comment-icon.png') }}" alt="コメントアイコン">
						<span class="item-comments__count" data-item-id="{{ $item->id }}"></span>
					</div>
				</div>
			</div>

			<!-- 購入リンク -->
			<div class="item-Purchase">
				<a href="{{ route('purchase.show', ['item_id' => $item->id]) }}" class="item-Purchase__link btn">購入手続きへ</a>
			</div>

			<!-- 商品の説明 -->
			<div class="item-description">
				<h2 class="item-description__header">商品説明</h2>
				<p class="item-description__condition">{{ $item->description }}。</p>
			</div>

			<!-- 商品の詳細情報 -->
			<div class="item-info">
				<h2 class="item-info__header">商品の情報</h2>
				<div class="item-info__category">
					<h3 class="item-info__category-title">カテゴリー</h3>
					<ul class="item-info__category-lists">
						@foreach ($item->categories as $category)
							<li class="item-info__category-list">{{ $category->category }}</li>
						@endforeach
					</ul>
				</div>
				<div class="item-info__condition">
					<h3 class="item-info__condition-title">商品の状態</h3>
					<p class="item-info__condition-description">{{ $item->condition }}</p>
				</div>
			</div>

			<!-- コメントセクション -->
			<div class="item-comments">
				<h2 class="item-comments__header">コメント (<span class="item-comments__count" data-item-id="{{ $item->id }}"></span>)</h2>
				@foreach ($comments as $comment)
					<article class="item-comments__comment">
						<div class="item-comments__user-info">
							<div class="item-comments__user-image-wrapper">
								<img class="item-comments__user-image" src="{{ $comment->user->image_path }}" alt="プロフィール画像">
							</div>
							<h3 class="item-comments__user-name">{{ $comment->user->name }}</h3>
						</div>
						<p class="item-comments__content">{{ $comment->comment }}</p>
					</article>
				@endforeach
				<div class="item-comments__form">
					<h3 class="item-comments__form-header">商品へのコメント</h3>
					<form action="{{ route('comment.send', ['item_id' => $item->id]) }}" method="post">
						@csrf
						@if (auth()->check())
							<input type="hidden" name="id" value="{{ $user->id }}">
						@endif
						<textarea class="item-comments__form-textarea" name="comment" cols="30" rows="10"></textarea>
						<button class="item-comments__form-btn btn" type="submit">コメントを送信する</button>
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