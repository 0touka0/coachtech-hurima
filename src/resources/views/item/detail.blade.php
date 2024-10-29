@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/item/detail.css') }}">
@endsection

@section('main')
    <div class="item-detail-page">
        <div class="item__image-area">
            <!-- 商品画像表示 -->
            <div class="item__image-wrapper">
                <img class="item__image" src="{{ asset($item->image_path) }}" alt="{{ $item->name }}">
            </div>
        </div>
        <div class="item__detail-area">
            <!-- 商品の詳細表示 -->
            <div class="item__title">
                <h1 class="item__name">{{ $item->name }}</h1>
                <p class="item__price">¥<span class="item__price-span">{{ number_format($item->price) }}</span> (税込)</p>
                <div class="item-actions">
                    <!-- お気に入りリスト -->
                    <div class="item-mylist" id="mylist" data-id="{{ $item->id }}">
                        <i class="fa-star {{ $isFavorited ? 'fa-solid' : 'fa-regular' }}" id="mylist-icon"></i>
                        <span class="item-mylist__count" id="mylist-count"></span>
                    </div>
                    <!-- コメントアイコン -->
                    <div class="item-comment">
                        <img class="item-comment__icon" src="{{ asset('images/comment-icon.png') }}" alt="コメントアイコン">
                        <span class="item-comments__count" data-item-id="{{ $item->id }}"></span>
                    </div>
                </div>
            </div>

            <!-- 購入リンク -->
            <div class="item-purchase">
                @if ($isSold)
                    <p class="item-purchase__link--sold btn">購入済み</p>
                @else
                    <a href="{{ route('purchase.show', ['item_id' => $item->id]) }}" class="item-purchase__link btn">購入手続きへ</a>
                    @if (session('error'))
                        <p class="error-message">{{ session('error') }}</p>
                    @endif
                @endif
            </div>

            <!-- 商品の説明 -->
            <div class="item-description">
                <h2 class="item-description__header">商品説明</h2>
                <p class="item-description__condition">{{ $item->description }}</p>
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
				@foreach ($item->Itemcomments as $Itemcomment)
					<article class="item-comments__comment">
						<div class="item-comments__user-info">
							<div class="item-comments__user-image-wrapper">
								@if ($Itemcomment->user->image_path)
									<img class="item-comments__user-image" src="{{ $Itemcomment->user->image_path }}" alt="プロフィール画像">
								@else
									<div class="item-comments__default-image"></div>
								@endif
							</div>
							<h3 class="item-comments__user-name">{{ $Itemcomment->user->name }}</h3>
						</div>
						<p class="item-comments__content">{{ $Itemcomment->comment }}</p>
					</article>
				@endforeach
				<div class="item-comments__form">
					<h3 class="item-comments__form-header">商品へのコメント</h3>
					@if ($errors->has('comment'))
						<p class="error-message">{{ $errors->first('comment') }}</p>
					@endif
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
