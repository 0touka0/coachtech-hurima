<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Item;
use App\Models\ItemComment;
use App\Models\Mylist;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class ItemDetailController extends Controller
{
    // 商品の詳細画面
    public function showItems($item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        $isFavorited = $user && Mylist::where('user_id', $user->id)
                                      ->where('item_id', $item->id)
                                      ->where('is_favorited', true)
                                      ->exists();

        $comments = ItemComment::where('item_id', $item_id)->get();
        $isSold = Purchase::where('item_id', $item_id)->first();

        return view('item/detail', compact('user', 'item', 'isFavorited', 'comments', 'isSold'));
    }

    // マイリスト登録
    public function toggleMylist($item_id)
    {
        $user = auth()->user();
        $item = Item::findOrFail($item_id);

        // マイリストに既に登録されているか確認
        $mylist = Mylist::where('user_id', $user->id)
                        ->where('item_id', $item->id)
                        ->where('is_favorited', true)
                        ->first();

        if ($mylist) {
            // 既にマイリストに登録されている場合は解除
            $mylist->update(['is_favorited' => false]);
            $isInMylist = false;
        } else {
            // マイリストに登録されていない場合は登録or更新
            Mylist::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'item_id' => $item->id,
                ],
                [
                    'is_favorited' => true
                ]
            );
            $isInMylist = true;
        }

        // 登録or解除した際のアイコンを変更するため
        return response()->json([
            'in_mylist' => $isInMylist
        ]);
    }

    // マイリスト登録数を取得
    public function getMylistCount($itemId)
    {
        $item = Item::findOrFail($itemId);
        $mylistCount = Mylist::where('item_id', $item->id)
                             ->where('is_favorited', true)
                             ->count();

        return response()->json(['mylist_count' => $mylistCount]);
    }

    // コメント登録
    public function sendComment(CommentRequest $request, $item_id)
    {
        ItemComment::create([
            'user_id' => $request->id,
            'item_id' => $item_id,
            'comment' => $request->comment,
        ]);

        return redirect()->back();
    }

    // コメント数を取得
    public function getCommentCount($itemId)
    {
        $item = Item::findOrFail($itemId);
        $commentCount = ItemComment::where('item_id', $item->id)
                                   ->count();

        return response()->json(['comment_count' => $commentCount]);
    }
}
