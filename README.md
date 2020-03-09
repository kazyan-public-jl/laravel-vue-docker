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