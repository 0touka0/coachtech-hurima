<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use Illuminate\Support\Facades\Auth;

class AddressChangeController extends Controller
{
    /**
     * 住所変更画面を表示
     *
     * @param int $itemId
     * @return \Illuminate\View\View
     */
    public function showAddressChange($itemId)
    {
        $user = Auth::user();
        return view('purchase.address_change', compact('user', 'itemId'));
    }

    /**
     * 住所変更処理を実行
     *
     * @param \App\Http\Requests\AddressRequest $request
     * @param int $itemId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeAddress(AddressRequest $request, $itemId)
    {
        $address = $request->only(['postal_code', 'address', 'building_name']);

        // セッションに住所情報を保存
        session([
            'purchase_address' => [
                'item_id'       => $itemId,
                'postal_code'   => $address['postal_code'],
                'address'       => $address['address'],
                'building_name' => $address['building_name'],
            ],
        ]);

        // 購入確認ページにリダイレクト
        return redirect()->route('purchase.show', ['item_id' => $itemId]);
    }
}
