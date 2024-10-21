@extends('layouts/app')

@section('css')
	<link rel="stylesheet" href="{{ asset('css/item/sell_form.css') }}">
@endsection

@section('main')
	<div class="sell-page">
		<h1 class="sell-page__header">商品の出品</h1>
		<form action="{{ route('sell.store') }}" method="post" enctype="multipart/form-data">
			<div class="sell-form">
				@csrf
				<!-- 商品画像のアップロード -->
				<div class="sell-item-image">
					<h2 class="sell-item-image__title">商品画像</h2>
					<div class="sell-item-image__form">
						<img class="image-form__image" src="" alt="商品画像">
						<button class="image-form__btn" type="button">画像を選択する</button>
						<input class="image-form__input" type="file" name="image" accept="image/*">
					</div>
					@if ($errors->has('image'))
						<p class="error-message">{{ $errors->first('image') }}</p>
					@endif
				</div>

				<!-- 商品の詳細情報入力 -->
				<div class="sell-item-detail">
					<h2 class="sell-item-detail__title">商品の詳細</h2>
					<!-- カテゴリー選択 -->
					<div class="sell-item-category">
						<h3 class="sell-item-category__title">カテゴリー</h3>
						<div class="sell-item-category__buttons">
							@foreach ($categories as $category)
								<input type="checkbox" id="category-{{ $category->id }}" name="category[]" value="{{ $category->id }}" class="category-checkbox">
								<label for="category-{{ $category->id }}" class="category-label">{{ $category->category }}</label>
							@endforeach
						</div>
						@if ($errors->has('category'))
							<p class="error-message">{{ $errors->first('category') }}</p>
						@endif
					</div>
					<!-- 商品の状態選択 -->
					<div class="sell-item-status">
						<h3 class="sell-item-status__title">商品の状態</h3>
						<div class="sell-item-status__select">
							<p class="select-box" id="selectBox">
								選択してください
							</p>
							<ul class="select-options" id="selectOptions">
								<li class="select-options__list" data-value="良好">良好</li>
								<li class="select-options__list" data-value="目立った傷や汚れ無し">目立った傷や汚れ無し</li>
								<li class="select-options__list" data-value="やや傷や汚れあり">やや傷や汚れあり</li>
								<li class="select-options__list" data-value="状態が悪い">状態が悪い</li>
							</ul>
							<input type="hidden" name="condition" id="paymentMethod">
						</div>
						@if ($errors->has('condition'))
							<p class="error-message">{{ $errors->first('condition') }}</p>
						@endif
					</div>

					<!-- 商品名、説明、価格入力 -->
					<h2 class="sell-item-detail__title">商品名と説明</h2>
					<div class="sell-item-name">
						<label class="sell-item-name__title" for="item-name">商品名</label>
						<input class="sell-item-name__input" type="text" name="name" id="item-name">
						@if ($errors->has('name'))
							<p class="error-message">{{ $errors->first('name') }}</p>
						@endif
					</div>
					<div class="sell-item-description">
						<label class="sell-item-description__title" for="item-description">商品の説明</label>
						<textarea class="sell-item-description__textarea" name="description" id="item-description" cols="30" rows="10"></textarea>
						@if ($errors->has('description'))
							<p class="error-message">{{ $errors->first('description') }}</p>
						@endif
					</div>
					<div class="sell-item-price">
						<label class="sell-item-price__title" for="item-price">販売価格</label>
						<div class="price-input-wrapper">
							<input class="sell-item-price__input" type="number" name="price" id="item-price" min="0">
						</div>
						@if ($errors->has('price'))
							<p class="error-message">{{ $errors->first('price') }}</p>
						@endif
					</div>
				</div>

				<!-- 出品ボタン -->
				<button class="sell-form__btn btn" type="submit">出品する</button>
			</div>
		</form>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('js/item_image.js') }}"></script>
	<script src="{{ asset('js/selectbox.js') }}"></script>
@endsection