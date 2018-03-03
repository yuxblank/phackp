<?php

namespace test\controller;

/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 24/08/2017
 * Time: 14:52
 */

use test\doctrine\repository\PostRepository;
use test\model\Post;
use yuxblank\phackp\core\Controller;
use yuxblank\phackp\http\api\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class RestController extends Controller
{


    private $postRepository;

    /**
     * RestController constructor.
     */
    public function __construct(PostRepository $postRepository)
    {
        parent::__construct();
        $this->postRepository = $postRepository;

    }

    public function onBefore()
    {
        // TODO: Implement onBefore() method.
    }

    public function onAfter()
    {
        // TODO: Implement onAfter() method.
    }


    public function testRestPost(ServerRequestInterface $serverRequest)
    {

        $post = new \test\doctrine\model\Post();
        if ($serverRequest)

            if ($body = $serverRequest->getParsedBody()) {
                $post->setTitle($body['title']);
                $post->setContent(htmlentities($body['content']));
                $this->postRepository->savePost($post);
                return new JsonResponse($this->postRepository->getPosts());
            }
        return $this->jsonReturnKO();
    }

    public function testRestPut(ServerRequestInterface $serverRequest)
    {

        $postId = $serverRequest->getPathParams() ?
            filter_var($serverRequest->getPathParams()['id'], FILTER_SANITIZE_NUMBER_INT)
            : null;

        /** @var Post $post */
        $post = Post::make()->findById($postId);
        if ($serverRequest && $post)
            if ($body = $serverRequest->getParsedBody()) {
                $post->setTitle($body['title']);
                $post->setCategoryId($body['category_id']);
                $post->setContent(htmlentities($body['content']));
                if ($post->update()) {
                    return $this->jsonReturnOK();
                }
                return $this->jsonReturnKO();
            }
        return $this->jsonReturnKO();
    }

    public function testThrowException(ServerRequestInterface $serverRequest)
    {
        throw new \RuntimeException("TEST_EXCEPTION");
    }


    private function jsonReturnKO()
    {
        return new JsonResponse(["result" => "KO"], 503);
    }

    private function jsonReturnOK()
    {
        return new JsonResponse(["result" => "OK"]);
    }

}