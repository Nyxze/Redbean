<?php
namespace slimApp\middlewares;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TestMiddleware{

public function __invoke(RequestInterface $request, RequestHandlerInterface $handler){
    $response= $handler->handle($request);
    $response->getBody()->write( "  From a class Middleware ");
    return $response;


}


}