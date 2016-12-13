<?php
namespace yuxblank\phackp\core;
use yuxblank\phackp\api\Service;
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
    public static function getRoutes() {
        return self::getInstance()->config['ROUTES'];
    }

    /**
     * Return database configurations
     * @return array
     */
    public static function getDatabase(){
        return self::getInstance()->config['DATABASE'];
    }
    /**
     * Return namespaces configured for the project
     * @return array
     */
    public static function getNameSpace() {
        return self::getInstance()->config['NAMESPACE'];
    }

    /**
     * Return the application root (__DIR__)
     * @return string
     */
    public static function getAppRoot() {
        return self::getInstance()->APP_ROOT;
    }

    /** Return view root dir
     * @return mixed
     */
    public static function getViewRoot() {
        return self::getInstance()->config['VIEW']['ROOT'];
    }
    /**
     * Return the application url configured
     * @return string
     */

    public static function getAppUrl() {
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
    public static function isDebug() {

        switch (self::getConfig()['APP_MODE']){
            case 'DEBUG':
                return true;
                break;
        }
    }

    public static function registerService(Service $service){
        self::getInstance()->services[get_class($service)] = $service;
    }

    public static function getService(String $serviceName) {
        return self::getInstance()->services[$serviceName];
    }

    private final function runtime () {
        $id = random_int(1,9999);
        define('pHackpRuntime', $id, false);
    }



    /**
     * Bootstrap the application. Requires the root path of the application (__DIR__)
     * Configuration files folder MUST be present at ROOT/config/.. path.
     * @param $realPath (__DIR__)
     */
    public function bootstrap(string $realPath)
    {

        $this->runtime();

        $this->APP_ROOT = $realPath;

        $config = $realPath.'/config/';

        if (is_dir($config)) {

            $tmp = null;
            $files = glob($config . '*.php');

            foreach ($files as $file) {
                $tmp[] = require $file;
            }

            foreach ($tmp as $key => $value ) {

                foreach($value as $key2 => $innervalue) {

                    $this->config[$key2] = $innervalue;

                }

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
        if ($route!==null) {
            $httpKernel->dispatch($route, $httpKernel);
            $action = Router::getController($route['action']);
            $controller = new $action[0];

            ReflectionUtils::invoke($controller, 'onBefore');

            $a = $action[1];
            $controller->$a($httpKernel->getParams());

            ReflectionUtils::invoke($controller, 'onAfter');
        } else {
            $notFoundRoute = Router::getController(self::getErrorRoute(404)['action']);
            $controller = new $notFoundRoute[0]();
            ReflectionUtils::invoke($controller, 'onBefore');
            $a = $notFoundRoute[1];
            $controller->$a();
            ReflectionUtils::invoke($controller, 'onAfter');
        }

        if(self::isDebug()) {
            // Anywhere else in the script
            echo '<p style="position: fixed; bottom:0; margin: 0 auto;"> Total execution time in seconds: ' . (microtime(true) - $time_start) . ' runtime_id: ' . pHackpRuntime .' memory peak: '. UnitConversion::byteConvert($memoryPeak)      .'</p>';
        }
    }


}


