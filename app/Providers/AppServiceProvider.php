<?php

namespace App\Providers;

use App\Models\ModuleModel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        if (!app()->runningInConsole() && Schema::hasTable('module')) {
            view()->share(["modules" => ModuleModel::all()]);
        }
    }
}
