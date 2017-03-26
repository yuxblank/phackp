<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 06/04/2016
 * Time: 17:28
 */

namespace yuxblank\phackp\database;


interface ObjectsDataAccess
{
    public function searchByKey($object, $id);
    public function search($object, string $filter, ...$filterParams);
    public function searchAll($object, string $filter=null, ...$filterParams):array;
    public function countObjects($object, string $filter=null, ...$filterParams):int;
    public function persist($object);
    public function merge($object);
    public function remove($object, $id=null);
}