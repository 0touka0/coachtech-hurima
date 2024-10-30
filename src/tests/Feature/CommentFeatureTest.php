<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Database\Seeders\ItemsTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * コメント送信機能に関するテスト
 */
class CommentFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ログイン済みのユーザーはコメントを送信できることをテスト
     *
     * @return void
     */
    public function test_logged_in_user_can_send_comment()
    {
        // シーダーを実行してデータをセットアップし、ユーザーにログインする
        $this->seed(UsersTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);
        $user = User::find(2);
        $this->actingAs($user);

        // コメントを入力する
        $item = Item::first();
        $comment = 'これはテストコメントです。';

        // コメントボタンを押す
        $response = $this->post(route('comment.send', $item->id), [
            'comment' => $comment,
        ]);

        // コメントが保存され、コメント数が増加することを確認
        $response->assertStatus(302); // 成功後のリダイレクトを確認
        $this->assertDatabaseHas('item_comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => $comment,
        ]);
    }

    /**
     * ログインしていないユーザーはコメントを送信できないことをテスト
     *
     * @return void
     */
    public function test_guest_user_cannot_send_comment()
    {
        // シーダーを実行してデータをセットアップ
        $this->seed(ItemsTableSeeder::class);
        $item = Item::first();
        $comment = 'これはテストコメントです。';

        // コメントボタンを押す
        $response = $this->post(route('comment.send', $item->id), [
            'comment' => $comment,
        ]);

        // コメントが保存されないことを確認
        $response->assertStatus(302); // 未ログイン時はリダイレクトされることを確認
        $this->assertDatabaseMissing('item_comments', [
            'item_id' => $item->id,
            'comment' => $comment,
        ]);
    }

    /**
     * コメントが入力されていない場合、バリデーションメッセージが表示されることをテスト
     *
     * @return void
     */
    public function test_validation_message_is_displayed_for_empty_comment()
    {
        // シーダーを実行してデータをセットアップし、ユーザーにログインする
        $this->seed(UsersTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // ユーザーを取得してログイン
        $user = User::first();
        $this->actingAs($user);

        // コメントボタンを押す（コメントは空）
        $item = Item::first();
        $response = $this->post(route('comment.send', $item->id), [
            'comment' => '',
        ]);

        // バリデーションメッセージが表示されることを確認
        $response->assertSessionHasErrors(['comment']);
    }

    /**
     * コメントが255文字以上の場合、バリデーションメッセージが表示されることをテスト
     *
     * @return void
     */
    public function test_validation_message_is_displayed_for_comment_over_255_characters()
    {
        // シーダーを実行してデータをセットアップし、ユーザーにログインする
        $this->seed(UsersTableSeeder::class);
        $this->seed(ItemsTableSeeder::class);

        // ユーザーを取得してログイン
        $user = User::first();
        $this->actingAs($user);

        // 256文字以上のコメントを入力する
        $item = Item::first();
        $longComment = str_repeat('あ', 256);

        // コメントボタンを押す
        $response = $this->post(route('comment.send', $item->id), [
            'comment' => $longComment,
        ]);

        // バリデーションメッセージが表示されることを確認
        $response->assertSessionHasErrors(['comment']);
    }
}
