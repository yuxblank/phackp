<?php

namespace yuxblank\phackp\core;

use DI\Container;
use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use DI\Scope;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ServerRequestInterface;
use yuxblank\phackp\core\api\ErrorHandlerInterface;
use yuxblank\phackp\core\api\LifeCycleInterface;
use yuxblank\phackp\core\api\Module;
use yuxblank\phackp\database\api\EntitiyManagerDriver;
use yuxblank\phackp\database\Database;
use yuxblank\phackp\database\driver\DoctrineDriver;
use yuxblank\phackp\database\HackORM;
use yuxblank\phackp\exceptions\ConfigurationException;
use yuxblank\phackp\http\HttpKernel;
use yuxblank\phackp\http\ServerRequest;
use yuxblank\phackp\routing\api\Router;
use yuxblank\phackp\services\api\AutoBootService;
use yuxblank\phackp\services\exceptions\ServiceProviderException;
use yuxblank\phackp\view\twig\TwigView;
use yuxblank\phackp\view\View;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;
use function DI\object;

/**
 * Class Application
 * @author Yuri Blanc
 * @package yuxblank\phackp\core
 */
class Application
{

    protected static $instance;
    public static $ROOT;
    private $config;
    protected $version;
    /** @var  Container */
    private $container;
    private $useDefaultDI = true;
    private $modules = [];
    const RUNTIME_NAME = 'pHackpRuntime';

    /**
     * Application constructor.
     */
    protected function __construct()
    {
    }

    /**
     * Get the application instance.
     * If it's not followed by bootstrap() the application will run under exception.
     * @return Application
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Application();
        }
        return self::$instance;
    }


    public function container(): Container
    {
        return $this->container;
    }

    /**
     * @deprecated
     * Return the entire array of configurations.
     * @param string $name
     * @param string|null $key
     * @return array
     */
    public static function getConfig(string $name, string $key = null)
    {
        $config = self::getInstance()->container->get($name);

        if ($key && array_key_exists($key, $config)) {
            return $config[$key];
        }
        return $config;
    }


    /**+
     * @deprecated
     * Check if the application APP_MODE is set to DEBUG
     * @return bool
     */
    public static function isDebug()
    {

        switch (self::getConfig("app.globals", 'APP_MODE')) {
            case 'DEBUG':
                return true;
                break;
        }
    }

    /**
     * Register a service.
     * @param string $service
     * @param bool|null $bootOnStartup
     * @param array|null $config
     * @throws ServiceProviderException
     */
    public function registerService(string $service, bool $bootOnStartup = null, array $config = null)
    {

        // add service to container
        $this->container->set($service,
            \Di\object($service)
                ->scope(Scope::SINGLETON)
                ->constructor($config)
                ->method('bootstrap'));

        // boot if required
        if ($bootOnStartup) {
            if (!class_implements($service, AutoBootService::class)) {
                throw new ServiceProviderException('Service ' . $service . 'does not implements ' . AutoBootService::class, ServiceProviderException::NOT_AUTO_BOOT);
            }
            // create instance
            $this->getServiceInstance($service);
        }
    }


    /**
     * @deprecated inject the service where you need it
     * Retrieve the instance from the container.
     * Eventually makes an instance of the ServiceProvider if was never bootstrapped
     * @param string $serviceName
     * @return mixed|ServiceProvider
     * @throws \InvalidArgumentException
     * @throws \yuxblank\phackp\services\exceptions\ServiceProviderException
     */

    public function getServiceInstance(string $serviceName): ServiceProvider
    {
        $service = null;
        try {
            /**
             * @var ServiceProvider | null
             */
            $service = $this->container->get($serviceName);

            if (!is_subclass_of($service, ServiceProvider::class)) {
                throw new ServiceProviderException('Class ' . get_class($service) . ' is not a subclass of ' .
                    ServiceProvider::class, ServiceProviderException::NOT_A_PROVIDER);
            }
        } catch (NotFoundException $e) {
            throw new ServiceProviderException('Class ' . get_class($service) . ' has not been registered ' .
                ServiceProvider::class, ServiceProviderException::REQUIRE_UNREGISTERED, $e);
        } catch (DependencyException $e) {
            throw new ServiceProviderException('Class ' . get_class($service) . ' is not valid ' .
                ServiceProvider::class, ServiceProviderException::DI_ERROR, $e);
        }
        return $service;
    }

    /**
     * Facade. Get Service Provider from the container
     * @param string $serviceName
     * @return mixed
     * @throws \yuxblank\phackp\services\exceptions\ServiceProviderException
     * @throws \InvalidArgumentException
     */
    public static function getService(string $serviceName)
    {
        return self::getInstance()->getServiceInstance($serviceName);
    }

    private final function runtime()
    {
        $id = random_int(1, 9999);
        if (!defined(self::RUNTIME_NAME)) define(self::RUNTIME_NAME, $id, false);
    }


