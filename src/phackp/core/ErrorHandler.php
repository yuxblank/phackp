<?php
/**
 * Created by IntelliJ IDEA.
 * User: yux
 * Date: 02/03/18
 * Time: 21.51
 */

namespace yuxblank\phackp\core;


use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;
use yuxblank\phackp\core\api\ApplicationController;
use yuxblank\phackp\core\api\ErrorHandlerInterface;
use yuxblank\phackp\core\api\LifeCycleInterface;
use yuxblank\phackp\routing\api\RouteInterface;
use yuxblank\phackp\routing\api\Router;
use yuxblank\phackp\routing\exception\RouterException;

class ErrorHandler implements ErrorHandlerInterface
{

    private $container;
    private $router;
    private $lifeCycle;
    /** @var RouteInterface */
    public $error500route;

    public function __construct(Container $container, Router $router, LifeCycleInterface $lifeCycle)
    {
        $this->container = $container;
        $this->router = $router;
        $this->lifeCycle = $lifeCycle;
        $this->register();

    }

    private function register()
    {
        //set_error_handler(array($this, 'errorHandler'));
        try {
            $this->error500route = $this->router->getErrorRoute(500);
            set_exception_handler(array($this, 'exceptionHandler'));
        } catch (RouterException $e) {
            restore_exception_handler();
        }

    }


    public function errorHandler()
    {

    }

    public function exceptionHandler(\Throwable $exception)
    {
        $this->handleEntityManagerStatus();
        $this->container->set(ApplicationController::class, $this->error500route->getClass());
        $this->lifeCycle->callController($this->error500route->getAction(), $exception);
    }

    private function handleEntityManagerStatus()
    {
        try {
            /** @var EntityManagerInterface $doctrineDriver */
            $doctrineDriver = $this->container->get(EntityManagerInterface::class);
            $doctrineDriver->rollback();
        } catch (\Exception $exception) {

        }
        if ($doctrineDriver) {
            $doctrineDriver->close();
        }

    }

}