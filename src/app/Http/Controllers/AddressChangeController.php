<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use Illuminate\Support\Facades\Auth;

class AddressChangeController extends Controller
{
    // 住所の変更画面
    public function showAddressChange($item_id)
    {
        $user = Auth::user();
        return view('purchase/address_change', compact('user', 'item_id'));
    }

    // 住所を変更する処理
    public function changeAddress(AddressRequest $request, $item_id)
    {
        $address = $request->all();

        // セッションに住所情報を保存
        session([
            'purchase_address' => [
                'item_id'       => $item_id, // 商品IDを保存しておく
                'postal_code'   => $address['postal_code'],
                'address'       => $address['address'],
                'building_name' => $address['building_name'],
            ],
        ]);

        // 購入確認ページなどへリダイレクト
        return redirect()->route('purchase.show', ['item_id' => $item_id]);
    }
}
