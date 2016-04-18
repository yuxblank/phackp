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

    private $object;
    private $query = '';
    private $table;


    public function __construct($object)
    {
        $this->object = $object;
        $this->table = $this->resolveTablename($this->object);
    }

    public function select () {
        $this->query .= 'SELECT '
            .implode(', ', ReflectionUtils::getProperties($this->object))
            . ' FROM '
            . $this->resolveTablename($this->object);
        return $this;
    }

    public function where(string $conditions) {
        $this->query .= ' WHERE '
            . $conditions;
        return $this;
    }

    public function join($object, $first, $second, $type='inner') {

        $this->query .= ' JOIN '
            . $this->_resolveTablename($object)
            . ' ON ' . $this->table .'.'.$first.'='.$this->resolveTablename($object).'.'.$second;
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
    private function resolveTablename():string {
        return strtolower(NamespaceParser::stripNamespace(get_class($this->object)));
    }
    private function _resolveTablename($object):string {
        return NamespaceParser::stripNamespace(get_class($object));
    }

    private function resolveTables(array $objects) {
        $tables = array();
        foreach ($objects as $object) {
            $tables[] = $this->resolveTablename($object);
        }
        return $tables;

    }

}