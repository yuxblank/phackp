<?php

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
        $this->application->run();
        $this->assertEquals(ob_get_contents(), 'Hello!');
    }

}