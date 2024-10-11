<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function showPurchase($item_id)
    {
        $user = Auth::user();
        $item = Item::find($item_id);

        return view('purchase/item_purchase', compact('user','item'));
    }

    public function store(PurchaseRequest $request)
    {
        // ログインユーザーIDと商品IDでpurchasesテーブルに保存
        Purchase::create([
            'item_id'        => $request->item_id,
            'buyer_id'       => Auth::id(),
            'payment_method' => $request->payment_method,
            'delivery_postal_code'   => $request->postal_code,
            'delivery_address'       => $request->address,
            'delivery_building_name' => $request->building_name,
        ]);

        // 商品一覧画面にリダイレクト(基本設計後、stripeの決済画面に変更)
        return redirect('/');
    }
}
