<?php
namespace slimApp\controllers;

use Exception;
use Psr\Http\Message\ResponseInterface;
use RedBeanPHP\R as R;

class PersonWebController extends AbstractWebController
{

    
    public function showAll(ResponseInterface $response)
    {
        $personList = R::findAll("person");
        // $response->getBody()->write(json_encode($personList));
        return $this->render($response, "person.twig",
    
    [
        "personList"=>$personList,
    ]);


    }
    public function showOne(ResponseInterface $response, $id)
    {

        $person = R::findOne("person","id = $id");
       
        return $this->render($response, "person.twig",
    
    [
        "person"=>$person,
    ]);
    }

    public function showForm(ResponseInterface $response)
    {
        return $this->render($response, "form.twig",
    
        [
        ]);
    }

    public function processForm(ResponseInterface $response){
        $isPosted = filter_has_var(INPUT_POST,"submit");
        if($isPosted){
            $firstName = filter_input(INPUT_POST,"firstName",FILTER_SANITIZE_STRING);
            $lastName = filter_input(INPUT_POST,"lastName",FILTER_SANITIZE_STRING);
            if(!empty($firstName)&& !empty($lastName)){
                $person = R::dispense("person");
                $person->firstName = $firstName;
                $person->lastName = $lastName;
                
                R::store($person);

            }else{
                $response->getBody()->write("No value pass");
            }

        // $response->withStatus(302)->withHeader("location","/person/");
        return $response->withStatus(302)->withHeader("location","/person/form");;
    }

    }
}
