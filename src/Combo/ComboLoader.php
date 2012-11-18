<?php

namespace Combo;

use Combo\Exception;

use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;

class ComboLoader
{
    /**
     * @var AssetCollection
     */
    private $collection;

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

    public function __construct($basedir, array $modules = array())
    {
        if (!is_dir($basedir)) {
            throw new \LogicException(sprintf('The ComboLoader basedir "%s" does not exist.', $basedir));
        }

        $this->basedir    = $basedir;
        $this->collection = new AssetCollection();

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
        return $this->collection->dump();
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
            $this->collection->add(new FileAsset($path));
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
        $base = $this->basedir;
        $dir  = dirname($base . '/' . $module);
        $real = substr(realpath($dir), 0, strlen($base));

        if ($real === $base) {
            return true;
        }

        return false;
    }
}