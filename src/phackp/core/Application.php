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
    private $config;
    protected $version;

    /**
     * Application constructor.
     */
    protected function __construct()
    {

    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Application();
        }
        return self::$instance;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }



    /**
     * Bootstrap the application. Requires the path of the configuration files.
     * @param $config
     */
    public function bootstrap($config)
    {

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

    public function run()
    {
        // get the httpKernel
        $httpKernel = new HttpKernel();
        // get the route
        $route = Router::findAction($httpKernel->getUrl());
        Router::dispatch($route, $httpKernel);
        $controller = Router::getController($route->action);
        $action = $route->action;
        $controller->$action($httpKernel->getParams());
    }


}


