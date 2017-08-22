<?php

use yuxblank\phackp\http\api\ServerRequestInterface;

/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 27/06/2017
 * Time: 16:51
 */

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    /** @var  \yuxblank\phackp\core\Application */
    private $application;

    public function setUp(){
        $app = $this->application = \yuxblank\phackp\core\Application::getInstance();
        $app->bootstrap('../');
    }

    /**
     * @runInSeparateProcess
     */
    public function testRun(){
        ob_start();
        $this->application->run();
        $out = ob_get_contents();
        ob_end_clean();
        $this->assertEquals($out, 'Hello!');
    }
    /**
     * @runInSeparateProcess

    public function testJsonResponse(){
        ob_start();
        $this->application->run();
        $out = ob_get_contents();
        ob_end_clean();
        echo $out;
        $this->assertEquals($out, 'Hello!');
    }
     */
}