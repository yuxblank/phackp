<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 06/04/2016
 * Time: 17:42
 */

namespace yuxblank\phackp\api;


interface ObjectRelationalMapping
{
    public function oneToOne($a,$b);
    public function oneToMany($a,$b):array;
    public function manyToOne($a,$b);
    public function manyToMany($a,$b);

}