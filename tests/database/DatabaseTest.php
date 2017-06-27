<?php
use yuxblank\phackp\database\Database;

/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 26/06/2017
 * Time: 14:48
 */
class DatabaseTest extends PHPUnit_Framework_TestCase
{

    /** @var  Database */
    private $database;
    private $config;

    public function setUp()
    {
        $this->config = require "../config/database.php";
        $this->database = new Database($this->config['app.database']);

    }


    public function testConnection(){

        $this->database->query("CREATE TABLE PHACKPTEST");
        $this->database->execute();
    }


}