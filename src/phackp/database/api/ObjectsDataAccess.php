<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 06/04/2016
 * Time: 17:28
 */

namespace yuxblank\phackp\database\api;


interface ObjectsDataAccess
{
    public function searchByKey($object, $id);
    public function search($object, string $filter, array $filterParams);
    public function searchAll($object, string $filter=null, array $filterParams):array;
    public function countObjects($object, string $filter=null, array $filterParams):int;
    public function persist($object);
    public function merge($object);
    public function remove($object, $id=null);
}