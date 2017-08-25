<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 25/08/2017
 * Time: 14:25
 */

namespace database;


use test\model\Category;
use test\model\Post;
use yuxblank\phackp\core\Application;
use yuxblank\phackp\database\Database;
use yuxblank\phackp\database\HackORM;

class HackORMTest extends \PHPUnit_Framework_TestCase
{
    /** @var  HackORM */
    private $hackORM;

    protected function setUp()
    {
        $path = defined("CONFIG_PATH") ? CONFIG_PATH : "../config/";
        $scriptPath = defined("SCRIPT_PATH") ? SCRIPT_PATH : "../scripts/";
        $appPath = defined("APP_PATH") ? APP_PATH : "../";
        $config = require $path."database.php";
        $db = new Database($config['database']);
        $db->query(file_get_contents($scriptPath."DDL.sql"));
        $db->execute();
        $db->query(file_get_contents($scriptPath."DML.sql"));
        $db->execute();
        $this->hackORM = new HackORM($db);

        /**
         * Create container to make Model instantiable
         */
        Application::getInstance();
        Application::getInstance()->bootstrap($appPath, $path);
        Application::getInstance()->container()->set(Database::class, $db);


    }

    public function testHackORMByModel(){
        $cat = new Category($this->hackORM);
        $this->assertNotNull($cat->findById(1));

        $cat->setTitle('HackORM');
        $cat->save();
        /** @var Category $catStored */
        $catStored = $cat->find('WHERE title=?', 'HackORM');
        $this->assertNotNull($catStored);

        $catStored->setTitle("HackORMupdate");
        $catStored->update();

        /** @var Category $catUpdated */
        $catUpdated = $cat->find('WHERE title=?', 'HackORMupdate');
        $this->assertNotNull($catUpdated);
        $catList = $catUpdated->findAll('WHERE title LIKE ?', '%Hack%');
        $this->assertCount(1,$catList);

        $catList = $catUpdated->findAll();
        /** @var Category $category */
        foreach ($catList as $category){
            $category->delete();
        }
        $this->assertCount(0,$cat->findAll());
    }

    public function testHackModelHasOne(){
        /** @var Category $Cat */
        $Cat = Category::make();
        $Cat->setTitle("hasOneCat");
        $Cat->save();

        /** @var Post $Post */
        $Post = Post::make();
        $Post->setTitle("hasOnePost");
        $Post->setCategoryId($Cat->find("WHERE title=?", "hasOneCat")->id);
        $Post->save();

        $this->assertInstanceOf(Category::class,
            Post::make()
                ->find("WHERE title=?", "hasOnePost")
                ->getCategory());


    }







    protected function tearDown()
    {

        /** Truncate all data */
        $this->hackORM->getDB()->query(
            'TRUNCATE category;
                      TRUNCATE post;
                      TRUNCATE comment;
                      TRUNCATE tag;'
        )->execute();
    }


}