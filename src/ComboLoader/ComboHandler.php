<?php

namespace ComboLoader;

use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;

class ComboHandler
{
    /**
     * @var AssetCollection
     */
    private $collection;

    public function __construct($basedir, array $modules = array())
    {
        if (!is_dir($basedir)) {
            throw new \LogicException(sprintf('The ComboLoader basedir "%s" does not exist.', $basedir));
        }

        $this->basedir    = $basedir;
        $this->collection = new AssetCollection();

        foreach ($modules as $module) {
            $this->addModule($module);
        }
    }

    public function dump()
    {
        return $this->collection->dump();
    }

    public function addModule($module)
    {
        if (!$this->isSubDir($module)) {
            throw new Exception\AccessDeniedException('Module is not a subdirectory of the basepath.');
        }

        $this->collection->add(new FileAsset($this->basedir . '/' . $module));

        return $this;
    }

    private function isSubDir($module)
    {
        $base = $this->basedir;
        $dir  = dirname($base . '/' . $module);
        $real = substr(realpath($dir), 0, strlen($base));

        if ($real === $base) {
            return true;
        }

        return false;
    }
}