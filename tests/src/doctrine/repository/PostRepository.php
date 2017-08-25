<?php
namespace test\doctrine\repository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Repository\RepositoryFactory;
use test\doctrine\model\Post;

/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 04/08/2017
 * Time: 11:09
 */

class PostRepository
{

    private $em;
    private $postRepository;


    /**
     * Entity manager is injected by the container
     * PostRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $this->postRepository = $this->em->getRepository(Post::class);
    }

    public function savePost(Post $post) {
        $this->em->persist($post);
        $this->em->flush();
    }

    public function getPosts() {
        return $this->postRepository->findAll();
    }


}