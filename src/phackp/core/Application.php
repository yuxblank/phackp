<?php
namespace yuxblank\phackp\core;

use yuxblank\phackp\api\Service;
use yuxblank\phackp\exceptions\ConfigurationException;
use yuxblank\phackp\exceptions\InvocationException;
use yuxblank\phackp\providers\HtmlErrorHandlerReporter;
use yuxblank\phackp\services\api\AutoBootService;
use yuxblank\phackp\services\exceptions\ServiceProviderException;
use yuxblank\phackp\utils\UnitConversion;

/**
 * Class Application
 * @author Yuri Blanc
 * @package yuxblank\phackp\core
 */
class Application
{

    protected static $instance;
    protected $APP_ROOT;
    private $config;
    protected $version;
    protected $services = [];
    protected $serviceConfig = [];


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

    /**
     * Return the entire array of configurations.
     * @return array
     */
    public static function getConfig()
    {
        return self::getInstance()->config;
    }

    /**
     * Return routes
     * @return array
     */
    public static function getRoutes()
    {
        return self::getInstance()->config['ROUTES'];
    }

    /**
     * Return database configurations
     * @return array
     */
    public static function getDatabase()
    {
        return self::getInstance()->config['DATABASE'];
    }

    /**
     * Return namespaces configured for the project
     * @return array
     */
    public static function getNameSpace()
    {
        return self::getInstance()->config['NAMESPACE'];
    }

    /**
     * Return the application root (__DIR__)
     * @return string
     */
    public static function getAppRoot()
    {
        return self::getInstance()->APP_ROOT;
    }

    /** Return view root dir
     * @return mixed
     */
    public static function getViewRoot()
    {
        return self::getInstance()->config['VIEW']['ROOT'];
    }

    /**
     * Return the application url configured
     * @return string
     */

    public static function getAppUrl()
    {
        return self::getInstance()->config['APP_URL'];
    }

    /**
     * Return the action for the given error code from routes.
     * @param int $code
     * @return mixed
     */
    public static function getErrorRoute(int $code)
    {
        if (isset(self::getRoutes()['ERROR'][$code])){
            return self::getRoutes()['ERROR'][$code];
        }
        return null;
    }

    /**
     * Check if the application APP_MODE is set to DEBUG
     * @return bool
     */
    public static function isDebug()
    {

        switch (self::getConfig()['APP_MODE']) {
            case 'DEBUG':
                return true;
                break;
        }
    }

    public function registerService(string $service,bool $bootOnStartup=null, array $config=null)
    {
        try {
            self::getInstance()->services[] =  $service;
        } catch (InvocationException $ex){
            throw new InvocationException("Unable to make service instance", InvocationException::SERVICE);
        }
        if ($config!==null){
            self::getInstance()->serviceConfig[$service] = $config;
        }
        if ($bootOnStartup){
            $serviceInstance = self::getInstance()->getService($service);
            if (!class_implements($serviceInstance, AutoBootService::class)){
                throw new ServiceProviderException('Service ' . $service . 'does not implements ' . AutoBootService::class, ServiceProviderException::NOT_AUTO_BOOT);
            }
        }
    }

    public  function getServiceConfig(string $serviceName){
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
            if (!is_object($service) && $service === $serviceName){
                try {
                    // bootstrap with config
                    if (self::getInstance()->getServiceConfig($serviceName) !== null){
                        self::getInstance()->services[$key] = new $service(self::getInstance()->getServiceConfig($serviceName));
                    } else {
                        // no config
                        self::getInstance()->services[$key] = new $service();
                    }
                    if (!is_subclass_of(self::getInstance()->services[$key], ServiceProvider::class)){
                        throw new ServiceProviderException('Class ' . get_class($service) .  ' is not a subclass of '.
                            ServiceProvider::class, ServiceProviderException::NOT_A_PROVIDER);
                    }

                    // do run bootstrap on Provider implementation
                    self::getInstance()->services[$key]->bootstrap();


                } catch (InvocationException $ex){
                    throw new InvocationException('Unable to make service instance', InvocationException::SERVICE,$ex);
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

        $this->runtime();

        $this->APP_ROOT = $realPath;

        $config = $configPath === null ? $realPath . '/config/' : $configPath;

        if (!is_dir($config)) {
            throw new ConfigurationException("The configuration path does not exist: " . $configPath, ConfigurationException::INVALID_PATH);
        }

        $tmp = null;
        $files = glob($config . '*.php');

        foreach ($files as $file) {
            $tmp[] = require $file;
        }

        foreach ($tmp as $key => $value) {

            foreach ($value as $key2 => $innervalue) {

                $this->config[$key2] = $innervalue;

            }

        }
    }

    /**
     * Where fun starts!
     * @throws \yuxblank\phackp\exceptions\InvocationException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function run()
    {
        if (self::isDebug()) {
            // At start of script
            $time_start = microtime(true);
            $memoryPeak = memory_get_peak_usage(true);
        }

        // get the httpKernel

        $httpKernel = new HttpKernel();
        // get the route
        $router = new Router(self::getRoutes());

        $route = $router->findAction($httpKernel);
        $httpKernel->parseBody($route);
        if ($route !== null) {
            Router::doRoute($route,$httpKernel->getParams(), $httpKernel->getRequest());
        } else {
            $notFoundRoute = self::getErrorRoute(404);
            Router::doRoute($notFoundRoute, null, $httpKernel->getRequest());
        }

        if (self::isDebug() && ($httpKernel->getContentType() === 'text/plain' || $httpKernel->getContentType() === 'text/html')) {
            // Anywhere else in the script
            echo '<p style="position: fixed; bottom:0; margin: 0 auto;"> Total execution time in seconds: ' . (microtime(true) - $time_start) . ' runtime_id: ' . pHackpRuntime . ' memory peak: ' . UnitConversion::byteConvert($memoryPeak) . '</p>';
        }
    }


}


