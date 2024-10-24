<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginValidationTest extends TestCase
{
    use RefreshDatabase; // テストごとにデータベースをリセット

    /**
     * メールアドレスが入力されていない場合のテスト
     */
    public function test_email_is_required()
    {
        $response = $this->post('/login', [
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    /**
     * パスワードが入力されていない場合のテスト
     */
    public function test_password_is_required()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    /**
     * 入力情報が間違っている場合のテスト
     */
    public function test_invalid_credentials()
    {
        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        // セッションエラーの確認
        $response->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません。']);
    }

    /**
     * 正しい情報が入力された場合のテスト(住所が未登録の場合)
     */
    public function test_successful_login_with_empty_address()
    {
        // 住所が未登録のユーザーを作成
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'address' => null, // 住所が未登録
        ]);

        // 正しい情報でログインを試みる
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // プロフィール編集ページへのリダイレクトを確認
        $response->assertRedirect(route('profile.edit'));

        $this->assertAuthenticatedAs($user); // ユーザーがログインされているか確認
    }

    /**
     * 正しい情報が入力された場合のテスト(住所が登録済みの場合)
     */
    public function test_successful_login_with_address()
    {
        // 住所が登録されているユーザーを作成
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'address' => '123 Main St', // 住所が登録済み
        ]);

        // 正しい情報でログインを試みる
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // 通常のログイン処理の確認（リダイレクト先をアプリに合わせて修正）
        $response->assertRedirect('/');

        $this->assertAuthenticatedAs($user); // ユーザーがログインされているか確認
    }
}
