@extends('layouts/app')

@section('css')
	<link rel="stylesheet" href="{{ asset('css/user/profile_edit.css') }}">
@endsection

@section('main')
	<div class="profile-edit-page">
		<h2 class="profile-edit-page__header">プロフィール設定</h2>
		<div class="profile-form">
			<form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
				@csrf
				@method('PUT')
				<input type="hidden" name="id" value="{{ $user->id }}">
				<!-- プロフィール画像のアップロード -->
				<div class="image-form__group">
					<div class="image-form__image-wrapper">
						@if ($user->image_path)
							<img class="image-form__image" src="{{ $user->image_path }}" alt="プロフィール画像">
						@else
							<div class="image-form__default-image"></div>
						@endif
					</div>
					<button class="image-form__btn" type="button">画像を選択する</button>
					<input class="image-form__input" type="file" name="image" accept="image/*" style="display:none;">
					@if ($errors->has('image'))
						<p class="error-message">{{ $errors->first('image') }}</p>
					@endif
				</div>

				<!-- ユーザー名の入力 -->
				<div class="profile-form__group">
					<label class="profile-form__label">ユーザー名</label>
					<input class="profile-form__input" type="text" name="name" value="{{ old('name', $user->name) }}">
					@if ($errors->has('name'))
						<p class="error-message">{{ $errors->first('name') }}</p>
					@endif
				</div>

				<!-- 郵便番号の入力 -->
				<div class="profile-form__group">
					<label class="profile-form__label">郵便番号</label>
					<input class="profile-form__input" type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
					@if ($errors->has('postal_code'))
						<p class="error-message">{{ $errors->first('postal_code') }}</p>
					@endif
				</div>

				<!-- 住所の入力 -->
				<div class="profile-form__group">
					<label class="profile-form__label">住所</label>
					<input class="profile-form__input" type="text" name="address" value="{{ old('address', $user->address) }}">
					@if ($errors->has('address'))
						<p class="error-message">{{ $errors->first('address') }}</p>
					@endif
				</div>

				<!-- 建物名の入力 -->
				<div class="profile-form__group">
					<label class="profile-form__label">建物名</label>
					<input class="profile-form__input" type="text" name="building_name" value="{{ old('building_name', $user->building_name) }}">
					@if ($errors->has('building_name'))
						<p class="error-message">{{ $errors->first('building_name') }}</p>
					@endif
				</div>

				<!-- 更新ボタン -->
				<button class="profile-form__btn btn" type="submit">更新する</button>
			</form>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('js/image.js') }}"></script>
@endsection