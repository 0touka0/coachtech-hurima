<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Database\Seeders\ItemsTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * 支払い方法選択機能に関するテスト
 */
class PaymentMethodSelectionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 小計計算画面で支払い方法の変更が即時反映されることをテスト
     *
     * @return void
     */
    public function test_payment_method_selection_is_reflected_in_subtotal_view()
    {
        // シーダーを実行してデータをセットアップし、ユーザーにログインする
        $this->seed(UsersTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);
        $user = User::find(2);
        $this->actingAs($user);

        // 購入者と出品者が異なる商品を取得
        $item = Item::first();

        // 支払い方法の選択肢
        $paymentMethod = 'カード払い';

        // Act: 支払い方法をセッションに保存する
        session(['payment_method' => 'カード払い']);

        // Act: 支払い方法を選択するリクエストをシミュレートする
        $response = $this->get(route('purchase.show', $item->id), [
            'payment_method' => 'カード払い',
        ]);

        // リクエストのステータスコードが200であることを確認
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('payment_method', 'カード払い');

        // 選択された支払い方法がビューに正しく反映されていることを確認する
        $viewResponse = $this->get(route('purchase.show', ['item_id' => $item->id]));
        $viewResponse->assertSee($paymentMethod);

        // purchase-confirmation__tdに支払い方法が表示されていることを確認する
        $viewResponse->assertSeeInOrder(['<td class="purchase-confirmation__td" id="payment-confirmation">', $paymentMethod], false);
    }
}

