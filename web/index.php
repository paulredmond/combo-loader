<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Combo\ComboLoader;
use Combo\ComboHandler;

$app = new Silex\Application();

require_once __DIR__ . "/../app/config/config.php";

$app->get('/combo', function () use ($app) {
    $handler = new ComboHandler(new ComboLoader(
        $app['combo.basedir'],
        explode('&', $app['request']->server->get('QUERY_STRING'))
    ));

    return $handler->respond();
});

$app->error(function (\Combo\Exception\Exception $e) {
    $response = new Response(sprintf('/* %s */', $e->getMessage()), 403);
    $response->headers->set('Content-Type', $e->loader->getContentType());

    return $response;
});

$app->error(function (\InvalidArgumentException $e) {
    $response = new Response(sprintf('/* %s */', $e->getMessage()), 403);

});

Request::trustProxyData();

if ($app['debug'] === true || !isset($app['http_cache'])) {
    $app->run();
} else {
    $app['http_cache']->run();
}