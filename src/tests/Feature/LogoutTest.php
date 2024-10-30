<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ログアウトが成功することをテスト
     */
    public function test_user_can_logout()
    {
        // 必要なシーダーを実行して、ダミーデータを作成
        $this->seed(UsersTableSeeder::class);

        // ユーザーをログインさせる
        $user = User::first();
        $this->actingAs($user);

        // ログアウト処理を実行する（POSTリクエストを送る）
        $response = $this->post('/logout');

        // リダイレクトが成功するか確認（ログインページへリダイレクトする想定）
        $response->assertRedirect('/');

        // ユーザーがログアウトされているか確認
        $this->assertGuest(); // ユーザーがログアウトされているか確認するメソッド
    }
}
