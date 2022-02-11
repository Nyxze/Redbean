<?php
namespace slimApp\controllers;

use Psr\Http\Message\ResponseInterface;
use RedBeanPHP\R as R;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;

class PersonWebController extends AbstractWebController {

    private function getOnePersonFromId($id){
        $person = R::load("person", $id);
        
        if(! empty($person->address_id)){
            $address = R::load("address", $person->address_id);
            $person->address = $address;
        } else {
            $person = [
                "id" => null,
                "firstName" => "",
                "lastName" => "",
                "address" => [
                    "id" => null,
                    "street" => "",
                    "zipCode" => "",
                    "city" => ""
                ]
            ];
        }

        return $person;
    }

    public function showAll(ResponseInterface $response){
        $persons = R::findAll("person");
        return $this->render(
            $response, 
            "person.twig", [
            "personList" => $persons]
        );
    }

    public function showOne($id, ResponseInterface $response){
        $person = $this->getOnePersonFromId($id);
    
        return $this->render(
            $response, 
            "person.twig", [
            "person" => $person]
        );
    }

    public function showForm(ResponseInterface $response, $id = null){
        $person = $this->getOnePersonFromId($id);
        return $this->render(
            $response, 
            "form.twig", ["person" => $person]
        );
    }

    public function processForm(ResponseInterface $response, 
    ServerRequestInterface $request){
        $data = $request->getParsedBody();

        $address = R::dispense("address");
        $address->import($data["address"]);
        R::store($address);

        if(empty($data["contact"]["id"])){
            $person = R::dispense("person");
        } else {
            $person = R::load("person", $data["contact"]["id"]);
        }
        
        $person->import($data["contact"]);
        $person->address = $address;
        R::store($person);

        return $response->withStatus(302)
                        ->withHeader("location", "/person/");

    }

}