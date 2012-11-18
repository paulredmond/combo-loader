<?php

namespace Combo\Exception;

use Combo\ComboLoader;

abstract class Exception extends \Exception
{
    /**
     * @var \Combo\ComboLoader
     */
    public $loader;

    public function __construct($message, ComboLoader $loader)
    {
        $this->message = $message;
        $this->loader = $loader;
    }
}