<?php
namespace yuxblank\phackp\core;
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 04/04/2016
 * Time: 14:35
 */
class Application
{

    protected static $instance;
    protected $APP_ROOT;
    private $config;
    protected $version;

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
    public function getConfig()
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

    /**
     * Return the application url configured
     * @return string
     */

    public static function getAppUrl() {
        return self::getInstance()->config['APP_URL'];
    }


    /**
     * Bootstrap the application. Requires the root path of the application (__DIR__)
     * Configuration files folder MUST be present at ROOT/config/.. path.
     * @param $realPath (__DIR__)
     */
    public function bootstrap(string $realPath)
    {

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
        // get the httpKernel
        $httpKernel = new HttpKernel();
        // get the route
        $route = Router::_findAction($httpKernel->getUrl());
        if ($route!==null) {
            Router::dispatch($route, $httpKernel);
            if (array_key_exists('params', $route)) {
                $httpKernel->setParams($route['params']);
            }
            $action = Router::getController($route['action']);
            $controller = new $action[0];
            $a = $action[1];
            $controller->$a($httpKernel->getParams());
        } else {
            die("404 not found");
        }
    }


}


