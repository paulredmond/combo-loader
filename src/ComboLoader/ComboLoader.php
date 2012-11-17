<?php

namespace ComboLoader;

use Symfony\Component\HttpFoundation\Request;

class ComboLoader
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    private $modules = array();

    public static function createFromArray(array $modules = array())
    {
        $class = new static();

        foreach ($modules as $module) {
            $class->addModule($module);
        }

        return $class;
    }

    public function handle()
    {
        throw new \ComboLoader\Exception\AccessDeniedException('?' . implode('&', $this->getModules()));
    }

    public function getModules()
    {
        return $this->modules;
    }

    public function addModule($module)
    {
        $this->modules[] = $module;

        return $this;
    }
}