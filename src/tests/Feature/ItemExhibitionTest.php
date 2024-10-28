<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;


/**
 * 商品出品機能に関するテスト
 */
class ItemExhibitionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 商品出品画面で各項目が正しく保存されることをテスト
     *
     * @return void
     */
    public function test_item_exhibition_page_saves_all_required_information()
    {
        // 必要なシーダーを実行して、ダミーデータを作成
        $this->seed(UsersTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);

         // ユーザーを取得してログイン
        $user = User::first();
        $this->actingAs($user);

        $category = Category::first();

        // 画像ファイルの保存を模擬
        Storage::fake('public');
        $image = UploadedFile::fake()->image('item.png');


        // 商品出品情報を送信
        $response = $this->post(route('sell.store'), [
            'name' => 'Test Item',
            'description' => 'This is a test description',
            'price' => 1500,
            'condition' => '良好',
            'category' => [$category->id],
            'image' => $image,
        ]);

        // ステータスコードが302（リダイレクト）であることを確認
        $response->assertStatus(302);

        // データベースに商品情報が保存されていることを確認
        $this->assertDatabaseHas('items', [
            'name' => 'Test Item',
            'description' => 'This is a test description',
            'price' => 1500,
            'condition' => '良好',
            'image_path' => '/storage/item_images/' . basename($image->hashName()),
        ]);

        // カテゴリーの関連付けが保存されていることを確認
        $item = Item::where('name', 'Test Item')->first();
        $this->assertDatabaseHas('categorizations', [
            'item_id' => $item->id,
            'category_id' => [$category->id],
        ]);
    }
}
