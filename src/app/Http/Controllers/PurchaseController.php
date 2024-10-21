<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Price as StripePrice;

class PurchaseController extends Controller
{
    public function showPurchase($item_id)
    {
        $user = Auth::user();
        $item = Item::find($item_id);

        // 出品者が自分だった場合、購入ページに進めないようにする
        if ($item->seller_id == $user->id) {
            return redirect()->back()->with('error', '自分が出品した商品は購入できません。');
        }

        return view('item/purchase_form', compact('user','item'));
    }

    public function store(PurchaseRequest $request)
    {
        $user = Auth::user();
        $item = Item::find($request->item_id);

        // 出品者が自分だった場合、購入処理を行わない
        if ($item->seller_id == $user->id) {
            return redirect()->back()->with('error', '自分が出品した商品は購入できません。');
        }

        // 1. 購入情報を保存
        Purchase::create([
            'item_id'        => $request->item_id,
            'buyer_id'       => Auth::id(),
            'payment_method' => $request->payment_method,
            'delivery_postal_code'   => $request->postal_code,
            'delivery_address'       => $request->address,
            'delivery_building_name' => $request->building_name,
        ]);

        // 2. StripeのAPIキーを設定
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // 3. 商品情報の取得
        $item = Item::find($request->item_id);

        // 4. Stripeの価格オブジェクトを作成
        $stripePrice = StripePrice::create([
            'product_data' => [
                'name' => $item->name,
            ],
            'unit_amount' => $item->price,
            'currency' => 'jpy',
        ]);

        // 5. 支払い方法に応じてCheckoutセッションを作成
        if ($request->payment_method === 'card') {
            // カード払いの場合
            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $stripePrice->id,
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => url('/mypage?tab=buy'), // 成功時のリダイレクトURL
                'cancel_url' => route('item.show', $item->id), // キャンセル時のリダイレクトURL
            ]);
        } elseif ($request->payment_method === 'konbini') {
            // コンビニ払いの場合
            $checkoutSession = Session::create([
                'payment_method_types' => ['konbini'],
                'line_items' => [[
                    'price' => $stripePrice->id,
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => url('/mypage?tab=buy'), // 成功時のリダイレクトURL
                'cancel_url' => route('item.show', $item->id), // キャンセル時のリダイレクトURL
            ]);
        }

        // 6. StripeのCheckoutページにリダイレクト
        return redirect($checkoutSession->url);
    }
}
