<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\CategorizationsTableSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * 商品一覧表示に関するテスト
 */
class ItemListingTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * 全商品が表示されることをテスト
     *
     * @return void
     */
    public function test_can_retrieve_all_items()
    {
        // 必要なシーダーを実行して、ダミーデータを作成
        $this->seed(UsersTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // 商品ページにアクセス
        $response = $this->get('/');

        // ステータスコードが200（正常）であることを確認
        $response->assertStatus(200);

        // 商品が表示されているか確認（シーダーで作成した商品データが表示されているか確認）
        $items = Item::all();
        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    /**
     * 購入済みの商品に「Sold」ラベルが表示されることをテスト
     *
     * @return void
     */
    public function test_sold_label_is_displayed_for_purchased_items_on_list_page()
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);
        $this->seed(CategorizationsTableSeeder::class);

        // 購入済みの商品をシミュレート
        $item = Item::first(); // シーダーで最初に作成された商品を取得する
        $buyer = User::factory()->create();

        // 購入データを作成する（purchasesテーブルにレコードを追加する）
        Purchase::create([
            'item_id' => $item->id,
            'buyer_id' => $buyer->id,
            'payment_method' => 'card',
            'delivery_postal_code' => '1234567',
            'delivery_address' => '東京都渋谷区1-1-1',
            'delivery_building_name' => 'Test Building',
        ]);

        // 商品ページにアクセス
        $response = $this->get('/');

        // 「SOLD」の表示があることを確認
        $response->assertSeeText('SOLD');
    }


    /**
     * 自分が出品した商品が表示されないことをテスト
     *
     * @return void
     */
    public function test_own_items_are_not_displayed()
    {
        // シーダーを実行して、ユーザーと商品データをセットアップ
        $this->seed(UsersTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // // ユーザーを取得してログイン
        $user = User::first();
        $this->actingAs($user);

        // 商品ページにアクセス
        $response = $this->get('/');

        // 自分が出品した商品が表示されないことを確認
        $ownItems = Item::where('seller_id', $user->id)->get();
        foreach ($ownItems as $item) {
            $response->assertDontSee($item->name);
        }

        // 他のユーザーの商品が表示されることを確認
        $otherItems = Item::where('seller_id', '!=', $user->id)->get();
        foreach ($otherItems as $item) {
            $response->assertSee($item->name);
        }
    }
}