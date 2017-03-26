<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 06/04/2016
 * Time: 17:42
 */

namespace yuxblank\phackp\database\api;


interface ObjectRelationalMapping
{
    /** Belongs to */
    public function oneToOne($a,$b);
    /** has_many */
    public function oneToMany($a,$b);
    /** Has one */
    public function manyToOne($a,$b);
    /** Has Many Through */
    public function manyToMany($a,$b);

}