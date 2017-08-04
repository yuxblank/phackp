<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 04/08/2017
 * Time: 09:46
 */

namespace yuxblank\phackp\database\api;


use Doctrine\ORM\EntityManagerInterface;

interface EntitiyManagerDriver
{

    /**
     * @return EntityManagerInterface
     */
    public function getDriver():EntityManagerInterface;

}