<?php

namespace Combo;

use Combo\Exception;

use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Asset\AssetCache;
use Assetic\Cache\FilesystemCache;
use Assetic\Cache\ExpiringCache;

class ComboLoader
{
    /**
     * @var AssetCollection
     */
    private $collection;

    /**
     * Copy of the dumped content.
     *
     * @var String
     */
    private $content;

    /**
     * Detected file extension.
     *
     * Extension will be used to determine the Content-Type of the response.
     *
     * @var
     */
    private $extension;

    /**
     * List of valid extensions and their content types.
     *
     * @var array
     */
    private $contentTypes = array(
        'js'   => 'application/javascript',
        'css'  => 'text/css'
    );

    private $debug = false;

    private $cachePath = false;

    /**
     * Amount of time in seconds an asset will be cached.
     *
     * @var int
     */
    private $expires = 0;

    public function __construct($basedir, array $modules = array(), $cachePath, $expires = 0, $debug = false)
    {
        if (!is_dir($basedir)) {
            throw new \LogicException(sprintf('The ComboLoader basedir "%s" does not exist.', $basedir));
        }

        $this->debug       = $debug;
        $this->cachePath   = $cachePath;
        $this->expires     = $expires;
        $this->basedir     = $basedir;
        $this->collection  = new AssetCollection();

        foreach ($modules as $module) {
            $ext = pathinfo($module, PATHINFO_EXTENSION);
            if (null === $this->extension) {
                $this->setExtension($ext);
            } else if ($ext !== $this->extension) {
                throw new Exception\RuntimeException('Multiple extension types cannot be combo-loaded.', $this);
            }
            $this->addModule($module);
        }
    }

    public function dump()
    {
        if (!isset($this->content)) {
            $this->content = $this->collection->dump();
        }

        return $this->content;
    }

    public function addModule($module)
    {
        if (!$this->isSubDir($module)) {
            throw new Exception\AccessDeniedException(
                sprintf('Module path "%s" is not a subdirectory of the basepath.', $module),
                $this
            );
        }

        $path = $this->basedir . '/' . $module;
        if (is_file($path)) {
            $this->collection->add($this->createFileAsset($path));
        }

        return $this;
    }

    public function setExtension($extension)
    {
        if (!$this->contentTypeSupported($extension)) {
            throw new Exception\AccessDeniedException(sprintf('The extension ".%s" is not supported.', $extension),
                $this);
        }

        $this->extension = $extension;
    }

    public function contentTypeSupported($extension)
    {
        return isset($this->contentTypes[$extension]);
    }

    public function getContentType()
    {
        if (!isset($this->extension) || !$this->contentTypeSupported($this->extension)) {
            return false;
        }

        return $this->contentTypes[$this->extension];
    }

    private function createFileAsset($path)
    {
        $asset = new FileAsset($path);
        if ($this->debug === true) {
            return $asset;
        }

        return new AssetCache(
            $asset,
            new ExpiringCache(new FileSystemCache($this->cachePath), $this->expires)
        );
    }

    /**
     * Ensures that a module is located within the base path defined.
     *
     * This helps avoid unintended access to the file system above the webroot.
     *
     * @param $module String Path to module.
     * @return bool
     */
    private function isSubDir($module)
    {
        $base = realpath($this->basedir);
        $dir  = dirname($base . '/' . $module);
        $real = substr(realpath($dir), 0, strlen($base));

        if ($real === $base) {
            return true;
        }

        return false;
    }
}