<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SellController extends Controller
{
    public function showSell()
    {
        $categories = Category::all();

        return view('item/sell_form', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $user = Auth::user();

        // 画像を保存してパスを取得
        $path = $request->file('image')->store('item_images', 'public');
        $imagePath = Storage::url($path); // 公開用のURLに変換

        // 商品情報を保存
        $item = Item::create([
            'seller_id'   => $user->id,
            'name'        => $request['name'],
            'description' => $request['description'],
            'price'       => $request['price'],
            'condition'   => $request['condition'],
            'image_path'  => $imagePath,
        ]);

        // カテゴリーを保存（多対多の関係の場合）
        $categories = $request->input('category'); // フォームから受け取ったカテゴリーID
        $item->categories()->sync($categories); // 中間テーブルに保存

        return redirect('/mypage');
    }
}
