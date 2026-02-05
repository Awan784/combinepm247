<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

class DropboxServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Dropbox::class, function ($app) {
            $dropboxApp = new DropboxApp(
                env('DROPBOX_APP_KEY'),
                env('DROPBOX_APP_SECRET'),
                env('DROPBOX_ACCESS_TOKEN')
            );

            return new Dropbox($dropboxApp);
        });
    }

    public function boot()
    {
        // No additional code is needed here for now
    }
}
