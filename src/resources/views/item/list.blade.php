@extends('layouts/app')

@section('css')
	<link rel="stylesheet" href="{{ asset('css/item/list.css') }}">
@endsection

@section('main')
	<div class="item-list-page">
		<!-- タブメニュー -->
		<div class="tabs-container">
			<ul class="tabs">
				<li class="tabs-list">
					<a href="/?tab=recommend" class="tab-link {{ $tab === 'recommend' ? 'is-active' : '' }}">おすすめ</a>
				</li>
				@if (auth()->check())
					<li class="tabs-list">
						<a href="/?tab=mylist" class="tab-link {{ $tab === 'mylist' ? 'is-active' : '' }}">マイリスト</a>
					</li>
				@endif
			</ul>
		</div>
		<!-- 商品リスト -->
		<div class="item-content">
			<div class="item-list">
				@foreach ($items as $item)
					<div class="item-card">
						<a href="{{ route('item.show', ['item_id' => $item->id]) }}" class="item-card__link">
							<div class="item-card__image-wrapper">
								<img src="{{ $item->image_path }}" alt="{{ $item->name }}" class="item-card__image">
								@if (in_array($item->id, $soldItemIds))
									<span class="item-card__image--sold">SOLD</span>
								@endif
							</div>
							<span class="item-card__title">{{ $item->name }}</span>
						</a>
					</div>
				@endforeach
			</div>
		</div>
	</div>
@endsection