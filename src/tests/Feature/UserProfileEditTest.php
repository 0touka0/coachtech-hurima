<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/**
 * ユーザー情報変更に関するテスト
 */
class UserProfileEditTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ユーザーがプロフィール編集ページを開いた際に、変更項目が初期値として正しく表示されることをテスト
     *
     * @return void
     */
    public function test_user_profile_edit_page_displays_correct_initial_values()
    {
        // 必要なシーダーを実行して、ダミーデータを作成
        $this->seed(UsersTableSeeder::class);

        // ユーザーを取得してログイン
        $user = User::first();
        $this->actingAs($user);

        // ユーザーのプロフィール画像を作成して保存
        Storage::fake('public');
        $file = UploadedFile::fake()->image('profile.png');
        $filePath = $file->store('profile_images', 'public');

        // ユーザーデータに画像パスを設定
        $user->image_path = Storage::url($filePath);
        $user->save();

        // プロフィール編集ページにアクセス
        $response = $this->get(route('profile.edit'));

        // ステータスコードが200（正常）であることを確認
        $response->assertStatus(200);

        // プロフィール画像が表示されているか確認
        $response->assertSee($user->image_path);

        // ユーザー名が初期値として表示されているか確認
        $response->assertSee('value="' . $user->name . '"', false);

        // 郵便番号が初期値として表示されているか確認
        $response->assertSee('value="' . $user->postal_code . '"', false);

        // 住所が初期値として表示されているか確認
        $response->assertSee($user->address);
    }
}
