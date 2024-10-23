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
    /**
     * 購入画面を表示
     *
     * @param int $itemId
     * @return \Illuminate\View\View
     */
    public function showPurchase($itemId)
    {
        $user = Auth::user();
        $item = Item::findOrFail($itemId);

        // 出品者が自分の場合、購入できない
        if ($item->seller_id == $user->id) {
            return redirect()->back()->with('error', '自分が出品した商品は購入できません。');
        }

        return view('item.purchase_form', compact('user', 'item'));
    }

    /**
     * 購入処理を実行
     *
     * @param \App\Http\Requests\PurchaseRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PurchaseRequest $request)
    {
        $user = Auth::user();
        $item = Item::findOrFail($request->item_id);

        // 出品者が自分だった場合、購入できない
        if ($item->seller_id == $user->id) {
            return redirect()->back()->with('error', '自分が出品した商品は購入できません。');
        }

        // 購入情報の保存
        Purchase::create([
            'item_id'                => $request->item_id,
            'buyer_id'               => $user->id,
            'payment_method'         => $request->payment_method,
            'delivery_postal_code'   => $request->postal_code,
            'delivery_address'       => $request->address,
            'delivery_building_name' => $request->building_name,
        ]);

        // StripeのAPIキーを設定
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Stripeの価格オブジェクトを作成
        $stripePrice = StripePrice::create([
            'product_data' => [
                'name' => $item->name,
            ],
            'unit_amount' => $item->price, // 円単位で指定
            'currency' => 'jpy',
        ]);

        // 支払い方法に応じてCheckoutセッションを作成
        $checkoutSession = $this->createCheckoutSession($request->payment_method, $stripePrice->id, $item);

        // StripeのCheckoutページにリダイレクト
        return redirect($checkoutSession->url);
    }

    /**
     * StripeのCheckoutセッションを作成
     *
     * @param string $paymentMethod
     * @param string $priceId
     * @param \App\Models\Item $item
     * @return \Stripe\Checkout\Session
     */
    private function createCheckoutSession($paymentMethod, $priceId, $item)
    {
        $paymentMethodTypes = [$paymentMethod];

        return Session::create([
            'payment_method_types' => $paymentMethodTypes,
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/mypage?tab=buy'), // 成功時のリダイレクトURL
            'cancel_url' => route('item.show', $item->id), // キャンセル時のリダイレクトURL
        ]);
    }
}
