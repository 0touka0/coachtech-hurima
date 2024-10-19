<?php

use App\Http\Controllers\AddressChangeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemDetailController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SellController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// ユーザ登録、ログイン
Route::post('/register' , [RegisterController::class, 'store']);
Route::post('/login'    , [LoginController::class, 'store']);

// 商品一覧、商品詳細画面
Route::get('/', [ItemController::class, 'index']);
Route::get('/item/{item_id}', [ItemDetailController::class, 'showItems'])->name('item.show');
Route::get('/item/{item_id}/mylist/count' , [ItemDetailController::class, 'getMylistCount']);
Route::get('/item/{item_id}/comment/count', [ItemDetailController::class, 'getCommentCount']);

// 認証済みユーザのみアクセス可能なルート
Route::middleware(['auth', 'verified'])->group(function () {
	// マイリスト、コメント登録(AJAXを使用、未認証のリダイレクト機能はそれぞれのjsファイルに記載)
	Route::post('/item/{item_id}/mylist' , [ItemDetailController::class, 'toggleMylist']);
	Route::post('/item/{item_id}/comment', [ItemDetailController::class, 'sendComment'])->name('comment.send');

	// 商品購入
	Route::get('/purchase/{item_id}' , [PurchaseController::class, 'showPurchase'])->name('purchase.show');
	Route::post('/purchase/store'	 , [PurchaseController::class, 'store'])->name('purchase.store');

	// 商品購入時の一時的な住所変更
	Route::get('/purchase/address/{item_id}'		, [AddressChangeController::class, 'showAddressChange'])->name('address.show');
	Route::post('/purchase/address/change/{item_id}', [AddressChangeController::class, 'changeAddress'])->name('address.change');

	// マイページ
	Route::get('/mypage', [MypageController::class, 'showMypage'])->name('mypage');

	// プロフィール編集
	Route::get('/mypage/profile', [ProfileController::class, 'editProfile'])->name('profile.edit');
	Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');

	// 出品画面
	Route::get('/sell', [SellController::class, 'showSell'])->name('sell.show');
	Route::post('/sell/store', [SellController::class, 'store'])->name('sell.store');
});
