<?php
require '../vendor/autoload.php';
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 10/05/2016
 * Time: 15:42
 */
use yuxblank\phackp\core\Session;
use yuxblank\phackp\core\Application;
class SessionTest extends PHPUnit_Framework_TestCase
{

    /**
     * SessionTest constructor.
     * @param $config
     */
    public function __construct()
    {
        $app = Application::getInstance();
        $app->bootstrap(__DIR__);
    }


    public function testInstance()
    {
        @session_start();
        $session = new Session();
        $session->setValue('test', [1,2,3,4,5,6=>[new stdClass()]]);
        self::assertNull($session->getValue('test'));
    }


}
