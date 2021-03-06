<?php

use yuxblank\phackp\core\Application;
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
        $path = defined("CONFIG_PATH") ? CONFIG_PATH : "../config/";
        $scriptPath = defined("SCRIPT_PATH") ? SCRIPT_PATH : "../scripts/";
        $appPath = defined("APP_PATH") ? APP_PATH : "../";
        $this->config = require $path."database.php";
        $this->database = new Database($this->config['database']);
        $this->database->query(file_get_contents($scriptPath."DDL.sql"));
        $this->database->execute();
        $this->createDML();
        /**
         * Create container to make Model instantiable
         */
        Application::getInstance();
        Application::getInstance()->bootstrap($appPath, $path);
        Application::getInstance()->container()->set(Database::class, $this->database);
    }


    protected function tearDown()
    {
        parent::tearDown();

        /** Truncate all data */
        $this->database->query(
            'TRUNCATE category;
                      TRUNCATE post;
                      TRUNCATE comment;
                      TRUNCATE tag;'
        );
        $this->database->execute();

    }


    public function testSavePost()
    {
        $title = "This is a post!";
        $content = htmlspecialchars("<div>Some html content</div>") ;

        $post = new \test\model\Post();
        $post->setCategoryId(1);
        $post->setTitle($title);
        $post->setContent($content);
        $post->setDateCreated(date("Y-m-d H:i:s"));

        $this->database->query("INSERT INTO post VALUES (NULL,?,?,?,?)");
        $this->database->bindValue(1,$post->getTitle());
        $this->database->bindValue(2,$post->getContent());
        $this->database->bindValue(3,$post->getCategoryId());
        $this->database->bindValue(4,$post->getDateCreated());
        $this->database->execute();

        $this->database->query("SELECT * FROM post WHERE title=?");
        $this->database->bindValue(1,$title);
        /** @var \test\model\Post $result */
        $result = $this->database->fetchSingleClass( \test\model\Post::class);

        $this->assertNotNull($result);
        $this->assertEquals($content, $result->getContent());
    }

    public function testBuilderJoin(){

        $this->storePost();

        $builder = new \yuxblank\phackp\database\QueryBuilder();
        $builder->
        select(["*"])
            ->from(['post'])
            ->innerJoin("post","category_id","category","id")
            ->where("category.id=?");

        $this->database->query($builder->getQuery());
        $this->database->bindValue(1,1);

        $resultList = $this->database->fetchClassSet(\test\model\Post::class);

        /** asserts */
        $this->assertNotNull($resultList);
        $this->assertTrue(count($resultList)>0);
        $this->assertTrue($resultList[0] instanceof \test\model\Post);
    }

    public function testQuery() {
        $this->database->query("SELECT * FROM category");
        $results = $this->database->resultList();
        $this->assertCount(1,$results);
        $obj = $this->database->fetchObjectSet();
        $this->assertInstanceOf(stdClass::class, $obj[0]);
        $class = $this->database->fetchClassSet(\test\model\Category::class);
        $this->assertInstanceOf(\test\model\Category::class, $class[0]);
    }

    public function testQuerySingleResult(){
        $this->database->query('SELECT * FROM category WHERE id=?');
        $this->database->paramsBinder([1]);
        $cat = $this->database->singleResult();
        $this->assertNotEmpty($cat);
        $obj = $this->database->fetchObj();
        $this->assertInstanceOf(stdClass::class, $obj);
        $class = $this->database->fetchSingleClass(\test\model\Category::class);
        $this->assertInstanceOf(\test\model\Category::class, $class);
    }

    public function testCount() {
        $this->database->query("SELECT * FROM category");
        $count = $this->database->rowCount();
        $this->assertEquals(1,$count);
    }

    public function testPDO(){
        $PDO = $this->database->getPDO();
        $this->assertInstanceOf(PDO::class,$PDO);
    }



    /**
     * Private methods
     */
    private function createDML(){
        $this->database->query("INSERT INTO category VALUES (NULL,'testcat')");
        $this->database->execute();

    }
    private function storePost(){
        $this->database->query("INSERT INTO post VALUES (NULL,'prova','<div>some content</div>',1,NULL)")->execute();
    }


}