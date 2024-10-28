<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Database\Seeders\ItemsTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/**
 * ユーザー情報取得に関するテスト
 */
class UserProfileTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * ユーザーがプロフィールページを開いた際に必要な情報が表示されることをテスト
     *
     * @return void
     */
    public function test_user_profile_page_displays_all_required_information()
    {
        // 必要なシーダーを実行して、ダミーデータを作成
        $this->seed(UsersTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // ユーザーを取得してログイン
        $user = User::first();
        $this->actingAs($user);

        // ユーザーのプロフィール画像を作成して保存
        Storage::fake('public');
        $file = UploadedFile::fake()->image('profile.png');
        $filePath = $file->store('profile_images', 'public');

        // ユーザーデータに画像パスを設定
        $user->image_path = Storage::url($filePath);
        $user->save();

        // 別のユーザーが出品した商品を購入用に作成
        $seller = User::find(2);
        $itemForPurchase = Item::create([
            'seller_id' => $seller->id,
            'name' => 'testItem',
            'description' => 'This is a test item',
            'price' => 1000,
            'condition' => '良好',
            'image_path' => '/storage/item_images/test_image.jpg',
        ]);

        // 購入処理を実行
        $purchase = Purchase::create([
            'item_id' => $itemForPurchase->id,
            'buyer_id' => $user->id,
            'payment_method' => 'card',
            'delivery_postal_code' => $user->postal_code,
            'delivery_address' => $user->address,
            'delivery_building_name' => $user->building_name,
        ]);

        // マイページにアクセス
        $response = $this->get(route('mypage'));

        // ステータスコードが200（正常）であることを確認
        $response->assertStatus(200);

        // プロフィール画像が表示されているか確認
        $response->assertSee($user->image_path);

        // ユーザー名が表示されているか確認
        $response->assertSee($user->name);

        // 出品した商品一覧が表示されているか確認
        $userItems = Item::where('seller_id', $user->id)->get();
        foreach ($userItems as $item) {
            $response->assertSee($item->name);
        }

        // 購入商品ページにアクセス
        $response = $this->get('/mypage?tab=buy');

        // ステータスコードが200（正常）であることを確認
        $response->assertStatus(200);

        // 購入した商品一覧が表示されているか確認
        $purchasedItems = Purchase::where('buyer_id', $user->id)->with('item')->get();
        foreach ($purchasedItems as $purchase) {
            $response->assertSee($purchase->item->name);
        }
    }
}
