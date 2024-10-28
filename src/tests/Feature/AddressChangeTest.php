<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Database\Seeders\ItemsTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AddressChangeTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * 送付先住所変更画面にて登録した住所が商品購入画面に反映されていることをテスト
     *
     * @return void
     */
    public function test_updated_address_is_reflected_on_purchase_screen()
    {
        // 必要なシーダーを実行して、ダミーデータを作成
        $this->seed(UsersTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);
        $item = Item::first();

        // ユーザーを取得してログイン
        $user = User::find(2);
        $this->actingAs($user);

        // 送付先住所変更画面で住所を登録する
        $response = $this->post(route('address.change', ['item_id' => $item->id]), [
            'postal_code' => '123-4567',
            'address' => '東京都港区1-1-1',
            'building_name' => '港区ビル',
        ]);

        // リダイレクトを追跡して購入確認ページにアクセス
        $response = $this->followRedirects($response);

        // 登録した住所が商品購入画面に正しく反映されていることを確認
        $response->assertSee('123-4567');
        $response->assertSee('東京都港区1-1-1');
        $response->assertSee('港区ビル');
    }

    /**
     * 購入した商品に送付先住所が紐づいて登録されることをテスト
     *
     * @return void
     */
    public function test_purchase_is_linked_with_updated_delivery_address()
    {
        // 必要なシーダーを実行して、ダミーデータを作成
        $this->seed(UsersTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);
        $item = Item::first();

        // ユーザーをログインさせる
        $user = User::find(2);
        $this->actingAs($user);

        // 送付先住所変更画面で住所を登録する
        $response = $this->post(route('address.change', ['item_id' => $item->id]), [
            'postal_code' => '123-4567',
            'address' => '東京都港区1-1-1',
            'building_name' => '港区ビル',
        ]);

        // セッションデータをリクエストとして送信し購入データを作成
        $response = $this->post(route('purchase.store', ['item_id' => $item->id]), [
                'payment_method' => 'card',
                'postal_code' => session('purchase_address.postal_code'),
                'address' => session('purchase_address.address'),
                'building_name' => session('purchase_address.building_name'),
            ]);

        $response->assertStatus(302);

        // 正しく送付先住所が紐づいていることを確認
        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'buyer_id' => $user->id,
            'delivery_postal_code' => '123-4567',
            'delivery_address' => '東京都港区1-1-1',
            'delivery_building_name' => '港区ビル',
        ]);
    }
}
