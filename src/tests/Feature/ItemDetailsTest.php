<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Item;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\CategorizationsTableSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * 商品詳細情報取得に関するテスト
 */
class ItemDetailsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * 商品詳細ページに必要な情報が表示されることをテスト
     *
     * @return void
     */
    public function test_item_detail_page_displays_all_required_information()
    {
        // 必要なシーダーを実行して、ダミーデータを作成
        $this->seed(UsersTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // 商品を取得
        $item = Item::with(['itemComments', 'categories'])->first();

        // 商品詳細ページにアクセス
        $response = $this->get(route('item.show', ['item_id' => $item->id]));

        // ステータスコードが200（正常）であることを確認
        $response->assertStatus(200);

        // 必要な情報が表示されているか確認
        $response->assertSee(asset('storage/' . $item->image_path)); // 画像のパスが正しく表示されているか確認
        $response->assertSee($item->name);
        $response->assertSee(number_format($item->price)); // 価格をフォーマットして確認
        $response->assertSee($item->myLists->count());
        $response->assertSee($item->itemComments->count());
        $response->assertSee($item->description);
        $response->assertSee($item->categories->pluck('category')->toArray());
        $response->assertSee($item->condition);

        foreach ($item->itemComments as $comment) {
            $response->assertSee($comment->user->name);
            $response->assertSee($comment->comment);
        }
    }

    /**
     * 商品詳細ページに複数選択されたカテゴリが表示されることをテスト
     *
     * @return void
     */
    public function test_item_detail_page_displays_multiple_categories()
    {
        // 必要なシーダーを実行して、ダミーデータを作成
        $this->seed(UsersTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
        $this->seed(CategorizationsTableSeeder::class);

        // 商品を取得（必ず複数のカテゴリーを持っている商品を選ぶ）
        $item = Item::with('categories')->find(10);

        // 商品に少なくとも1つのカテゴリーが付与されていることを確認
        $this->assertTrue($item->categories->count() > 1, '商品にカテゴリーが付与されていません');

        // 商品詳細ページにアクセス
        $response = $this->get(route('item.show', ['item_id' => $item->id]));

        // ステータスコードが200（正常）であることを確認
        $response->assertStatus(200);

        // 複数選択されたカテゴリが表示されているか確認
        foreach ($item->categories as $category) {
            $response->assertSee($category->category);
        }
    }
}

