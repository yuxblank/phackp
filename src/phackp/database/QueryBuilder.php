<?php
namespace yuxblank\phackp\database;

use yuxblank\phackp\core\Application;
use yuxblank\phackp\utils\ReflectionUtils;

class QueryBuilder
{

    private $query = '';


    public function select (array $properties) {
        $this->query .= 'SELECT '
            .implode(', ', $properties);
        return $this;
    }

    public function from(array $tables) {
        $this->query .= ' FROM ' . implode(', ', $tables);
        return $this;
    }

    public function where(string $conditions) {
        $this->query .= ' WHERE '
            . $conditions;
        return $this;
    }

    public function join(string $parent, string $parentJoin, string $child,  string $childJoin) {
        $this->query .= ' JOIN '
            . $child
            . ' ON ' . $parent .'.'.$parentJoin.'='.$child.'.'.$childJoin;
        return $this;
    }

    public function innerJoin(string $parent, string $parentJoin, string $child,  string $childJoin) {
        $this->query .= ' INNER JOIN '
            . $child
            . ' ON ' . $parent .'.'.$parentJoin.'='.$child.'.'.$childJoin;
        return $this;
    }
    public function fullJoin(string $parent, string $parentJoin, string $child,  string $childJoin) {
        $this->query .= ' FULL JOIN '
            . $child
            . ' ON ' . $parent .'.'.$parentJoin.'='.$child.'.'.$childJoin;
        return $this;
    }
    public function leftJoin(string $parent, string $parentJoin, string $child,  string $childJoin) {
        $this->query .= ' LEFT JOIN '
            . $child
            . ' ON ' . $parent .'.'.$parentJoin.'='.$child.'.'.$childJoin;
        return $this;
    }
    public function rightJoin(string $parent, string $parentJoin, string $child,  string $childJoin) {
        $this->query .= ' RIGHT JOIN '
            . $child
            . ' ON ' . $parent .'.'.$parentJoin.'='.$child.'.'.$childJoin;
        return $this;
    }

    public function groupBy(array $fields) {
        $this->query .= ' GROUP BY '. implode(', ', $fields);
        return $this;
    }

    public function having(string $function, string $subject, $condition) {
        $this->query .= ' HAVING '. $function .' (' . $subject .' ) ' . $condition;
        return $this;

    }

    public function order(array $orders) {
        $this->query .= ' ORDER BY ' . implode(', ', $orders);
        return $this;
    }

    public function limit(int $min, int $max) {
        $this->query .= ' LIMIT ' .$min . ',' . $max;
        return $this;
    }

    public function union () {
        $this->query .= ' UNION ';
        return $this;
    }
    public function unionAll () {
        $this->query .= ' UNION ALL ';
        return $this;
    }


    public function insert(string $table, array $fields) {
        $this->query .= ' INSERT INTO ' . $table
            . ' (' . implode(', ', $fields) . ') VALUES (';

        foreach ($fields as $field) {
            $this->query .= '?,';
        }
        $this->query = rtrim($this->query, ', ');
        $this->query .= ')';
        return $this;
    }

    public function update(string $table, array $fields) {
        $this->query .= ' UPDATE ' . $table .' SET ';
        for($i=0, $max = count($fields); $i<$max; $i++) {
            $this->query .= $fields[$i] . ' = ?, ';
        }
        $this->query = rtrim($this->query, ', ');
        return $this;
    }

    public function delete(string $table) {
        $this->query .= ' DELETE  FROM ' . $table;
        return $this;
    }




    public function addSubQueryBuilder(QueryBuilder $queryBuilder) {
        $this->query .= ' = ( ' . $queryBuilder->getQuery() . ' )';
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }



    private function resolveNamespace():string {
        return Application::getNameSpace()['MODEL'].get_class($this->object);
    }
    private function resolveTablename($object):string {
        return strtolower(ReflectionUtils::stripNamespace(get_class($object)));
    }

    private function resolveTables(array $objects) {
        $tables = array();
        foreach ($objects as $object) {
            $tables[] = $this->resolveTablename($object);
        }
        return $tables;

    }

}
