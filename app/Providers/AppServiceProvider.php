<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema; // 追加
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // マイグレーションにより生成されるデフォルトのインデックス用文字列長を
        // 明示的に設定する必要がる（MySQLは5.7未満で必要な設定）
        Schema::defaultStringLength(191);
    }
}
