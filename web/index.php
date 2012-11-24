<?php

$app = require_once __DIR__ . "/../app/config/bootstrap.php";

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app->get('/combo', function () use ($app) {
    /** @var $handler \Combo\ComboHandler */
    $handler = $app['combo.handler'];
    $loader  = $handler->getLoader();
    foreach (explode('&', $app['request']->server->get('QUERY_STRING')) as $module) {
        $loader->addModule($module);
    }

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