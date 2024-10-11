@extends('layouts/app')

@section('main')
    <div class="purchase-page">
        <form action="{{ route('purchase.store') }}" method="POST">
            @csrf
            <!-- 商品情報 -->
            <div class="purchase-item">
                <div class="purchase-item__img">
                    <img src="{{ $item->image_path }}" alt="{{ $item->name }}">
                </div>
                <h1 class="purchase-item__name">{{ $item->name }}</h1>
                <p class="purchase-item__price">￥{{ number_format($item->price) }}</p>
            </div>

            <!-- 支払い方法 -->
            <div class="purchase-payment">
                <h2 class="purchase-payment__title">支払い方法</h2>
                <div class="purchase-payment__options">
                    <select class="purchase-payment__select" id="payment-select" name="payment_method">
                        <option value="">選択してください</option>
                        <option value="コンビニ払い">コンビニ払い</option>
                        <option value="カード払い">カード払い</option>
                    </select>
                </div>
                @if ($errors->has('payment_method'))
                    <p class="error-message">{{ $errors->first('payment_method') }}</p>
                @endif
            </div>

            <!-- 配送先 -->
            <div class="purchase-delivery">
                <h2 class="purchase-dReeelivery__title">配送先</h2>
                <a href="{{ route('address.show', ['item_id' => $item->id]) }}" class="purchase-delivery__edit-link">変更する</a>
                {{-- セッションに購入時の住所があればそれを表示 --}}
                @if (session()->has('purchase_address') && session('purchase_address.item_id') == $item->id)
                    <div class="purchase-delivery__address">
                        <p class="purchase-delivery__postal">〒{{ session('purchase_address.postal_code') }}</p>
                        <p class="purchase-delivery__address-line">{{ session('purchase_address.address') }}</p>
                        <p class="purchase-delivery__building">{{ session('purchase_address.building_name') }}</p>
                    </div>
                @else
                    {{-- セッションに住所情報がない場合はユーザーの登録住所を表示 --}}
                    <div class="purchase-delivery__address">
                        <p class="purchase-delivery__postal">〒{{ $user->postal_code }}</p>
                        <p class="purchase-delivery__address-line">{{ $user->address }}</p>
                        <p class="purchase-delivery__building">{{ $user->building_name }}</p>
                    </div>
                @endif
            </div>

            <!-- 確認内容 -->
            <div class="purchase-confirmation">
                <table class="purchase-confirmation__table">
                    <tr class="purchase-confirmation__tr">
                        <th class="purchase-confirmation__th">商品代金</th>
                        <td class="purchase-confirmation__td">￥{{ number_format($item->price) }}</td>
                    </tr>
                    <tr class="purchase-confirmation__tr">
                        <th class="purchase-confirmation__th">支払い方法</th>
                        <td class="purchase-confirmation__td" id="payment-confirmation"></td>
                    </tr>
                </table>
            </div>

            <!-- 購入ボタン -->
            <div class="purchase-action">
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <input type="hidden" name="postal_code" value="{{ session('purchase_address.postal_code') ?? $user->postal_code }}">
                <input type="hidden" name="address" value="{{ session('purchase_address.address') ?? $user->address }}">
                <input type="hidden" name="building_name" value="{{ session('purchase_address.building_name') ?? $user->building_name }}">
                {{-- 後にstripeを使用する --}}
                <button type="submit" class="purchase-action__btn btn">購入する</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
	<script src="{{ asset('js/payment_confirmation.js') }}"></script>
@endsection