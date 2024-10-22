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
        $userId = auth()->id();
        // クエリパラメータからタブとキーワードを取得
        $tab = $request->query('tab', session('tab', 'recommend'));
        $keyword = $request->query('keyword', session('keyword'));

        // もしクエリパラメータがなければセッションをリセット（リセット時は通常表示）
        if (!$request->has('keyword')) {
            session()->forget('keyword'); // 検索キーワードをクリア
        } else {
            session(['keyword' => $keyword]);
        }

        session(['tab' => $tab]);

        // 選択したタブの商品を取得
        $items = $this->getItemsByTab($tab, $userId, $keyword);

        // 購入済み商品のIDを取得
        $soldItemIds = $this->getSoldItemIds();

        return view('item/list', compact('tab', 'items', 'soldItemIds'));
    }

    public function search(Request $request)
    {
        // indexメソッドと同じロジック
        return $this->index($request);
    }

    // タブによってアイテムを取得する共通メソッド
    private function getItemsByTab($tab, $userId, $keyword = null)
    {
        if ($tab === 'mylist') {
            // マイリストに登録された商品を取得
            $query = Mylist::where('user_id', $userId)
                ->where('is_favorited', 1)
                ->whereHas('item', function ($query) use ($userId) {
                    $query->where('seller_id', '!=', $userId); // 自身で出品した商品を除く
                })
                ->with('item');

            if ($keyword) {
                $query->whereHas('item', function ($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                });
            }

            return $query->get()->pluck('item');
        } else {
            // 全ての商品からおすすめ商品または検索結果を取得
            $query = Item::where('seller_id', '!=', $userId); // 自身で出品した商品を除く

            if ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            }

            return $query->get();
        }
    }

    // 購入済み商品のIDを取得する共通メソッド
    private function getSoldItemIds()
    {
        return Purchase::pluck('item_id')->toArray();
    }
}