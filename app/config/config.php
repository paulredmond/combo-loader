<?php

$app['debug'] = false;

// Combo configuration
$app['combo.basedir']  = __DIR__ . "/../../web/assets";
$app['combo.maxage']   = 31536000; // 1 Year

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
