<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 27/06/2017
 * Time: 17:03
 */

namespace test\controller;


use Psr\Http\Message\ServerRequestInterface;
use yuxblank\phackp\core\Controller;
use yuxblank\phackp\http\ServerRequest;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\JsonResponse;

class App extends Controller
{
    public function onBefore()
    {

    }

    public function onAfter()
    {
        // TODO: Implement onAfter() method.
    }

    /**
     * @param ServerRequestInterface $serverRequest
     * @return Response
     */
    public function index(ServerRequestInterface $serverRequest){
        $response = new Response();
        $response->getBody()->write("Hello!");
        return $response;
    }

    public function testGet(){
        $response = new Response();
        $response->getBody()->write("Pippo!");
        return $response;
    }

    public function testJsonResponse(){
        $class = new \stdClass();
        $class->field1 = "testfield1";
        $class->field2 = "testfield1";
        return new JsonResponse($class);
    }

    public function supaDupaPathParams(ServerRequest $serverRequest){
        return new JsonResponse($serverRequest->getPathParams());
    }











}