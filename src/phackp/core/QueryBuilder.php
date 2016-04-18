<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 15/04/2016
 * Time: 14:08
 */

namespace yuxblank\phackp\core;


use yuxblank\phackp\utils\NamespaceParser;
use yuxblank\phackp\utils\ReflectionUtils;

class QueryBuilder
{

    private $query = '';


    public function __construct()
    {
    }

    public function select ($object) {
        $this->query .= 'SELECT '
            .implode(', ', ReflectionUtils::getProperties($object))
            . ' FROM '
            . $this->resolveTablename($object);
        return $this;
    }

    public function where(string $conditions) {
        $this->query .= ' WHERE '
            . $conditions;
        return $this;
    }

    public function join($parent,$child, $first, $second, $type='inner') {

        $this->query .= ' JOIN '
            . $this->resolveTablename($child)
            . ' ON ' . $this->resolveTablename($parent) .'.'.$first.'='.$this->resolveTablename($child).'.'.$second;
        return $this;
    }

    public function groupBy(array $fields) {

    }

    public function having() {

    }

    public function order() {

    }

    public function limit() {

    }



    private function resolveNamespace():string {
        return Application::getNameSpace()['MODEL'].get_class($this->object);
    }
    private function resolveTablename($object):string {
        return strtolower(NamespaceParser::stripNamespace(get_class($object)));
    }

    private function resolveTables(array $objects) {
        $tables = array();
        foreach ($objects as $object) {
            $tables[] = $this->resolveTablename($object);
        }
        return $tables;

    }

}