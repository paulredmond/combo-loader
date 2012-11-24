<?php

require __DIR__ . "/../vendor/autoload.php";

$app = new Silex\Application();
// App data, such as version info.
require __DIR__ . "/config/application.php";
require __DIR__ . "/config/config.php";

return $app;