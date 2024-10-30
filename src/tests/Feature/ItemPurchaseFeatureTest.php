<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Database\Seeders\ItemsTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * 商品購入機能に関するテスト
 */
class ItemPurchaseFeatureTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    /**
     * 「購入する」ボタンを押下すると購入が完了することをテスト
     *
     * @return void
     */
    public function test_purchase_button_completes_purchase()
    {
        // シーダーを実行してデータをセットアップし、ユーザーにログインする
        $this->seed(UsersTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // ユーザーを取得してログイン
        $user = User::find(2);
        $this->actingAs($user);

        // 商品購入画面を開く
        $item = Item::first();
        $this->get(route('purchase.show', ['item_id' => $item->id]))->assertStatus(200);

        // 商品を選択して「購入する」ボタンを押下
        $response = $this->post(route('purchase.store', ['item_id' => $item->id]), [
            'payment_method' => 'card',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building_name' => 'Test Building',
        ]);

        // 購入が完了することを確認
        $response->assertStatus(302); // 成功後のリダイレクトを確認
        $this->assertDatabaseHas('purchases', [
            'buyer_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /**
     * 購入した商品は商品一覧画面にて「sold」と表示されることをテスト
     *
     * @return void
     */
    public function test_purchased_item_is_displayed_as_sold_in_item_list()
    {
        // シーダーを実行してデータをセットアップし、ユーザーにログインする
        $this->seed(UsersTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // ユーザーを取得してログイン
        $user = User::find(2);
        $this->actingAs($user);

        // 商品購入画面を開く
        $item = Item::first();
        $this->get(route('purchase.show', ['item_id' => $item->id]))->assertStatus(200);

        // 商品を選択して「購入する」ボタンを押下
        $this->post(route('purchase.store', ['item_id' => $item->id]), [
            'payment_method' => 'card',
            'postal_code' => '1234567',
            'address' => '東京都渋谷区1-1-1',
            'building_name' => 'Test Building',
        ])->assertStatus(302);

        // 商品一覧画面を表示する
        $response = $this->get('/');

        // 「SOLD」の表示があることを確認
        $response->assertSeeText('SOLD');
    }

    /**
     * 購入した商品が「プロフィール/購入した商品一覧」に追加されていることをテスト
     *
     * @return void
     */
    public function test_purchased_item_is_added_to_profile_purchases()
    {
        // シーダーを実行してデータをセットアップし、ユーザーにログインする
        $this->seed(UsersTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // ユーザーを取得してログイン
        $user = User::find(2);
        $this->actingAs($user);

        // 商品購入画面を開く
        $item = Item::first();
        $this->get(route('purchase.show', ['item_id' => $item->id]))->assertStatus(200);

        // 商品を選択して「購入する」ボタンを押下
        $this->post(route('purchase.store', ['item_id' => $item->id]), [
            'payment_method' => 'card',
            'postal_code' => '1234567',
            'address' => '東京都渋谷区1-1-1',
            'building_name' => 'Test Building',
        ])->assertStatus(302);

        // プロフィール画面を表示する
        $response = $this->get('/mypage?tab=buy');

        // 購入した商品がプロフィールの購入した商品一覧に追加されていることを確認
        $response->assertSee($item->name);
    }
}
