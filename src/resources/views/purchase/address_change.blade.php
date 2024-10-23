@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase/address_change.css') }}">
@endsection

@section('main')
    <div class="address-change-page">
        <h1 class="address-change-page__header">住所の変更</h1>
        <div class="address-form">
            <form action="{{ route('address.change', ['item_id' => $itemId]) }}" method="post">
                @csrf

                <!-- 郵便番号の入力 -->
                <div class="address-form__group">
                    <label class="address-form__label" for="postal_code">郵便番号</label>
                    <input class="address-form__input" id="postal_code" type="text" name="postal_code" value="{{ $user->postal_code }}">
                    @if ($errors->has('postal_code'))
                        <p class="error-message">{{ $errors->first('postal_code') }}</p>
                    @endif
                </div>

                <!-- 住所の入力 -->
                <div class="address-form__group">
                    <label class="address-form__label" for="address">住所</label>
                    <input class="address-form__input" id="address" type="text" name="address" value="{{ $user->address }}">
                    @if ($errors->has('address'))
                        <p class="error-message">{{ $errors->first('address') }}</p>
                    @endif
                </div>

                <!-- 建物名の入力 -->
                <div class="address-form__group">
                    <label class="address-form__label" for="building_name">建物名</label>
                    <input class="address-form__input" id="building_name" type="text" name="building_name" value="{{ $user->building_name }}">
                    @if ($errors->has('building_name'))
                        <p class="error-message">{{ $errors->first('building_name') }}</p>
                    @endif
                </div>

                <!-- 更新ボタン -->
                <button class="btn address-form__btn" type="submit">更新する</button>
            </form>
        </div>
    </div>
@endsection
