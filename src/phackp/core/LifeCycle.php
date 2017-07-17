<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 22/06/2017
 * Time: 11:22
 */

namespace yuxblank\phackp\core;


use DI\Container;
use Interop\Container\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use yuxblank\phackp\core\api\ApplicationController;
use yuxblank\phackp\exceptions\InvocationException;
use yuxblank\phackp\http\HttpKernel;
use yuxblank\phackp\routing\api\Router;
use Zend\Diactoros\Response\EmitterInterface;

/**
 * Class LifeCycle
 * @package yuxblank\phackp\core
 */

class LifeCycle
{
    /** @var  Container */
    private $container;
    /** @var  EmitterInterface */
    private $emitter;

    public function __construct(Container $container, EmitterInterface $emitter){
        $this->container = $container;
        $this->emitter = $emitter;
    }


    protected function request(HttpKernel $httpKernel, Router $router){

        $route = $router->findAction();

        if ($route !== null && class_exists($route['class'])) {

            if (!is_subclass_of($route['class'], Controller::class)) {
                throw new InvocationException('Class ' . $route['class'] . ' is not a controller, extend ' . Controller::class . ' is required by controllers', InvocationException::ROUTER);
            }

            try {
                $this->container->set(ApplicationController::class, $route['class']);
            } catch (NotFoundException $e) {
                throw new InvocationException('Class ' . $route['class'] . ' not found in routes', InvocationException::ROUTER, $e);
            }

            $this->container->call([HttpKernel::class, 'parseRequest'], [$route]);
            $instance = $this->container->get(ApplicationController::class);
            $this->container->call([$instance,Controller::EVENT_ON_BEFORE]);
            $this->response($this->container->call([$instance,$route['method']]));

        } else {
            $notFoundRoute = $this->container->get(Router::class)->getErrorRoute(404);
            $this->container->set(ApplicationController::class, $notFoundRoute['class']);
            $instance = $this->container->get(ApplicationController::class);
            $this->container->call([$instance,Controller::EVENT_ON_BEFORE]);
            $this->response($this->container->call([$instance,$notFoundRoute['method']]));
        }
    }

    protected function response (ResponseInterface $response) {
        $this->emitter->emit($response);
        return $this->container->call([ApplicationController::class, Controller::EVENT_ON_AFTER]);
    }




}