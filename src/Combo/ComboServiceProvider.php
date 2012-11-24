<?php

namespace Combo;

use Silex\Application;
use Silex\ServiceProviderInterface;

class ComboServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['combo.loader'] = $app->share(function () use ($app) {
            return new ComboLoader(
                $app['combo.basedir'],
                $app['combo.cache_path'],
                $app['combo.asset_lifetime'],
                $app['debug']
            );
        });

        $app['combo.handler'] = $app->share(function () use ($app) {
            return new ComboHandler($app['combo.loader']);
        });
    }

    public function boot(Application $app)
    {

    }
}