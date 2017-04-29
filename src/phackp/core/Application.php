<?php

namespace yuxblank\phackp\core;

use DI\Container;
use DI\ContainerBuilder;
use DI\NotFoundException;
use function foo\func;
use Psr\Container\ContainerInterface;
use yuxblank\phackp\database\Database;
use yuxblank\phackp\database\HackORM;
use yuxblank\phackp\exceptions\ConfigurationException;
use yuxblank\phackp\exceptions\InvocationException;
use yuxblank\phackp\services\api\AutoBootService;
use yuxblank\phackp\services\exceptions\ServiceProviderException;
use yuxblank\phackp\utils\ReflectionUtils;
use yuxblank\phackp\utils\UnitConversion;
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
    protected $services = [];
    protected $serviceConfig = [];
    /** @var  Container */
    private $container;



    /**
     * Application constructor.
     */
    protected function __construct()
    {
        /* // register default provider
         $this->services =
             [
                 ErrorHandlerProvider::class
             ];

         $this->registerService($this->services);*/
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


    public function container():ContainerInterface
    {
        return $this->container;
    }

    /**
     * Return the entire array of configurations.
     * @return array
     * @throws \InvalidArgumentException
     * @throws \DI\NotFoundException
     * @throws \DI\DependencyException
     */
    public static function getConfig(string $name, string $key=null)
    {
        $config =  self::getInstance()->container->get($name);

        if($key && array_key_exists($key,$config)){
            return $config[$key];
        }
        return $config;
    }


    /**
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

    public function registerService(string $service, bool $bootOnStartup = null, array $config = null)
    {
        try {
            self::getInstance()->services[] = $service;
        } catch (InvocationException $ex) {
            throw new InvocationException("Unable to make service instance", InvocationException::SERVICE);
        }
        if ($config !== null) {
            self::getInstance()->serviceConfig[$service] = $config;
        }
        if ($bootOnStartup) {
            $serviceInstance = self::getInstance()->getService($service);
            if (!class_implements($serviceInstance, AutoBootService::class)) {
                throw new ServiceProviderException('Service ' . $service . 'does not implements ' . AutoBootService::class, ServiceProviderException::NOT_AUTO_BOOT);
            }
        }
    }

    public function getServiceConfig(string $serviceName)
    {
        foreach (self::getInstance()->serviceConfig as $name => $options) {
            if ($name === $serviceName) {
                return $options;
            }
        }
        return null;
    }


    /**
     * Retrieve the instance from the container.
     * Eventually makes an instance of the ServiceProvider if was never bootstrapped
     * @param string $serviceName
     * @return mixed|ServiceProvider
     * @throws \yuxblank\phackp\services\exceptions\ServiceProviderException
     * @throws \yuxblank\phackp\exceptions\InvocationException
     */

    public static function getService(string $serviceName): ServiceProvider
    {
        foreach (self::getInstance()->services as $key => $service) {
            if (!is_object($service) && $service === $serviceName) {
                try {
                    // bootstrap with config
                    if (self::getInstance()->getServiceConfig($serviceName) !== null) {
                        self::getInstance()->services[$key] = new $service(self::getInstance()->getServiceConfig($serviceName));
                    } else {
                        // no config
                        self::getInstance()->services[$key] = new $service();
                    }
                    if (!is_subclass_of(self::getInstance()->services[$key], ServiceProvider::class)) {
                        throw new ServiceProviderException('Class ' . get_class($service) . ' is not a subclass of ' .
                            ServiceProvider::class, ServiceProviderException::NOT_A_PROVIDER);
                    }

                    // do run bootstrap on Provider implementation
                    self::getInstance()->services[$key]->bootstrap();


                } catch (InvocationException $ex) {
                    throw new InvocationException('Unable to make service instance', InvocationException::SERVICE, $ex);
                }
            }
            if (self::getInstance()->services[$key] instanceof $serviceName) {
                return self::getInstance()->services[$key];
            }
        }
        throw new ServiceProviderException($serviceName, ServiceProviderException::REQUIRE_UNREGISTERED);
    }

    private final function runtime()
    {
        $id = random_int(1, 9999);
        define('pHackpRuntime', $id, false);
    }


    /**
     * Bootstrap the application. Requires the root path of the application (__DIR__)
     * Configuration files folder is $realPath/config/.. if $configPath is not set.
     * @param string $realPath (__DIR__) of the application root
     * @param string $configPath override default path of configuration (e.g. when you want use a protected path outside /httpdocs)
     * @throws ConfigurationException
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

        foreach ($files as $file) {
            $containerBuilder->addDefinitions($file);
        }

        /*
        foreach ($tmp as $key => $value) {

            foreach ($value as $key2 => $innervalue) {

                $this->config[$key2] = $innervalue;

            }
        }
        */
        $containerBuilder->addDefinitions($this->frameworkDI());
        $containerBuilder->useAutowiring(true);
        $containerBuilder->useAnnotations(true);
        $this->container = $containerBuilder->build();
    }

    /**
     * Framework DI factories
     * @return array
     */
    private function frameworkDI(){
        return
            [
                Router::class => function (){
                    return new Router($this->container->get('routes'), $this->container->get('app.globals'));
                },
                Database::class => function(){
                    return new Database($this->container->get('database'));
                },
                HackORM::class => object(HackORM::class),
                View::class => function(){
                    return new View(
                        array_merge($this->container->get('app.view'), $this->container->get('app.globals'), ['APP_ROOT'=>self::$ROOT]), $this->container->get(Router::class));
                },
                Session::class => function(){
                    return new Session($this->container->get('app.session'));
                },
                HttpKernel::class => function() {
                    return new HttpKernel($this->container->get('app.http'));
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
        if (self::isDebug()) {
            // At start of script
            $time_start = microtime(true);
            $memoryPeak = memory_get_peak_usage(true);
        }

        // get the httpKernel

        $httpKernel = $this->container->make(HttpKernel::class);

        /** @var Router $router */
        $router = $this->container->make(Router::class);

        $route = $router->findAction($httpKernel);

        if ($route !== null) {

            if (!is_subclass_of($route['class'], Controller::class)) {
                throw new InvocationException('Class ' . $route['class'] . ' is not a controller, extend ' . Controller::class . ' is required by controllers', InvocationException::ROUTER);
            }

            $clazz = null;
            try {
                if (!$this->container->has($route['class'])) {
                    $this->container->set($route['class'], $route['class']);
                }
                /** Make the controller class */
                $clazz = $this->container->make($route['class'], [
                    'request' => $httpKernel->getRequest(),
                    'router' => $router
                ]);

            } catch (NotFoundException $e) {
                throw new InvocationException('Class ' . $route['class'] . ' not found in routes', InvocationException::ROUTER, $e);
            }


            $httpKernel->parseBody($route);
            ReflectionUtils::invoke($clazz, 'onBefore');
            $clazz->{$route['method']}($httpKernel->getParams());
            ReflectionUtils::invoke($clazz, 'onAfter');

        } else {
            $notFoundRoute = $this->container->get(Router::class)->getErrorRoute(404);
            $clazz = new $notFoundRoute['class']($httpKernel->getRequest(), $router);
            ReflectionUtils::invoke($clazz, 'onBefore');
            $clazz->{$notFoundRoute['method']}($httpKernel->getParams());
            ReflectionUtils::invoke($clazz, 'onAfter');
        }

        if (self::isDebug() && ($httpKernel->getContentType() === 'text/plain' || $httpKernel->getContentType() === 'text/html')) {
            // Anywhere else in the script
            echo '<p style="position: fixed; bottom:0; margin: 0 auto;"> Total execution time in seconds: ' . (microtime(true) - $time_start) . ' runtime_id: ' . pHackpRuntime . ' memory peak: ' . UnitConversion::byteConvert($memoryPeak) . '</p>';
        }
    }


}


