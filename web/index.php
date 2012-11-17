<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ComboLoader\ComboLoader;
use ComboLoader\ComboHandler;

$app = new Silex\Application();
$app['debug'] = true;

// @todo add a proper service later
$app['combo.basedir'] = __DIR__ . "/assets";

$app->get('/combo', function () use ($app) {
    $comboLoader = new ComboLoader(new ComboHandler(
        $app['combo.basedir'],
        explode('&', $app['request']->server->get('QUERY_STRING'))
    ));

    return $comboLoader->handle();
});

//$app->error(function (\ComboLoader\Exception\AccessDeniedException $e) {
//    return new Response($e->getMessage(), 403);
//});

$app->run();