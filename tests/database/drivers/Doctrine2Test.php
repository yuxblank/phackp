<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 25/08/2017
 * Time: 11:41
 */

namespace database\drivers;


use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use test\doctrine\model\Post;
use test\tools\DbTools;
use yuxblank\phackp\database\driver\DoctrineDriver;

class Doctrine2Test extends \PHPUnit_Framework_TestCase
{
    /** @var  EntityManagerInterface */
    private $em;

    /** @var  ObjectRepository */
    private $postRepository;

    protected function setUp()
    {
        $path = defined('CONFIG_PATH') ? CONFIG_PATH : '../../config/';
        $scriptPath = defined('SCRIPT_PATH') ? SCRIPT_PATH : '../../scripts/';
        $dbConfig = require $path . 'doctrine.php';
        $driver = new DoctrineDriver($dbConfig['doctrine.config']);
        $this->em = $driver->getDriver();
        DbTools::createDatabase();

        $this->postRepository = $this->em->getRepository(Post::class);
    }

    public function testEntityManagerConnection()
    {
        $this->em->beginTransaction();
        $this->assertTrue($this->em->getConnection()->isConnected());
        $this->em->close();
    }

    public function testRepository()
    {
        $this->assertNotEmpty($this->postRepository->findAll());
        $this->assertNotEmpty($this->postRepository->find(1));
    }

    public function testStoreRepository()
    {
        $post = new Post();
        $post->setContent(htmlentities("<div> this is stored by Doctrine2</div>"));
        $post->setTitle("Doctrine2 post");
        $post->setDateCreated(new \DateTime());

        $this->em->beginTransaction();
        $this->em->persist($post);
        $this->em->flush();
        $this->em->close();

        $postFromDb = $this->postRepository->findOneBy(['title' => 'Doctrine2 post']);

        $this->assertEquals($postFromDb, $post);
    }

    protected function tearDown()
    {
        $this->em->getConnection()->close();
        $this->em->close();
    }


}