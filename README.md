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
```