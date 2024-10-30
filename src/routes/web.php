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
use Illuminate\Foundation\Auth\EmailVerificationRequest;
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
// ユーザ登録、ログイン、認証ルート
Route::post('/register', [RegisterController::class, 'store']);
Route::post('/login', [LoginController::class, 'store']);
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
	$request->fulfill();
	return redirect()->route('profile.edit'); // 認証完了後にリダイレクト
})->middleware(['auth', 'signed'])->name('verification.verify');

// 商品一覧
Route::get('/', [ItemController::class, 'index']);

// 商品詳細関連のルート
Route::prefix('item/{item_id}')->group(function () {
	Route::get('/', [ItemDetailController::class, 'showItems'])->name('item.show');
	Route::get('/mylist/count' , [ItemDetailController::class, 'getMylistCount']);
	Route::get('/comment/count', [ItemDetailController::class, 'getCommentCount']);
});

// 検索機能
Route::get('/search', [ItemController::class, 'search']);

// 認証済みユーザーのみアクセス可能なルート
Route::middleware(['auth', 'verified'])->group(function () {
	// マイリスト、コメント登録
	Route::post('/item/{item_id}/mylist' , [ItemDetailController::class, 'toggleMylist'])->name('item.mylist.toggle');
	Route::post('/item/{item_id}/comment', [ItemDetailController::class, 'sendComment'])->name('comment.send');

	// 購入関連のルート
	Route::prefix('purchase/{item_id}')->group(function () {
		Route::get('/', [PurchaseController::class, 'showPurchase'])->name('purchase.show');
		Route::post('/store' , [PurchaseController::class, 'store'])->name('purchase.store');
		Route::get('/address', [AddressChangeController::class, 'showAddressChange'])->name('address.show');
		Route::post('/address/change', [AddressChangeController::class, 'changeAddress'])->name('address.change');
	});
	Route::post('/update-payment-method', [PurchaseController::class, 'updatePaymentMethod'])->name('payment.update');

	// マイページ関連のルート
	Route::get('/mypage', [MypageController::class, 'showMypage'])->name('mypage');
	Route::get('/mypage/profile', [ProfileController::class, 'editProfile'])->name('profile.edit');
	Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');

	// 出品関連のルート
	Route::get('/sell', [SellController::class, 'showSell'])->name('sell.show');
	Route::post('/sell/store', [SellController::class, 'store'])->name('sell.store');
});
