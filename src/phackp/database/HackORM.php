<?php

namespace yuxblank\phackp\database;
use yuxblank\phackp\core\Application;
use yuxblank\phackp\database\api\ObjectRelationalMapping;
use yuxblank\phackp\database\api\ObjectsDataAccess;
use yuxblank\phackp\utils\ReflectionUtils;

/**
 * Class HackORM
 * @package yuxblank\phackp\core
 * @author Yuri Blanc
 */
class HackORM implements ObjectRelationalMapping, ObjectsDataAccess
{

    /**
     * @Inject
     * @var Database
     */
    private $db;

    public function __construct(Database $database)
    {
        $this->db = $database;
    }


    /**
     * @return Database
     */
    public function getDB()
    {
        return $this->db;
    }

    /**
     * Query and return an associative array of one result.
     * @param $object
     * @param $query
     * @param $params
     * @return mixed
     */
    public function _search($object, string $query = null, array $params): array
    {
        $table = $this->objectInjector($object);
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->select(ReflectionUtils::getProperties($object))
            ->from(array($table));
        $query = $query === null ? $queryBuilder->getQuery() : $queryBuilder->getQuery() . ' ' . $query;
        $this->db->query($query);
        $this->db->paramsBinder($params);
        $this->db->execute();
        return $this->db->singleResult();
    }

    /**
     * Query and return an array of associative arrays of results.
     * @param $object
     * @param $query
     * @param $params
     * @return mixed
     */
    public function _searchAll($object, string $query = null, array $params): array
    {
        $table = $this->objectInjector($object);
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->select(ReflectionUtils::getProperties($object))
            ->from(array($table));
        $query = $query === null ? $queryBuilder->getQuery() : $queryBuilder->getQuery() . ' ' . $query;
        $this->db->query($query);
        $this->db->paramsBinder($params);
        $this->db->execute();
        return $this->db->resultList();
    }

    /**
     * Returns a stdClass representation of the target table.
     * @deprecated
     * @param mixed $object
     * @param string $query
     * @param mixed[] $params
     * @return \stdClass
     */
    public function findAs($object, $query, $params)
    {
        $table = $this->objectInjector($object);
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->select(ReflectionUtils::getProperties($object))
            ->from(array($table));
        $query = $query === null ? $queryBuilder->getQuery() : $queryBuilder->getQuery() . ' ' . $query;
        $this->db->query($query);
        $this->db->paramsBinder($params);

        return $this->db->fetchObj();
    }

    /**
     * Returns an array of stdClass representation of the target table.
     * @deprecated
     * @param mixed $object
     * @param string $query
     * @param mixed[] $params
     * @return \stdClass[]
     */
    public function findAsAll($object, $query, $params)
    {
        $table = $this->objectInjector($object);
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->select(ReflectionUtils::getProperties($object))
            ->from(array($table));
        $query = $query === null ? $queryBuilder->getQuery() : $queryBuilder->getQuery() . ' ' . $query;
        $this->db->query($query);
        $this->db->paramsBinder($params);

        return $this->db->fetchObjectSet();
    }

    /**
     * Find an object instance using the table id. Table primary key must be called id.
     * @param mixed $object
     * @param int $id
     * @return mixed Object instance
     **/

    public function searchByKey($object, $id)
    {
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->select(ReflectionUtils::getProperties($object))
            ->from(array($this->objectInjector($object)))
            ->where('id=?');
        $this->db->query($queryBuilder->getQuery());
        $this->db->bindValue(1, $id);
        return $this->db->fetchSingleClass($object);
    }

    /**
     * Build a query using an object and returning that object instance from the datasource.
     * The object passed is used as table name, converted in lowercase. (e.g. Posts() = table post).
     * @param string $object
     * @param string $query
     * @param mixed[] $params array with all params data non assoc.
     * @return mixed
     */
    public function search($object, string $query, array $params)
    {
        $table = $this->objectInjector($object);
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->select(ReflectionUtils::getProperties($object))
            ->from(array($table));

        $query = $query === null ? $queryBuilder->getQuery() : $queryBuilder->getQuery() . ' ' . $query;
        $this->db->query($query);
        $this->db->paramsBinder($params);
        return $this->db->fetchSingleClass($object);
    }

    public function searchAll($object, string $query = null, array $params): array
    {
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->select(ReflectionUtils::getProperties($object))
            ->from(array($this->objectInjector($object)));
        $query = $query === null ? $queryBuilder->getQuery() : $queryBuilder->getQuery() . ' ' . $query;
        $this->db->query($query);
        $this->db->paramsBinder($params);
        return $this->db->fetchClassSet($object);
    }

    /**
     * Counts the occurrences of a give object type
     * @param string $object
     * @param string $query
     * @param array $params
     * @return int
     */
    public function countObjects($object, string $query = null, array $params): int
    {
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->select(array(' COUNT(*) '))
            ->from(array($this->objectInjector($object)));

        $query = $query === null ? $queryBuilder->getQuery() : $queryBuilder->getQuery() . ' ' . $query;
        $this->db->query($query);
        $this->db->paramsBinder($params);
        return $this->db->rowCount();
    }

    /**
     * Persist an object to the target database
     * @param mixed $object
     * @return mixed
     */
    public function persist($object)
    {
        $table = $this->objectInjector($object);
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->insert($table, ReflectionUtils::getInstanceProperties($object));
        $this->db->query($queryBuilder->getQuery());
        $this->db->paramsBinder(ReflectionUtils::getInstancePropertiesValues($object));
        return $this->db->execute();
    }

