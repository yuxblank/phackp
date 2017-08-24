<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 03/08/2017
 * Time: 16:24
 */

namespace test\model;


use yuxblank\phackp\database\Model;

class Comment extends Model
{
    public $id;
    public $text;
    public $post_id;


    public function isValidComment(){
        return $this->post_id && $this->text;
    }

}