<?php

$app['debug'] = false;
// application.php config has a default name. Overwrite if desired.
// $app['name'] = 'My Super Duper Loader';

$app->register(new Combo\ComboServiceProvider(), array(
    'combo.basedir' => __DIR__ . "/../../web/assets",
    'combo.maxage'  => 31536000, // 1 Year
    'combo.cache_path' => __DIR__ . "/../cache/assetic",
    'combo.asset_lifetime' => 60 * 60 * 2, // 2 Hours
));

// Http Cache
$app->register(new Silex\Provider\HttpCacheServiceProvider(), array(
    'http_cache.cache_dir' => __DIR__.'/../cache/http',
));

$app->after(function ($request, $response) use ($app) {
    if ($response->isSuccessful()) {
        $response->setCache(array(
            'public' => true,
            'max_age' => $app['combo.maxage'],
        ));

        $response->headers->set('Expires', date('r', strtotime(sprintf("+%s seconds", $app['combo.maxage']))));

    }
});