    /**
     * Update an object instance in the data-layer
     * @param mixed $object
     * @return bool
     */
    public function merge($object)
    {
        $table = $this->objectInjector($object);
        $properties = $this->db->excludeId(ReflectionUtils::getDeferredInstanceProperties($object));
        $queryBuilder = new QueryBuilder();

        $queryBuilder
            ->update($table, array_keys($properties))
            ->where('id=?');
        $this->db->query($queryBuilder->getQuery());
        $this->db->paramsBinder(array_values($properties));
        $this->db->bindValue(count($properties) + 1, $object->id);
        return $this->db->execute();
    }

    /**
     * Delete an object instance in the data-layer
     * @param mixed $object
     * @param int $id
     * @return bool
     */
    public function remove($object, $id = null)
    {
        $table = $this->objectInjector($object);
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->delete($table)
            ->where('id=?');
        $this->db->query($queryBuilder->getQuery());
        $this->db->bindValue(1, $id != null ? $id : $object->id);
        return $this->db->execute();
    }

    /**
     * Create a 1-1 relationship from two objects. Returns the target object of the relationship.
     * e.g (Posts() 1 <=> 1 Category())
     * The parent table must contain table_id of the foreign key. (e.g. post contains category_id column)
     * @param mixed $object
     * @param string $target
     * @return mixed
     */
    public function oneToOne($object, $target)
    {
        $parent = $this->objectInjector($object);
        $child = $this->objectInjector($target);
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->select($this->db->setTableToProperties(ReflectionUtils::getProperties($target), $child))
            ->from(array($child))
            ->innerJoin($child, 'id', $parent, $child . '_id')
            ->where($parent . '.id=?');
        $this->db->query($queryBuilder->getQuery());
        $this->db->bindValue(1, $object->id);
        return $this->db->fetchSingleClass($target);
    }

    /**
     * Create 1-N relationship from two objects. Returns an array of target objects of the relationship.
     * e.g. (Posts() 1 <=> N Tags())
     * The child table should contain the parent id. (e.g. tags contains post_id column)
     * @param mixed $object
     * @param string $target
     * @return array
     */
    public function oneToMany($object, $target)
    {
        $parent = $this->objectInjector($object);
        $child = strtolower(ReflectionUtils::stripNamespace($target));
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->select($this->db->setTableToProperties(ReflectionUtils::getProperties($target), $child))
            ->from(array($child))
            ->where($parent . '_id =?');
        $this->db->query($queryBuilder->getQuery());
        $this->db->bindValue(1, $object->id);
        return $this->db->fetchClassSet($target);
    }

    /**
     * * Create N-1 relationship from two objects. Returns a single target   of the relationship.
     * Is the inverse of oneToMany
     * e.g. (Posts() N <=> 1 Tags())
     * The parent table should contain the child id. (e.g. post contains tags_id column)
     * @param $object
     * @param $target
     * @return mixed
     */
    public function manyToOne($object, $target)
    {
        // todo correct query
        $parent = $this->objectInjector($object);
        $child = $this->objectInjector($target);
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->select($this->db->setTableToProperties(ReflectionUtils::getProperties($target), $child))
            ->from(array($child))
            ->where("id");
        //->innerJoin($child, 'id', $parent, $child .'_id');
        $subQueryBuilder = new QueryBuilder();
        $subQueryBuilder
            ->select(array($child . "_id"))
            ->from(array($parent))
            ->where("id=?");
        // $query = "SELECT * FROM $child WHERE id = (SELECT ". $child ."_id FROM $parent WHERE id=?)";
        $queryBuilder->addSubQueryBuilder($subQueryBuilder);
        $this->db->query($queryBuilder->getQuery());
        $this->db->bindValue(1, $object->id);
        return $this->db->fetchSingleClass($target);
    }

    /**
     * Return a collection of objects of a N to N relationship. The table must be called $object_$target, the N/N table must contain
     * $object_id reference. The table names uses the convention of lowercase (@see ObjectInjector).
     * Return an ArrayObject of the $target object class.
     * * N/T Table must be called parent_child
     * @param mixed $object
     * @param string $target
     * @return array
     */
    public function manyToMany($object, $target)
    {
        $parent = $this->objectInjector($object);
        $child = strtolower(ReflectionUtils::stripNamespace($target));
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->select($this->db->setTableToProperties(ReflectionUtils::getProperties($target), $child))
            ->from(array($child))
            ->innerJoin($child, 'id', $parent . '_' . $child, $child . '_id');
        $this->db->query($queryBuilder->getQuery());
        return $this->db->fetchClassSet($target);
    }

    /**
     * Return a collection of objects of a N to N relationship. The table must be called $object_$target, the N/N table must contain
     * $object_id reference. The table names uses the convention of lowercase (@see ObjectInjector).
     * Return an ArrayObject of the $target object class.
     * N/T Table must be called child_parent
     * @param mixed $object
     * @param string $target
     * @return array
     */
    public function _manyToMany($object, $target)
    {
        $parent = $this->objectInjector($object);
        $child = strtolower(ReflectionUtils::stripNamespace($target));
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->select($this->db->setTableToProperties(ReflectionUtils::getProperties($target), $child))
            ->from(array($child))
            ->innerJoin($child, 'id', $child . '_' . $parent, $child . '_id');
        $this->db->query($queryBuilder->getQuery());
        return $this->db->fetchClassSet($target);
    }


    private function objectInjector($object)
    {
        if (is_string($object)){
            return strtolower(ReflectionUtils::stripNamespace($object));
        }
        return strtolower(ReflectionUtils::stripNamespace(get_class($object)));
    }

    private function objectRelocator($object)
    {
        return Application::getNameSpace()['MODEL'] . $object;
    }


}