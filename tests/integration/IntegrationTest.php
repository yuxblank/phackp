<?php

use GuzzleHttp\Client;

/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 24/08/2017
 * Time: 13:56
 */
class IntegrationTest extends PHPUnit_Framework_TestCase
{

    /** @var  Client */
    protected $client;
    protected $config;
    protected $dbConfig;
    protected $uri;

    protected function setUp()
    {
        $path = defined("CONFIG_PATH") ? CONFIG_PATH : "../config/";
        $this->config = require $path."app.php";
        $this->uri = $this->config['app.globals']['APP_URL'];
        $dbConf = require $path."database.php";
        $this->dbConfig = $dbConf['database'];
        $this->client = new Client();

        $this->createTestDatabase();

    }

    public function testIndexRun()
    {
        $res = $this->client->request("GET", $this->uri . "/");
        $this->assertEquals($res->getBody(), "Hello!");
    }

    public function testJsonResponse()
    {
        $class = new \stdClass();
        $class->field1 = "testfield1";
        $class->field2 = "testfield1";
        $res = $this->client->request("GET", $this->uri . "/json/response");
        $this->assertEquals($res->getHeaderLine("content-type"), "application/json");
        $this->assertEquals(\GuzzleHttp\json_decode($res->getBody()), $class);
    }

    public function testRestPost()
    {

        $object = array(
            "title" => "Eureka!",
            "date_created" => date(DATE_ATOM),
            "content" => "<div class='test-crazy'</div>",
            "category_id" => 1
        );
        $res = $this->client->post($this->uri . "/rest/post", ['json' => $object]);
        var_dump(['POST_CREATED' => $res->getBody()->getContents()]);
        $this->assertNotEmpty(\GuzzleHttp\json_decode($res->getBody()));
    }


    public function testRestPut()
    {

        $object = array(
            "title" => "EurekaUpdate!",
            "date_created" => date(DATE_ATOM),
            "content" => "<div class='test-crazy'> update the post!</div>",
            "category_id" => 1
        );
        $res = $this->client->put($this->uri . "/rest/put/1", ['json' => $object]);
        $this->assertEquals(\GuzzleHttp\json_decode($res->getBody())->result, "OK");
    }



    public function testErrorHandler(){
        $res = $this->client->get($this->uri . "/exception", []);
        $this->assertEquals(\GuzzleHttp\json_decode($res->getBody())->error, '500');
    }

    public function testNotFound(){
        $res = $this->client->get($this->uri . "/pippo/1", []);

        $this->assertEquals(\GuzzleHttp\json_decode($res->getBody())->error, 'not-found');
    }




    protected function tearDown()
    {
        $db = new \yuxblank\phackp\database\Database($this->dbConfig);
        $db->query(
            "TRUNCATE category;
                      TRUNCATE post;
                      TRUNCATE comment;
                      TRUNCATE tag;"
        )->execute();

    }


    private function createTestDatabase()
    {
        $install = false;
        $db = new \yuxblank\phackp\database\Database($this->dbConfig);
        $scriptPath = defined("SCRIPT_PATH") ? SCRIPT_PATH : "../scripts/";
        $install &= $db->query(file_get_contents($scriptPath."DDL.sql"))->execute();
        $install &= $db->query(file_get_contents($scriptPath."DML.sql"))->execute();
    }


}