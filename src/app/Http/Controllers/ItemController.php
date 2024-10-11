<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Mylist;
use App\Models\Purchase;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // 商品の一覧画面
    public function index(Request $request)
    {
        // タブの状態を取得
        $tab = $request->query('tab', 'recommend');
        $userId = auth()->id();

        // マイリストの商品を取得
        if ($tab === 'mylist') {
            // マイリストに登録された商品の取得
            $items = Mylist::where('user_id', $userId)
                           ->where('is_favorited', 1)
                           ->whereHas('item', function ($query) use ($userId) {
                                $query->where('seller_id', '!=', $userId);
                            })
                           ->with('item')
                           ->get()
                           ->pluck('item');
        } else {
            // おすすめ商品を取得
            $items = Item::where('seller_id', '!=', $userId)->get();
        }

        // 購入済み商品のIDを取得
        $soldItemIds = Purchase::pluck('item_id')->toArray();

        return view('item_list', compact('tab', 'items', 'soldItemIds'));
    }
}