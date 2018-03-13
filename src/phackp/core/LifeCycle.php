<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 22/06/2017
 * Time: 11:22
 */

namespace yuxblank\phackp\core;


use DI\Container;
use DI\DependencyException;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\Exception\NotFoundException;
use Invoker\Exception\NotCallableException;
use Psr\Http\Message\ResponseInterface;
use yuxblank\phackp\core\api\ApplicationController;
use yuxblank\phackp\core\api\LifeCycleInterface;
use yuxblank\phackp\database\driver\DoctrineDriver;
use yuxblank\phackp\exceptions\InvocationException;
use yuxblank\phackp\http\HttpKernel;
use yuxblank\phackp\routing\api\Router;
use yuxblank\phackp\routing\exception\RouterException;
use Zend\Diactoros\Response\EmitterInterface;

/**
 * Class LifeCycle
 * @package yuxblank\phackp\core
 */
class LifeCycle implements LifeCycleInterface
{
    /** @var  Container */
    private $container;
    /** @var  EmitterInterface */
    private $emitter;
    private $router;

    public function __construct(Container $container, EmitterInterface $emitter, Router $router)
    {
        $this->container = $container;
        $this->emitter = $emitter;
        $this->router = $router;
    }


    public function request()
    {

        try {
            $route = $this->router->findAction();
            try {
                $this->container->set(ApplicationController::class, $route->getClass());
                $this->container->call([HttpKernel::class, 'parseRequest'], array($route));
                $this->callController($route->getAction());
            } catch (NotFoundException $e) {
                throw new InvocationException('Class ' . $route->getClass() . ' is not valid: ' . $e->getMessage(), InvocationException::ROUTER, $e);
            }

        } catch (RouterException $ex) {
            if ($ex->getCode() === $ex::NOT_FOUND) {
                //todo better support for multi-apps
                $notFoundRoute = $this->router->getErrorRoute(404);
                $this->container->set(ApplicationController::class, $notFoundRoute->getClass());
                $this->callController($notFoundRoute->getAction());
            }
        }
    }


    public function callController(string $method, ...$params)
    {
        $instace = $this->container->get(ApplicationController::class);


            $resp = $this->container->call([$instace, ApplicationController::EVENT_ON_BEFORE]);
            if ($resp && $resp instanceof ResponseInterface) {
                $this->response($resp);
            } else {
                $resp = $this->container->call([$instace, $method], $params);
                $this->handleDoctrineDriver();
                if ($resp && $resp instanceof ResponseInterface) {
                    $this->response($resp);
                }
                $this->container->call([$instace, ApplicationController::EVENT_ON_AFTER]);
            }

    }


    public function response(ResponseInterface $response)
    {
        $this->emitter->emit($response);
    }

    private function handleDoctrineDriver()
    {
        try {
            $doctrineConfig = $this->container->get('doctrine.config');
            $isContaierManaged = $doctrineConfig['transaction'] === DoctrineDriver::CONTAINER_MANAGED ?? false;
            if ($isContaierManaged) {
                $doctrineDriver = $this->container->get(EntityManagerInterface::class);
                if ($doctrineDriver->isOpen()) {
                    $doctrineDriver->flush();
                }
            }
            // it's not a required dependency
        } catch (DependencyException $e) {

        } catch (\DI\NotFoundException $e) {

        }
    }


}