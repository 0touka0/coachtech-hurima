<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Mylist;
use App\Models\Purchase;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * 商品の一覧画面を表示する
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $userId = auth()->id();

        // クエリパラメータまたはセッションからタブの状態を取得
        $tab = $request->query('tab', session('tab', 'recommend'));

        // 検索キーワードはセッションに保持
        $keyword = session('keyword', null);

        // タブに応じた商品を取得
        $items = $this->getItemsByTab($tab, $userId, $keyword);

        $soldItemIds = $this->getSoldItemIds();

        return view('item.list', compact('tab', 'keyword', 'items', 'soldItemIds'));
    }

    /**
     * 商品検索
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $userId = auth()->id();
        $tab = $request->input('tab', 'recommend');
        $keyword = $request->input('keyword');

        // 検索キーワードとタブの状態をセッションに保存
        session(['keyword' => $keyword, 'tab' => $tab]);

        $items = $this->getItemsByTab($tab, $userId, $keyword);
        $soldItemIds = $this->getSoldItemIds();

        return view('item.list', compact('tab', 'keyword', 'items', 'soldItemIds'));
    }

    /**
     * タブに応じたアイテムを取得する
     *
     * @param string $tab
     * @param int $userId
     * @param string|null $keyword
     * @return \Illuminate\Support\Collection
     */
    private function getItemsByTab($tab, $userId, $keyword = null)
    {
        if ($tab === 'mylist') {
            return $this->getMylistItems($userId, $keyword);
        } else {
            return $this->getRecommendedItems($userId, $keyword);
        }
    }

    /**
     * マイリストに登録された商品を取得する
     *
     * @param int $userId
     * @param string|null $keyword
     * @return \Illuminate\Support\Collection
     */
    private function getMylistItems($userId, $keyword = null)
    {
        $query = Mylist::where('user_id', $userId)
                       ->where('is_favorited', 1)
                       ->whereHas('item', function ($query) use ($userId) {
                           $query->where('seller_id', '!=', $userId); // 自分の出品商品を除外
                       })
                       ->with('item');

        if ($keyword) {
            $query->whereHas('item', function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            });
        }

        return $query->get()->pluck('item');
    }

    /**
     * 推奨される商品を取得する
     *
     * @param int $userId
     * @param string|null $keyword
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getRecommendedItems($userId, $keyword = null)
    {
        $query = Item::where('seller_id', '!=', $userId);

        if ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        return $query->get();
    }

    /**
     * 購入済み商品のIDを取得する
     *
     * @return array
     */
    private function getSoldItemIds()
    {
        return Purchase::pluck('item_id')->toArray();
    }
}
