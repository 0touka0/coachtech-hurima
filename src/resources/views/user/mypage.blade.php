@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/user/mypage.css') }}">
@endsection

@section('main')
    <div class="mypage">
        <!-- ユーザー情報 -->
        <div class="mypage-info">
            <div class="mypage-info__image-wrapper">
                @if ($user->image_path)
                    <img class="mypage-info__image" src="{{ $user->image_path }}" alt="プロフィール画像">
                @else
                    <div class="mypage-info__image--default"></div>
                @endif
            </div>
            <h1 class="mypage-info__name">{{ $user->name }}</h1>
            <a href="{{ route('profile.edit') }}" class="mypage-info__link">プロフィール編集</a>
        </div>

        <!-- タブメニュー -->
        <div class="tabs-container">
            <ul class="tabs">
                <li class="tabs-list">
                    <a href="/mypage?tab=sell" class="tab-link {{ $tab === 'sell' ? 'is-active' : '' }}">出品した商品</a>
                </li>
                <li class="tabs-list">
                    <a href="/mypage?tab=buy" class="tab-link {{ $tab === 'buy' ? 'is-active' : '' }}">購入した商品</a>
                </li>
            </ul>
        </div>

        <div class="mypage-content">
            <!-- 商品リスト -->
            <div class="item-list">
                @foreach ($items as $item)
                    <div class="item-card">
                        <a href="{{ route('item.show', ['item_id' => $item->id]) }}" class="item-card__link">
                            <div class="item-card__image-wrapper">
                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="item-card__image">
                            </div>
                            <span class="item-card__title">{{ $item->name }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
