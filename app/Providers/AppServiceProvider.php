<?php

namespace App\Providers;

use Carbon\Carbon;
use Laravel\Passport\Passport;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
//        if (app()->isLocal()) {
//            $this->app->register(\VIACreative\SudoSu\ServiceProvider::class);
//        }
//
//        \API::error(function (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
//            abort(404);
//        });
//
//        \API::error(function (\Illuminate\Auth\Access\AuthorizationException $exception) {
//            abort(403, $exception->getMessage());
//        });
        
        // Passport 的路由
        Passport::routes();
        // access_token 过期时间
        Passport::tokensExpireIn(Carbon::now()->addDays(15));
        // refreshTokens 过期时间
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));

    }
}
