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
        $out = ob_get_contents();
        ob_clean();
        $this->assertEquals($out, 'Hello!');

    }
    /**
     * @runInSeparateProcess
     */
  /*  public function testRoutedResponse(){
        $this->application->run();
        $out = ob_get_contents();
        ob_clean();
        var_dump($out);
        //$this->assertEquals($out, 'Pippo!');

    }*/

}