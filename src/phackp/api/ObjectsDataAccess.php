<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 06/04/2016
 * Time: 17:28
 */

namespace yuxblank\phackp\api;


interface ObjectsDataAccess
{
    public function findAsArray($object, $filter, $filterParams): array; // todo make a custom array class
    public function findAs($object, $filter, $filterParams):\stdClass;
    public function findAsAll($object, $filter, $filterParams):array; // todo make a custom Collection class
    public function findById($object,$id); //todo think, is always available?
    public function countObjects($object):int;
    public function save($object):bool;
    public function update($object):bool;
}