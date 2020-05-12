<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// APIのURL以外のアクセスに関してはindex.blade.phpファイルを返すようにする
// URLに対する画面の描画はVueRouterで管理する
Route::get('/{any?}', fn() => view('index'))->where('any', '.+');

// {any?}で任意のパラメータを受け入れる
// whereメソッドで正規表現を用いてanyパラメータの文字列形式を定義
// .+で任意の文字が何でも良いと言う指定
// これによってすべてのURLでindex.blade.phpを返すことができる