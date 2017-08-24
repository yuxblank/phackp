<?php
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 11/04/2016
 * Time: 19:38
 */

namespace test\model;



use yuxblank\phackp\database\HackORM;
use yuxblank\phackp\database\Model;

class Post extends Model
{
    public $id;
    public $title;
    public $content;
    public $category_id;
    public $date_created;



    public function setExampleNoDbPost(){
        $this->id = 1;
        $this->title = 'No db title post';
        $this->content = 'No db content';
        $this->category_id = 1;
        return $this;
    }


    public function tags() {
        return $this->hasManyThrough(Tag::class);
    }


    public function tag() {
        return $this->hasMany(Tag::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @param mixed $category_id
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * @param mixed $date_created
     */
    public function setDateCreated($date_created)
    {
        $this->date_created = $date_created;
    }

    public function getCategory(){
        return $this->hasOne(Category::class);
    }





}