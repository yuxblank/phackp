<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 15/04/2016
 * Time: 14:08
 */

namespace yuxblank\phackp\core;


use yuxblank\phackp\utils\ReflectionUtils;

class QueryBuilder
{

    private $query = '';


    public function __construct()
    {
    }

    public function select (string $table,array $properties) {
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
        $this->query .= 'INNER JOIN '
            . $child
            . ' ON ' . $parent .'.'.$parentJoin.'='.$child.'.'.$childJoin;
        return $this;
    }
    public function fullJoin(string $parent, string $parentJoin, string $child,  string $childJoin) {
        $this->query .= 'FULL JOIN '
            . $child
            . ' ON ' . $parent .'.'.$parentJoin.'='.$child.'.'.$childJoin;
        return $this;
    }
    public function leftJoin(string $parent, string $parentJoin, string $child,  string $childJoin) {
        $this->query .= 'LEFT JOIN '
            . $child
            . ' ON ' . $parent .'.'.$parentJoin.'='.$child.'.'.$childJoin;
        return $this;
    }
    public function rightJoin(string $parent, string $parentJoin, string $child,  string $childJoin) {
        $this->query .= 'RIGHT JOIN '
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

/*
 * SELECT tag.id, tag.tag FROM tag
         *   JOIN post_tag ON tag.id= post_tag.tag_id
         *   JOIN post ON post_tag.post_id=post.id;
 */

/*$queryBuilder = new QueryBuilder();
$queryBuilder->select('tag', array('*'))
    ->from(array('tag'))
    ->join('tag', 'id','post_tag','post_id')
    ->where('id=?');


$queryBuilder2 = new QueryBuilder();
$queryBuilder2->select('tag', array('*'))
    ->from(array('tag'))
    ->join('tag', 'id','post_tag','post_id')
    ->where('id=?')
    ->having('COUNT','tag.id','>1')
    ->order(array('tag.id ASC', 'tag.count DESC'))
    ->limit(0,10)
    ->union()
    ->select('x',array('*'));

$queryBuilder->addSubQueryBuilder($queryBuilder2);
print_r($queryBuilder);*/