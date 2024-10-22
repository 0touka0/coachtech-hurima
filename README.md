# coachtech-hurima

## 環境構築
Dockerビルド

1. `git clone git@github.com:0touka0/coachtech-hurima.git`
2. `docker-compose up -d --build`

※MySQLは、OSによって起動しない場合があるのでそれぞれのPCに合わせて、docker-compose.ymlファイルを編集してください。

Laravel環境構築

1. `docker-compose exec php bash`
2. `composer install`
3. `.env.example`ファイルから`.env`を作成し、環境変数を変更<br>
- DBの設定を行って下さい
- セッションの値をDBに保存しているので`SESSION_DRIVER=database`に変更してください。
- `MAIL_FROM_ADDRESS`に`mailtest@example.com`等のような送信側のメールアドレス設定を行ってください。
4. `php artisan key:generate`
5. `php artisan migrate`
6. `php artisan db:seed`

## 機能確認用ユーザー
- ユーザー名：出品者
- メールアドレス：seller@example.com
- パスワード：sellerexample

- ユーザー名：ゲスト
- メールアドレス：guest@example.com
- パスワード：guestexample

## 注意事項
- メール認証の時間が経過してしまった場合、ログインを行うと再度メールが送信されます。
- `購入する`ボタンを押下した際に決済ページに移動、商品は購入されたことになり商品詳細画面の`購入手続きへ`ボタンを使用できなくしています。

## 使用技術(実行環境)
- PHP 8.3.6
- Laravel 8
- Mysql 8.0.26
- nginx 1.21.1

## ER図
- ![フリマアプリER図](https://github.com/user-attachments/assets/cc4239f5-70b7-4a45-b3c1-e995bfc6c3a8)

## アプリケーションURL
- 商品一覧画面　 : http://localhost/
- 会員登録画面　 : http://localhost/register
- ログイン画面　 : http://localhost/login
- 商品詳細画面　 : http://localhost/item/:item_id
- 商品購入画面　 : http://localhost/purchase/:item_id
- 住所変更ページ : http://localhost/purchase/address/:item_id
- 商品出品画面　 : http://localhost/sell
- プロフィール画面　　 : http://localhost/mypage
- プロフィール編集画面 : http://localhost/mypage/profile
- mailhog
- http://localhost:8025
- phpMyAdmin
- http://localhost:8080