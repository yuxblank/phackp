<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 15/04/2016
 * Time: 11:12
 */

namespace test\model;


use yuxblank\phackp\database\Model;

class Tag extends Model
{
    public $id;
    public $tag;
    public $post_id;



    public function posts() {
        return $this->belongsTo( Post::class);
    }

}