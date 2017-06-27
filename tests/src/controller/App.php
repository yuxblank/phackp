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

    public function index(ServerRequestInterface $serverRequest){

        echo "true";
    }


}