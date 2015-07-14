<?php

require_once __DIR__. "/../vendor/autoload.php";

use Ihsw\TwitterClone\Application;
use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse;

$twig = new \Twig_Environment(new \Twig_Loader_Filesystem("../templates"), [
    // "cache" => "../cache"
    "cache" => false
]);
$app = new Application();

$app["debug"] = true;

$app->before(function (Request $request, Application $app) use($twig) {
    $app["twig"] = $twig;

    if ($request->getRequestUri() !== "/login") {
        return new RedirectResponse("/login");
    }
});
$app->get("/", function () { return "Hello, world!"; });
$app->get("/login", function (Application $app) {
    return $app["twig"]->render("login.html.twig");
});
$app->run();