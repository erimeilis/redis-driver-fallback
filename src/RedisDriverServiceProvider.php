<?php

namespace Redshoes\RedisDriverFallback;
use Illuminate\Cache\CacheServiceProvider;

class RedisDriverServiceProvider extends CacheServiceProvider
{
    /**
     * @package redshoes/redis-driver-fallback
     *
     * @author Eri Meilis <eri@redshoes.pro> fork from Paulo De Iovanna <paulodeiovanna@gmail.com>
     */
    protected $defer = false;
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/config.php', 'redis-driver-fallback.php'
        );

        $this->app->singleton('cache', function ($app) {
            return new RedisDriverFallback($app);
        });

        $this->app->singleton('cache.store', function ($app) {
            return $app['cache']->driver();
        });

        $this->app->singleton('memcached.connector', function () {
            return new MemcachedConnector;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/config.php' => config_path('redis-driver-fallback.php'),
            __DIR__.'/mail' => resource_path('views/redshoes/redis-driver-fallback-email-template'),
        ]);
        $this->loadViewsFrom(__DIR__.'/mail', 'RedisDriverFallback');
    }
}
