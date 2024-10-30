<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Item;
use App\Models\ItemComment;
use App\Models\Mylist;
use Illuminate\Support\Facades\Auth;

class ItemDetailController extends Controller
{
    /**
     * 商品の詳細画面を表示
     *
     * @param int $itemId
     * @return \Illuminate\View\View
     */
    public function showItems($itemId)
    {
        $user = Auth::user();
        $item = Item::with(['itemComments', 'purchases'])->findOrFail($itemId);

        $isFavorited = $user && Mylist::where('user_id', $user->id)
                                      ->where('item_id', $item->id)
                                      ->where('is_favorited', true)
                                      ->exists();

        // 購入されているかを確認
        $isSold = $item->purchases->isNotEmpty(); // リレーションを利用

        return view('item.detail', compact('user', 'item', 'isFavorited', 'isSold'));
    }

    /**
     * マイリストに登録または解除
     *
     * @param int $itemId
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleMylist($itemId)
    {
        $user = auth()->user();
        $item = Item::findOrFail($itemId);

        $mylist = Mylist::where('user_id', $user->id)
                        ->where('item_id', $item->id)
                        ->first();

        if ($mylist && $mylist->is_favorited) {
            // 既に登録されていれば解除
            $mylist->update(['is_favorited' => false]);
            return response()->json(['in_mylist' => false]);
        }

        // まだ登録されていない場合は登録
        Mylist::updateOrCreate(
            ['user_id' => $user->id, 'item_id' => $item->id],
            ['is_favorited' => true]
        );

        return response()->json(['in_mylist' => true]);
    }

    /**
     * マイリスト登録数を取得
     *
     * @param int $itemId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMylistCount($itemId)
    {
        $item = Item::findOrFail($itemId);
        $mylistCount = Mylist::where('item_id', $item->id)
                             ->where('is_favorited', true)
                             ->count();

        return response()->json(['mylist_count' => $mylistCount]);
    }

    /**
     * コメントを登録
     *
     * @param \App\Http\Requests\CommentRequest $request
     * @param int $itemId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendComment(CommentRequest $request, $itemId)
    {
        $user = Auth::user();

        ItemComment::create([
            'user_id' => $user->id,
            'item_id' => $itemId,
            'comment' => $request->comment,
        ]);

        return redirect()->back();
    }

    /**
     * コメント数を取得
     *
     * @param int $itemId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCommentCount($itemId)
    {
        $item = Item::findOrFail($itemId);
        $commentCount = ItemComment::where('item_id', $item->id)->count();

        return response()->json(['comment_count' => $commentCount]);
    }
}
