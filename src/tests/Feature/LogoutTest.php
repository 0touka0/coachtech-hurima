<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ログアウトが成功することをテスト
     */
    public function test_user_can_logout()
    {
        /**
         * ユーザーを作成してログインする
         *  @var \App\Models\User $user
         */
        $user = User::factory()->create();

        // ユーザーをログインさせる
        $this->actingAs($user);

        // ログアウト処理を実行する（POSTリクエストを送る）
        $response = $this->post('/logout');

        // リダイレクトが成功するか確認（ログインページへリダイレクトする想定）
        $response->assertRedirect('/');

        // ユーザーがログアウトされているか確認
        $this->assertGuest(); // ユーザーがログアウトされているか確認するメソッド
    }
}
