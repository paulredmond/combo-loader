<?php

namespace Combo;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ComboHandler
{
    /**
     * @var ComboLoader
     */
    private $loader;

    public function __construct(ComboLoader $loader)
    {
        $this->loader = $loader;
    }

    public function respond()
    {
        return $this->createResponse();
    }

    public function getLoader()
    {
        return $this->loader;
    }

    private function createResponse()
    {
        $response = new Response($this->loader->dump());
        $response->headers->set('Content-Type', $this->loader->getContentType());

        return $response;
    }
}