<?php
namespace slimApp\controllers;
use Psr\Http\Message\ResponseInterface;

use DI\Container;

abstract class AbstractWebController{

protected Container $container;

public function __construct(Container $c){
    $this->container = $c;
}

public function render (ResponseInterface $reponse, string $template,array $params = []){

return $this->container->get("Twig")->render($reponse,$template,$params);
}

}