<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Item;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\ItemsTableSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * 商品検索機能に関するテスト
 */
class ItemSearchTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * 商品名で部分一致検索ができることをテスト
     *
     * @return void
     */
    public function test_can_search_items_by_name()
    {
        // 必要なシーダーを実行して、ダミーデータを作成
        $this->seed(UsersTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // キーワードを設定して検索ページにアクセス
        $keyword = '腕時計';
        $response = $this->get('/search?keyword=' . $keyword);

        // ステータスコードが200（正常）であることを確認
        $response->assertStatus(200);

        // 部分一致する商品が表示されていることを確認
        $items = Item::where('name', 'like', '%' . $keyword . '%')->get();
        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    /**
     * 検索状態がマイリストでも保持されていることをテスト
     *
     * @return void
     */
    public function test_search_keyword_is_preserved_in_mylist()
    {
        // 必要なシーダーを実行して、ダミーデータを作成
        $this->seed(UsersTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // 検索キーワードを設定して、ホームページで商品を検索
        $keyword = '腕時計';
        $response = $this->get('/search?keyword=' . $keyword);

        // ステータスコードが200（正常）であることを確認
        $response->assertStatus(200);

        // セッションに検索キーワードが保存されていることを確認
        $this->assertEquals(session('keyword'), $keyword);

        // マイリストページにアクセス
        $response = $this->get('/?tab=mylist');

        // ステータスコードが200（正常）であることを確認
        $response->assertStatus(200);

        // 検索キーワードが保持されていることを確認
        $response->assertViewHas('keyword', $keyword);
    }
}

