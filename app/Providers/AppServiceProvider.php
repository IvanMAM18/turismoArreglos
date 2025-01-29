<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Glide\Server;
use League\Glide\ServerFactory;
use League\Glide\Responses\LaravelResponseFactory;

use Illuminate\Support\Facades\URL;
use Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerGlide();
    }

    protected function registerGlide()
    {
        $this->app->bind(Server::class, function ($app) {
            return ServerFactory::create([
                'source' => Storage::getDriver(),
                'cache' => Storage::getDriver(),
                'cache_path_prefix' => '.glide-cache',
                'base_url' => 'img',
                'response' => new LaravelResponseFactory(app('request'))
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        URL::forceRootUrl(Config::get('app.url'));
        URL::forceScheme('https');
	\Illuminate\Pagination\AbstractPaginator::currentPathResolver(function () {
            
           $url = app('url');
           return $url->current();
        });
	
    }
}
