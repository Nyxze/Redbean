<?php
namespace slimApp\controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RedBeanPHP\R as R;

class PersonWebController extends AbstractWebController
{

    private function getOnePersonFromId($id)
    {
        if (!empty($id)) {
            $person = R::load("person", $id);

            if (!empty($person->address_id)) {
                $address = R::load("address", $person->address_id);
                $person->address = $address;

            }
            $phones = [];
            foreach ($person->ownPhone as $phone) {
                $phones[] = $phone->number;
            }
            $person->ownPhone = $phones;

        } else {
            $person = [
                "id" => null,
                "firstName" => "",
                "lastName" => "",
                "address" => [
                    "id" => null,
                    "street" => "",
                    "zipCode" => "",
                    "city" => "",
                ],
            ];
        }

        return $person;
    }



    public function showAll(ResponseInterface $response)
    {
        $persons = R::findAll("person");
        return $this->render(
            $response,
            "person.twig", [
                "personList" => $persons]
        );
    }

    public function showOne($id, ResponseInterface $response)
    {
        $person = $this->getOnePersonFromId($id);


        return $this->render(
            $response,
            "person.twig", [
                "person" => $person
            ]
        );
    }

    public function showForm(ResponseInterface $response, $id = null)
    {
        $person = $this->getOnePersonFromId($id);
        return $this->render(
            $response,
            "form.twig", ["person" => $person]
        );
    }

    public function processForm(ResponseInterface $response,
        ServerRequestInterface $request) {
        $data = $request->getParsedBody();
        var_dump($data);

        $address = R::dispense("address");
        $address->import($data["address"]);
        R::store($address);

        if (empty($data["contact"]["id"])) {
            $person = R::dispense("person");
        } else {
            $person = R::load("person", $data["contact"]["id"]);
            $person->ownPhone = [];
        }
        if (isset($data["phones"])) {
            $phoneNumbers = $data["phones"]["numbers"];
            for ($i = 0; $i < count($phoneNumbers); $i++) {
                if (!empty(trim($phoneNumbers[$i]))) {
                    $phone = R::dispense("phone");
                    $phone->number = $phoneNumbers[$i];
                    $person->ownPhone[] = $phone;
                }
            }

        }

        $person->import($data["contact"]);
        $person->address = $address;
        R::store($person);
        R::exec("DELETE FROM phone WHERE person_id IS NULL");

        return $response->withStatus(302)
            ->withHeader("location", "/person/");

    }

}
