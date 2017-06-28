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
use Zend\Diactoros\Response;

class App extends Controller
{
    public function onBefore()
    {
        // TODO: Implement onBefore() method.
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


}