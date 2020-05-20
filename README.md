# Laravel,Vue,Docker 環境で遊ぼう

以下のサイトを参考に、モダンなWeb開発環境を構築するまでのリポジトリ

**参考サイト**

- [【Laravel＋Vue+axios+docker】アプリ開発環境の作り方を公開します](https://kumatetsublog.com/shoot/blog/laravel-vue-axios-docker)

## 環境構築

```sh
# composer のインストール
$ brew install composer

# Laravel のインストール
$ composer global require laravel/installer
$ echo 'export PATH="$PATH:$HOME/.composer/vendor/bin"' >> ~/.bash_profile

# Laravelプロジェクトの作成
$ composer create-project laravel/laravel laravel-vue-docker --prefer-dist

# git リポジトリへ接続
$ git init
$ git add .
$ git remote add origin https://github.com/kazyan-public-jl/laravel-vue-docker.git
$ git push -u origin master

# 必要なパッケージ（Vue, axios, Redis）をインストール
$ npm install vue axios
$ npm install
$ composer require predis/predis
$ composer install

# DBとキャッシュを起動（Dockerを利用）
## Dockerのインストール
### 公式サイトから Docker.dmg をダウンロード＆実行してインストール
### 一度 Docker にログインしておく.
$ docker login
## Dockerの設定ファイルを編集
$ vi docker-compose.yml
## Dockerコンテナを起動
$ docker-compose up -d

# .env.local を作成
$ cp .env.example .env.local
# DBの設定を docker-compose.yml と同じに修正
$ vi .env.local
```

続きは画面要素を作る

## JSとSASS、ビルドファイルを作成

- 画面表示に必要な scss, js, blade.php を追加
- アプリのCSRFチェックを追加

```sh
# いくつかのファイルを追加後、フロントエンド要素のコンパイルを実行
$ npm run dev

# laravelが読み込む .env を生成
$ cp .env.local .env

# アプリキーを作成, .env の APP_KEYに反映
$ php artisan key:generate
# キャッシュを削除して.envファイルを反映
$ php artisan config:clear
# サーバーを起動
$ php artisan serve
Laravel development server started: http://127.0.0.1:8000
```

- `https://localhost:3000` にアクセスして次のような結果が出ていればOK
```
Hello, World
{{ vueData }}
```

## DBマイグレーション

- [【初心者向けLaravel講座】マイグレーションの手順を解説します](https://kumatetsublog.com/shoot/blog/laravel-migration)

を元にDBを作成していきます

```sh
# migrateファイルを作成
php artisan make:migration Tasks

# model, controller, factory, resource を自動生成します
php artisan make:model Task -a
```

### マイグレーションファイルの編集

マイグレーションファイルの詳細は公式がわかりやすい
- [Laravel 5.5 データベース：マイグレーション](https://readouble.com/laravel/5.5/ja/migrations.html)

どんな値をもつテーブルを作成したいかをよく考えて作ること

### マイグレーション実行

```sh
# migration ファイルを作成する
php artisan make:migration Tasks
# 未実行の migration を実行する
php artisan migrate
# migration の実行状態を確認する
php artisan migrate:status
# 最後に実行した migration を元に戻す
php artisan migrate:rollback
# 最後に実行した migration を元に戻す
php artisan migrate:rollback --path 20200318_create_tasks_table
# 全てrollbackしてから再び全て migration する
php artisan migrate:refresh
```

### Seeder 作成

DBに特定の値を insert するクエリを記述しておく

DatabaseSeeder.php には全ての Seeder の呼び出しを登録しておくと後が楽です
```php
public function run()
{
    $this->call(TasksSeeder::class);
}
```

個別のSeederでは直接DBのクエリを実行する方法と、ファクトリモデルを使う方法のいずれかで初期データを作成します

TasksSeeder.php
```php
//何件でもいいですが適当に3件作りました。
factory(App\Tasks::class, 3)->create();
```

random_int などで乱数を使うこともできるため、
大量のランダムなデータを作る場合は、ファクトリモデルを使うと便利です

TasksFactory.php
```php
$factory->define(Tasks::class, function (Faker $faker) {
    return [
        "name"=> "サンプルタスクです",
        "status"=> false,
        "order"=> random_int(1, 100)
    ];
});
```

### Seeder 実行

```sh
# DatabaseSeeder だけ実行する場合
php artisan db:seed
# 特定の Seeder だけ実行する場合
php artisan db:seed --class TasksSeeder
# DBを完全に初期化して最初からやり直して Seeder も実行する場合
php artisan migrate:refresh --seed
```

以上で、データベースの作成から初期サンプルデータの生成までが完了しました

続いて、DBへのデータ操作APIを作っていきます

## データベース操作のCRUD API の作成

apiの追加は大まかに次の作業に沿って行います

1. api.php に追加 : [Laravel ルーティング](https://readouble.com/laravel/5.7/ja/routing.html)
2. 対応する関数内で CRUD に即した処理を行う **(本項の解説ポイント)**
3. 更新したデータを JSON 形式で返す(対応したもののみを返すか、対応後の全データを返すかは実装次第)
4. そして返されたデータを元に画面に反映する

### データの追加 create

Taskモデルインスタンスを生成(`new`)し、`save()` することでレコードを追加できます。
```
$task = new Tasks;
$task->save();
```

### データの読み込み read

データの取得方法は色々あるのでドキュメントを読んだ方が良いですね。
多すぎて一度に覚えられないと思うので、何度か読み返しましょう。
```
# id で一件だけインスタンスを取得したい場合
$task = Tasks::find({id});

# モデルインスタンスごと全て取得したい場合
$allTasks = Tasks::all();

# 一部のレコード情報だけ取得したい場合
$someTasks = Tasks::where('status', true)->get();
```

### データの更新 update

モデルインスタンスのパラメータへ値を代入したあとで `save()` すれば更新されます
```
$task['order'] = 10;
$task->save();
```

### データの削除 delete

レコードを削除したい場合は `delete()` です
```
$task->delete();
```
