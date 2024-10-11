@extends('layouts/app')

@section('css')
	<link rel="stylesheet" href="{{ asset('css/item/sell_form.css') }}">
@endsection

@section('main')
	<div class="sell-page">
		<h1 class="sell-page__header">商品の出品</h1>
		<div class="sell-form">
			<form action="{{ route('sell.store') }}" method="post" enctype="multipart/form-data">
				@csrf
				<!-- 商品画像のアップロード -->
				<div class="sell-item-image">
					<h2 class="sell-item-image__title">商品画像</h2>
					<div class="sell-item-image__form">
						<img class="image-form__image" src="" alt="商品画像" style="display:none;">
						<button class="image-form__btn" type="button">画像を選択する</button>
						<input class="image-form__input" type="file" name="image" accept="image/*" style="display:none;">
					</div>
					@if ($errors->has('image'))
						<p class="error-message">{{ $errors->first('image') }}</p>
					@endif
				</div>

				@if ($errors->any())
					<div class="error-message">
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif
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
					</div>
					<!-- 商品の状態選択 -->
					<div class="sell-item-status">
						<h3 class="sell-item-status__title">商品の状態</h3>
						<select class="sell-item-status__dropdown" name="condition" id="">
							<option value="">選択してください</option>
							<option value="良好">良好</option>
							<option value="目立った傷や汚れ無し">目立った傷や汚れ無し</option>
							<option value="やや傷や汚れあり">やや傷や汚れあり</option>
							<option value="状態が悪い">状態が悪い</option>
						</select>
					</div>

					<!-- 商品名、説明、価格入力 -->
					<h2 class="sell-item-detail__title">商品名と説明</h2>
					<div class="sell-item-name">
						<label class="sell-item-name__title" for="item-name">商品名</label>
						<input type="text" name="name" id="item-name">
					</div>
					<div class="sell-item-description">
						<label for="item-description">商品の説明</label>
						<textarea name="description" id="item-description" cols="30" rows="10"></textarea>
					</div>
					<div class="sell-item-price">
						<label for="item-price">販売価格</label>
						<input type="number" name="price" id="item-price" min="0">
					</div>
				</div>

				<!-- 出品ボタン -->
				<button class="sell-form__btn btn" type="submit">出品する</button>
			</form>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('js/image.js') }}"></script>
@endsection