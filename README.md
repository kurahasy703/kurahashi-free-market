# KURAHASHI-free-market

## フリマアプリ

商品を出品・購入できるフリーマーケットアプリです。

会員登録、ログイン、メール認証、商品検索、マイリスト、コメント、
商品出品、プロフィール編集、配送先変更、Stripe決済などの機能を
実装しています。

## 環境構築

### Dockerビルド

1. リポジトリをクローンします。

```bash
git clone git@github.com:kurahasy703/kurahashi-free-market.git
```

2. プロジェクトディレクトリへ移動します。

```bash
cd kurahashi-free-market
```

3. Docker Desktopを起動します。

4. Dockerコンテナを作成・起動します。

```bash
docker compose up -d --build
```

環境によっては、次のコマンドを使用します。

```bash
docker-compose up -d --build
```

## Laravel環境構築

1. PHPコンテナに入ります。

```bash
docker compose exec php bash
```

2. Composerパッケージをインストールします。

```bash
composer install
```

3. `.env` ファイルを作成します。

```bash
cp .env.example .env
```

4. `.env` のデータベース設定を次のように変更します。

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

5. アプリケーションキーを作成します。

```bash
php artisan key:generate
```

6. マイグレーションを実行します。

```bash
php artisan migrate
```

7. シーディングを実行します。

```bash
php artisan db:seed
```

マイグレーションとシーディングを同時に実行する場合は、次のコマンドを使用します。

```bash
php artisan migrate:fresh --seed
```

8. ストレージのシンボリックリンクを作成します。

```bash
php artisan storage:link
```

## フロントエンド環境構築

PHPコンテナを終了します。

```bash
exit
```

Laravelプロジェクトがある `src` ディレクトリへ移動します。

```bash
cd src
```

Node.jsパッケージをインストールします。

```bash
npm install
```

CSSとJavaScriptをビルドします。

```bash
npm run development
```

## Stripe設定

Stripe決済を使用するため、Stripeでテスト用APIキーを取得し、
`.env` に設定します。

```env
STRIPE_KEY=Stripeの公開可能キー
STRIPE_SECRET=Stripeのシークレットキー
```

Stripeのキーを設定した後、設定キャッシュを削除します。

```bash
php artisan config:clear
```

## メール認証設定

メール認証メールの送受信確認にはMailHogを使用します。

`.env` のメール設定を次のように変更します。

```env
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=test@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

MailHogは次のURLから確認できます。

- http://localhost:8025

## テスト環境

Feature Testを実行するため、テスト用データベースを作成します。

MySQLコンテナへ入ります。

```bash
docker compose exec mysql bash
```

MySQLへログインします。

```bash
mysql -u root -p
```

テスト用データベースを作成し、Laravelのデータベースユーザーへ権限を付与します。

```sql
CREATE DATABASE laravel_test;
GRANT ALL PRIVILEGES ON laravel_test.* TO 'laravel_user'@'%';
FLUSH PRIVILEGES;
EXIT;
```

MySQLコンテナを終了します。

```bash
exit
```

PHPコンテナへ入ります。

```bash
docker compose exec php bash
```

`.env.testing` を作成します。

```bash
cp .env.example .env.testing
```

`.env.testing` のデータベース設定を次のように変更します。

```env
APP_ENV=testing
APP_KEY=
APP_KEY は後述の php artisan key:generate --env=testing で生成します。

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_test
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

STRIPE_KEY=pk_test_dummy
STRIPE_SECRET=sk_test_dummy
```

テスト環境用のアプリケーションキーを作成します。

```bash
php artisan key:generate --env=testing
```

テスト用データベースのマイグレーションを実行します。

```bash
php artisan migrate:fresh --env=testing
```

すべてのテストを実行します。

```bash
php artisan test
```

`.env` と `.env.testing` に設定したアプリケーションキーや
Stripe APIキーなどの秘密情報は、GitHubへコミットしないでください。

## エラーが発生した場合

次のようなエラーが発生した場合、

```text
The stream or file could not be opened
```

ストレージとキャッシュディレクトリの権限を変更します。

```bash
chmod -R 777 storage bootstrap/cache
```

設定やルートのキャッシュが原因と考えられる場合は、次のコマンドを実行します。

```bash
php artisan optimize:clear
```

## 使用技術

- PHP 8.1
- Laravel 8.83
- Laravel Fortify
- Laravel Mix 6
- MySQL 8.0.26
- nginx 1.21.1
- Docker
- Docker Compose
- Stripe
- MailHog

## ER図

![ER図](ER.drawio.png)

## URL

- 商品一覧画面：http://localhost/
- 会員登録画面：http://localhost/register
- ログイン画面：http://localhost/login
- 商品出品画面：http://localhost/sell
- マイページ：http://localhost/mypage
- 商品検索：http://localhost/?keyword=検索キーワード
- phpMyAdmin：http://localhost:8080
- MailHog：http://localhost:8025
