<?php

require_once __DIR__. "/../vendor/autoload.php";

use Silex\Application;

$app = new Application();
$app->get("/hello/{name}", function($name) use($app) { return sprintf("Hello %s", $app->escape($name)); });
$app->run();