<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * いいね機能に関するテスト
 */
class LikeFunctionalityTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * いいねアイコンを押下して商品を登録し、いいね合計値が増加することをテスト
     *
     * @return void
     */
    public function test_can_add_item_to_favorites_and_increase_like_count()
    {
        // 必要なシーダーを実行して、ダミーデータを作成
        $this->seed(UsersTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // ユーザーをログインさせる
        $user = User::first();
        $this->actingAs($user);

        // 商品を取得
        $item = Item::first();

        // 商品詳細ページにアクセス
        $this->get(route('item.show', ['item_id' => $item->id]));

        // 商品詳細ページにアクセスし、いいねアイコンを押下
        $response = $this->post(route('item.mylist.toggle', ['item_id' => $item->id]));

        // ステータスコードが200（正常）であることを確認
        $response->assertStatus(200);

        // いいねが追加されたことを確認
        $this->assertDatabaseHas('mylists', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'is_favorited' => true,
        ]);
    }

    /**
     * いいねアイコンが押下された状態で色が変化することをテスト
     *
     * @return void
     */
    public function test_like_icon_changes_color_when_item_is_liked()
    {
        // 必要なシーダーを実行して、ダミーデータを作成
        $this->seed(UsersTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // ユーザーをログインさせる
        $user = User::first();
        $this->actingAs($user);

        // 商品を取得
        $item = Item::first();

        // 商品詳細ページにアクセス (最初にページを開く)
        $this->get(route('item.show', ['item_id' => $item->id]));

        // 商品詳細ページにアクセスし、いいねアイコンを押下
        $this->post(route('item.mylist.toggle', ['item_id' => $item->id]));

        // 商品詳細ページを再度取得し、アイコンの状態を確認
        $response = $this->get(route('item.show', ['item_id' => $item->id]));

        // いいねが追加されているアイコンが表示されていることを確認
        $response->assertSee('fa-solid');
    }


    /**
     * いいねを解除して、いいね合計値が減少することをテスト
     *
     * @return void
     */
    public function test_can_remove_item_from_favorites_and_decrease_like_count()
    {
        // 必要なシーダーを実行して、ダミーデータを作成
        $this->seed(UsersTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // ユーザーをログインさせる
        $user = User::first();
        $this->actingAs($user);

        // 商品を取得
        $item = Item::first();

        // 商品詳細ページにアクセス
        $this->get(route('item.show', ['item_id' => $item->id]));

        // 商品詳細ページにアクセスし、いいねアイコンを押下して登録
        $this->post(route('item.mylist.toggle', ['item_id' => $item->id]));

        // いいねを解除するため再度いいねアイコンを押下
        $this->post(route('item.mylist.toggle', ['item_id' => $item->id]));

        // いいねが解除されたことを確認
        $this->assertDatabaseMissing('mylists', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'is_favorited' => true,
        ]);
    }
}
