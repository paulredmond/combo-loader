<?php

namespace ComboLoader;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;

class ComboLoader
{
    /**
     * @var ComboHandler
     */
    private $handler;

    public function __construct(ComboHandler $handler)
    {
        $this->handler = $handler;
    }

    public function handle()
    {
        return new Response($this->handler->dump());
    }
}