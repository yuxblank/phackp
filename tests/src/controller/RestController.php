<?php
namespace test\controller;
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 24/08/2017
 * Time: 14:52
 */

use test\model\Post;
use yuxblank\phackp\core\Controller;
use yuxblank\phackp\http\api\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class RestController extends Controller
{
    public function onBefore()
    {
        // TODO: Implement onBefore() method.
    }

    public function onAfter()
    {
        // TODO: Implement onAfter() method.
    }


    public function testRestPost(ServerRequestInterface $serverRequest){

        /** @var Post $post */
        $post = Post::make();
        if ($serverRequest)

        if ($body = $serverRequest->getParsedBody()){
            $post->setTitle($body['title']);
            $post->setCategoryId($body['category_id']);
            $post->setContent(htmlentities($body['content']));
            if ($post->save()){
                return new JsonResponse(["result" => "OK"]);
            }
            return new JsonResponse(["result" => "KO"],503);
        }
        return new JsonResponse(["result" => "KO",503]);
    }

}