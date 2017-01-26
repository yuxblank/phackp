<?php
namespace yuxblank\phackp\core;

use yuxblank\phackp\api\Service;
use yuxblank\phackp\exceptions\ClassNotFoundException;
use yuxblank\phackp\exceptions\ConfigurationException;
use yuxblank\phackp\providers\HtmlErrorHandlerReporter;
use yuxblank\phackp\services\ErrorHandlerProvider;
use yuxblank\phackp\services\exceptions\ServiceProviderException;
use yuxblank\phackp\utils\ReflectionUtils;
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
        return self::getRoutes()['ERROR'][$code];
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

    public function registerService(string $service)
    {
        self::getInstance()->services[] = new $service;
    }

    /**
     * @param string $serviceName
     * @return mixed
     * @throws ServiceProviderException
     */

    public static function getService(string $serviceName):ServiceProvider
    {
        foreach (self::getInstance()->services as $service) {
            if ($service instanceof $serviceName) {
                return $service;
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
        $route = Router::findAction($httpKernel);
        if ($route !== null) {
            $httpKernel->dispatch($route);

            $controller = null;
            try {
                $controller = ReflectionUtils::makeInstance($route['class']);
            } catch (ClassNotFoundException $e) {
                Application::getService(ErrorHandlerProvider::class)->invoke(ErrorHandlerProvider::HANDLE, $e);
            }
            ReflectionUtils::invoke($controller, 'onBefore');

            $controller->{$route['method']}($httpKernel->getParams());

            ReflectionUtils::invoke($controller, 'onAfter');
        } else {
            $notFoundRoute = self::getErrorRoute(404);
            $controller = null;
            try {
                $controller = ReflectionUtils::makeInstance($notFoundRoute['class']);
            } catch (ClassNotFoundException $e) {
                http_response_code(404);
                die(Application::isDebug() ? $e : "");
            }

            ReflectionUtils::invoke($controller, 'onBefore');
            $controller->{$notFoundRoute['method']}();
            ReflectionUtils::invoke($controller, 'onAfter');
        }

        if (self::isDebug()) {
            // Anywhere else in the script
            echo '<p style="position: fixed; bottom:0; margin: 0 auto;"> Total execution time in seconds: ' . (microtime(true) - $time_start) . ' runtime_id: ' . pHackpRuntime . ' memory peak: ' . UnitConversion::byteConvert($memoryPeak) . '</p>';
        }
    }


}


