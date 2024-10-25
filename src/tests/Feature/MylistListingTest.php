<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Mylist;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * マイリスト表示に関するテスト
 */
class MylistListingTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * いいねした商品が表示されることをテスト
     *
     * @return void
     */
    public function test_can_retrieve_liked_items()
    {
        // シーダーを実行してデータをセットアップ
        $this->seed(UsersTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // ユーザーを取得してログイン
        $user = User::find(2);
        $this->actingAs($user);

        // いいね（マイリストに追加）された商品を作成
        $likedItem = Item::first();
        Mylist::create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
            'is_favorited' => true,
        ]);

        // マイリストページにアクセス
        $response = $this->get('/?tab=mylist');

        // いいねした商品が表示されているか確認
        $response->assertSee($likedItem->name);
    }

    /**
     * 購入済み商品に「SOLD」ラベルが表示されることをテスト
     *
     * @return void
     */
    public function test_sold_label_is_displayed_for_purchased_items_in_mylist()
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // ユーザーを取得してログイン
        $user = User::find(2);
        $this->actingAs($user);

        // いいねされたが自分が出品した商品を作成
        $item = Item::first();
        Mylist::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'is_favorited' => true,
        ]);

        // 購入データを作成して購入済みにする
        Purchase::create([
            'item_id' => $item->id,
            'buyer_id' => $user->id,
            'payment_method' => 'credit_card',
            'delivery_postal_code' => '1234567',
            'delivery_address' => '東京都渋谷区1-1-1',
            'delivery_building_name' => 'Test Building',
        ]);

        // マイリストページにアクセス
        $response = $this->get('/?tab=mylist');

        // 「SOLD」が表示されていることを確認
        $response->assertSeeText('SOLD');
    }

    /**
     * 自分が出品した商品が表示されないことをテスト
     *
     * @return void
     */
    public function test_own_items_are_not_displayed_in_mylist()
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // ユーザーを取得してログイン
        $user = User::first();
        $this->actingAs($user);

        // ログインしたユーザーの商品を作成
        $ownItem = Item::where('seller_id', $user->id)->first();

        // いいねされたが自分が出品した商品を作成
        Mylist::create([
            'user_id' => $user->id,
            'item_id' => $ownItem->id,
            'is_favorited' => true,
        ]);

        // マイリストページにアクセス
        $response = $this->get('/?tab=mylist');

        // 自分の商品が表示されていないことを確認
        $response->assertDontSee($ownItem->name);
    }

    /**
     * 未認証ユーザーがマイリストページにアクセスした際に何も表示されないことをテスト
     *
     * @return void
     */
    public function test_nothing_is_displayed_for_unauthenticated_users()
    {
        // ログインしていない状態でマイリストページにアクセス
        $response = $this->get('/?tab=mylist');

        // ステータスコードが200（正常）であることを確認
        $response->assertStatus(200);
    }
}
