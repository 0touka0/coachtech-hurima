<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    /**
     * プロフィール画面
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function showMypage(Request $request)
    {
        $user   = Auth::user();
        $userId = $user->id;

        // タブの状態を取得
        $tab = $request->query('tab', 'sell');

        // 出品したor購入した商品を取得
        if ($tab === 'sell') {
            // 出品した商品を取得
            $items = Item::where('seller_id', $userId)
                         ->get();
        } else {
            // 購入した商品を取得
            $items = Purchase::where('buyer_id', $userId)
                             ->with('item') // 関連するデータを一度にロード
                             ->get()
                             ->pluck('item')
                             ->unique('id');
        }

        return view('user/mypage', compact('user', 'tab', 'items'));
    }
}
