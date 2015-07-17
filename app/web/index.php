<?php

require_once __DIR__. "/../vendor/autoload.php";

use Ihsw\TwitterClone\Application;
use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse;
use Silex\Provider\SecurityServiceProvider,
    Silex\Provider\SessionServiceProvider,
    Silex\Provider\TwigServiceProvider,
    Silex\Provider\UrlGeneratorServiceProvider;

// starting it up
$app = new Application();
$app["pdo"] = new \PDO("mysql:host=localhost;dbname=silex_twitter_clone", "root", "");

// service setup
$app->register(new SecurityServiceProvider())
    ->register(new SessionServiceProvider())
    ->register(new TwigServiceProvider(), ["twig.path" => "../templates", "twig.options" => ["cache" => "../cache"]])
    ->register(new UrlGeneratorServiceProvider());

// firewall definitions
$app["security.firewalls"] = [
    "login" => [
        "pattern" => "^/login$",
        "anonymous" => true
    ],
    "secured" => [
        "pattern" => "^.*$",
        "form" => ["login_path" => "/login", "check_path" => "/login_check"],
        "logout" => ["logout_path" => "/logout"],
        "users" => [
            // raw password is foo
            "admin" => [
                "ROLE_ADMIN",
                "5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg=="
            ],
        ]
    ]
];

// route setup
$app->get("/", function (Application $app) {
    return $app["twig"]->render("home.html.twig", [
        "username" => $app["security.token_storage"]->getToken()->getUser()->getUsername(),
        "posts" => $app->getPosts()
    ]);
})->bind("home");
$app->post("/", function (Application $app, Request $request) {
    $app->createPost($request->get("content"));
    return new RedirectResponse($app["url_generator"]->generate("home"));
})->bind("create");
$app->get("/login", function (Application $app, Request $request) {
    return $app["twig"]->render("login.html.twig", [
        "error" => $app["security.last_error"]($request),
        "last_username" => $app["session"]->get("_security.last_username"),
    ]);
})->bind("login");

// running it out
$app->run();