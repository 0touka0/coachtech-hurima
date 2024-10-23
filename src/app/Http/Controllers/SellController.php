<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SellController extends Controller
{
    /**
     * 出品フォームを表示する
     *
     * @return \Illuminate\View\View
     */
    public function showSell()
    {
        $categories = Category::all();

        return view('item.sell_form', compact('categories'));
    }

    /**
     * 商品の出品処理を行う
     *
     * @param \App\Http\Requests\ExhibitionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ExhibitionRequest $request)
    {
        $user = Auth::user();

        // 画像を保存し、公開用のURLを取得
        $path = $request->file('image')->store('item_images', 'public');
        $imagePath = Storage::url($path);

        // 商品情報を保存
        $item = Item::create([
            'seller_id'   => $user->id,
            'name'        => $request->input('name'),
            'description' => $request->input('description'),
            'price'       => $request->input('price'),
            'condition'   => $request->input('condition'),
            'image_path'  => $imagePath,
        ]);

        // カテゴリーを保存（多対多の関係）
        $categories = $request->input('category'); // 選択されたカテゴリーID
        $item->categories()->sync($categories); // 中間テーブルに保存

        return redirect('/mypage');
    }
}
