<?php

use RedBeanPHP\R as R;
use slimApp\controllers\PersonController;
use DI\Bridge\Slim\Bridge;


require_once "../vendor/autoload.php";

$dsn = "mysql:host=localhost;dbname=formation_cda_2022;charset=utf8";
$user = "root";
$pass = "";
R::setup($dsn, $user, $pass);
$builder = new \DI\ContainerBuilder();
$container = $builder->build();
$app = Bridge::create($container);



$app->get(
    "/person/insert/{firstName}&{lastName}",[PersonController::class,"insert"]);


$app->get("/person/all", [PersonController::class,"showAll"]);

$app->run();
