<?php
namespace slimApp\controllers;
use Psr\Http\Message\ResponseInterface;
use RedBeanPHP\R as R;

class PersonController
{

    
    public function showAll(ResponseInterface $response)
    {

        $personList = R::findAll("person");
        $response->getBody()->write(json_encode($personList));
        return $response;
    }
    public function insert(ResponseInterface $response,$firstName,$lastName)
    {

        $person = R::dispense("person");
        $person->firstName = $firstName;
        $person->lastName = $lastName;
        $id = R::store($person);
        $response->getBody()->write("hello $person->firstName $person->lastName id : $id");
        return $response;
    }

}
