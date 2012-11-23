<?php

$app['debug'] = true;

// Combo configuration
$app['combo.basedir']               = __DIR__ . "/../../web/assets";
$app['combo.response.maxage']       = 31536000; // 1 Year
$app['combo.cache.path']            = __DIR__ . "/../cache/assetic";
// This value won't matter much if using HttpCache
$app['combo.cache.asset_lifetime']  = 60 * 60 * 2; // 2 hours

$app['combo.filters.js'] = array(
    new Assetic\Filter\CoffeeScriptFilter('/usr/local/share/npm/bin/coffee')
);

// Http Cache
$app->register(new Silex\Provider\HttpCacheServiceProvider(), array(
    'http_cache.cache_dir' => __DIR__.'/../cache/http',
));

$app->after(function ($request, $response) use ($app) {
    if ($response->isSuccessful()) {
        $response->setCache(array(
            'public' => true,
            'max_age' => $app['combo.response.maxage'],
        ));

        $response->headers->set('Expires', date('r', strtotime(sprintf("+%s seconds", $app['combo.response.maxage']))));

    }
});
