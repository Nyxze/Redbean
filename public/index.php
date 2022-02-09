<?php

use DI\Bridge\Slim\Bridge;
use Middlewares\Whoops;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RedBeanPHP\R as R;
use slimApp\controllers\HomeController;
use slimApp\controllers\PersonController;
use slimApp\controllers\PersonWebController;
use Slim\Interfaces\RouteCollectorProxyInterface;
use slimApp\middlewares\ApiKey;
use Slim\Views\Twig;

require_once "../vendor/autoload.php";
$dev = true;
$dsn = "mysql:host=localhost;dbname=formation_cda_2022;charset=utf8";
$user = "root";
$pass = "";
R::setup($dsn, $user, $pass);
$builder = new \DI\ContainerBuilder();
$container = $builder->build();

$container->set("Twig",function (){

    return Twig::create("../views");
});

$middle = function (RequestInterface $request, RequestHandlerInterface $handler) {

    $response = $handler->handle($request);
    $response->getBody()->write(" Middleware?");
    return $response;
};

$middle2 = function (RequestInterface $request, RequestHandlerInterface $handler) {

    $response = $handler->handle($request);
    $response->getBody()->write(" End?");
    return $response;
};

$app = Bridge::create($container);
if ($dev) {

    $app->add(Whoops::class);
}
// $app->add(ErrorMiddleware::class);
$app->add($middle);
$app->add($middle2);
$apiKey = new ApiKey("12346");

$app->group("/api/person", function (RouteCollectorProxyInterface $group) {
    $group->get("/insert/{firstName}&{lastName}", [PersonController::class, "insert"]);
    $group->get("/all", [PersonController::class, "showAll"]);
    $group->get("/{id}", [PersonController::class, "showOne"]);

})->add($apiKey);

$app->group("/person", function (RouteCollectorProxyInterface $group) {
    $group->get("/", [PersonWebController::class, "showAll"]);
    $group->get("/form", [PersonWebController::class, "showForm"]);
    $group->get("/{id}", [PersonWebController::class, "showOne"]);
    $group->post("/form",[PersonWebController::class, "processForm"]);

});


$app->get("/hello[/{name}]", [HomeController::class, "hello"]);

$app->run();