    /**
     * Bootstrap the application. Requires the root path of the application (__DIR__)
     * Configuration files folder is $realPath/config/.. if $configPath is not set.
     * @param string $realPath (__DIR__) of the application root
     * @param string $configPath override default path of configuration (e.g. when you want use a protected path outside /httpdocs)
     * @throws ConfigurationException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \InvalidArgumentException
     */
    public function bootstrap(string $realPath, string $configPath = null)
    {
        $containerBuilder = new ContainerBuilder();
        $this->runtime();
        self::$ROOT = $realPath;

        $config = $configPath === null ? $realPath . '/config/' : $configPath;

        if (!is_dir($config)) {
            throw new ConfigurationException("The configuration path does not exist: " . $configPath, ConfigurationException::INVALID_PATH);
        }

        $tmp = null;
        $files = glob($config . '*.php');

        if ($this->useDefaultDI) {
            $containerBuilder->addDefinitions($this->frameworkDI());
        }
        foreach ($files as $file) {
            $containerBuilder->addDefinitions($file);
        }

        $containerBuilder->useAutowiring(true);
        $containerBuilder->useAnnotations(true);
        $this->container = $containerBuilder->build();
    }

    public function disableFrameworkDefaultDI()
    {
        $this->useDefaultDI = false;
    }

    /**
     * Framework DI factories
     * @return array
     * @throws \yuxblank\phackp\view\exception\ViewException
     * @throws \Doctrine\ORM\ORMException
     * @throws \DI\NotFoundException
     * @throws \DI\DependencyException
     * @throws \InvalidArgumentException
     */
    private function frameworkDI(): array
    {
        return
            [
                Router::class => function () {
                    return new \yuxblank\phackp\routing\Router($this->container->get('routes'), $this->container->get('app.globals'), $this->container->get(ServerRequestInterface::class));
                },
                Database::class => function () {
                    return new Database($this->container->get('database'));
                },
                HackORM::class => object(HackORM::class),
                View::class => function () {
                    return new View(
                        $this->container->get('app.view'), array_merge($this->container->get('app.globals'), ['APP_ROOT' => self::$ROOT]), $this->container->get(Router::class));
                },
                Session::class => function () {
                    return new Session($this->container->get('app.session'));
                },
                HttpKernel::class => function () {
                    return new HttpKernel($this->container->get('app.http'));
                },
                EntitiyManagerDriver::class => function () {
                    return new DoctrineDriver($this->container->get('doctrine.config'));
                },
                TwigView::class => function () {
                    return new TwigView($this->container->get('app.view.twig'), self::$ROOT);
                },
                EntityManagerInterface::class => \DI\factory([EntitiyManagerDriver::class, 'getDriver'])->scope(Scope::SINGLETON),
                EntityManager::class => \DI\factory([EntitiyManagerDriver::class, 'getDriver'])->scope(Scope::SINGLETON),
                ServiceProvider::class => object(ServiceProvider::class)->property('container', $this->container),
                ServerRequestInterface::class => \DI\factory([HttpKernel::class, 'getRequest'])->scope(Scope::SINGLETON),
                \yuxblank\phackp\http\api\ServerRequestInterface::class => \DI\factory([HttpKernel::class, 'getRequest'])->scope(Scope::SINGLETON),
                ServerRequest::class => \DI\factory([HttpKernel::class, 'getRequest'])->scope(Scope::SINGLETON),
                Response\EmitterInterface::class => function () {
                    return new SapiEmitter();
                },
                LifeCycleInterface::class => function () {
                    return new LifeCycle($this->container,
                        $this->container->get(Response\EmitterInterface::class),
                        $this->container->get(Router::class));
                },
                ErrorHandlerInterface::class => function () {
                    return new ErrorHandler($this->container, $this->container->get(Router::class), $this->container->get(LifeCycleInterface::class));
                }
            ];

    }


    /**
     * Where fun starts!
     * @throws \yuxblank\phackp\exceptions\InvocationException
     * @throws \InvalidArgumentException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function run()
    {
        $this->container->get(ErrorHandlerInterface::class);
        $this->registerModules();
        /** @var LifeCycleInterface $lifeCycle */
        $lifeCycle = $this->container->get(LifeCycleInterface::class);
        $lifeCycle->request();
    }


    public function addModule(Module $module){
        try {
            $refl = new \ReflectionClass($module);
            $this->modules[$refl->getName()] = $module;
        } catch (\ReflectionException $e) {

        }
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    private function registerModules(){
        $routes = $this->container->get('routes');
        /** @var Module $module */
        foreach ($this->modules as $module){
            // add routes
            foreach ($module->getRoutes() as $method => $parsedRoute) {
                foreach ($parsedRoute as $effectiveRoute)
                {
                    $routes[$method][] = $effectiveRoute;
                }
            }

            if($this->container->has('doctrine.config')){
                $doctrineConfig = $this->container->get('doctrine.config');
                foreach ($module->getEntitiesPaths() as $entitiesPath) {
                    $doctrineConfig['entities_paths'][] = $entitiesPath;
                }
                $this->container->set('doctrine.config', $doctrineConfig);
            }
        }

        $this->container->set('routes', $routes);

    }


}


