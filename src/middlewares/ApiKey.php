<?php

namespace slimApp\middlewares;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ApiKey
{
    private string $key;
    public function __construct(string $key)
    {
        $this->key = filter_var($key,FILTER_SANITIZE_STRING);
    }
    public function __invoke(
        RequestInterface $request,
        RequestHandlerInterface $handler

    ){
        $key = filter_input(INPUT_GET, "key");

        if($key != $this->key)
        {
            throw new \Exception("Invalid Key");
        }
        $response = $handler->handle($request);
        return $response;
    }
}
