<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ComboLoader\ComboLoader;

$app = new Silex\Application();

$app->get('/combo', function () use ($app) {
    $comboLoader = ComboLoader::createFromArray(
        explode('&', $app['request']->server->get('QUERY_STRING'))
    );

    return $comboLoader->handle();
});

$app->error(function (\ComboLoader\Exception\AccessDeniedException $e) {
    return new Response($e->getMessage(), 403);
});

$app->run();