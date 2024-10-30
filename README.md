# coachtech-hurima

## 環境構築
Dockerビルド

1. `$ git clone git@github.com:0touka0/coachtech-hurima.git`
2. `$ docker-compose up -d --build`

※MySQLは、OSによって起動しない場合があるのでそれぞれのPCに合わせて、docker-compose.ymlファイルを編集してください。

Laravel環境構築

1. `$ docker-compose exec php bash`
2. `$ composer install`
3. `$ cp .env.example .env`<br>
4. `.env` の設定を行います：
- **データベース設定**
- `docker-compose.yml`に記載されているデータベース設定を参考にして、`.env` ファイルのデータベース設定を行ってください。
- **セッション設定**
- `SESSION_DRIVER=file` を `SESSION_DRIVER=database` に変更してください。
- **送信側のメールアドレス設定**
- `MAIL_FROM_ADDRESS=null` を `mailtest@example.com` などのメールアドレスに変更してください。
5. `$ php artisan key:generate`
6. `$ php artisan migrate --seed`

## 機能確認用ユーザー
- ユーザー名：出品者
- メールアドレス：seller@example.com
- パスワード：sellerexample

- ユーザー名：ゲスト
- メールアドレス：guest@example.com
- パスワード：guestexample

## 注意事項
- メール認証の再送信<br>
メール認証の有効期限が切れてしまった場合は、再度ログイン情報を入力しボタンを押下することで新しい認証メールが送信されます。
- 認証完了後の画面表示<br>
再送信された認証メールで認証が完了すると、自動的にログインされ商品一覧画面が表示されます。
- 購入手続きの進行<br>
商品購入画面の「購入する」ボタンを押すと、Stripeの決済ページに移動し商品が購入済みとなります。<br>これにより、商品詳細画面の「購入手続きへ」ボタンは使用できなくなります。
- 決済ページからの遷移先<br>
Stripeの決済ページから「戻る」ボタンを使用して移動した場合は、商品詳細画面に戻ります。<br>一方、テスト購入を行った場合はマイページに移動します。
- 検索条件のリセット<br>
検索ボックスに入力されたテキストはリセットしない限り残ります。<br>すべての商品を再度表示したい場合は、検索条件をリセットしてください。

## 使用技術(実行環境)
- PHP 8.3.6
- Laravel 8
- Mysql 8.0.26
- nginx 1.21.1
- mailhog
- phpMyAdmin

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
- mailhog    : http://localhost:8025
- phpMyAdmin : http://localhost:8080