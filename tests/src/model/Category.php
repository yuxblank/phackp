<?php
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 11/04/2016
 * Time: 19:39
 */

namespace model;



use yuxblank\phackp\database\Model;

class Category extends Model
{

    public $id;
    public $title;

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



}