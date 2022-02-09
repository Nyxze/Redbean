<?php
namespace slimApp\controllers;

use Psr\Http\Message\ResponseInterface;

class HomeController extends AbstractWebController {
  
public function hello(ResponseInterface $response, $name = null ){
    
    return $this->render($response, "hello.twig",
    
    [
        "name"=>$name,
        "skills"=>["Rene","Lataupe","Linkedin"],
        "showSkills"=>true

]);

}

}