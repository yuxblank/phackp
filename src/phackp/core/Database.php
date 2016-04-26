<?php
namespace yuxblank\phackp\core;
//use Exception;
use PDO;
/*
 * Copyright (C) 2015 yuri.blanc
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
use yuxblank\phackp\api\ObjectRelationalMapping;
use yuxblank\phackp\api\ObjectsDataAccess;
use yuxblank\phackp\utils\ReflectionUtils;

/**
 * This class is a API based on top of PDO. The class allow query building, Object relationship mapping and db access.
 * The class can be used as an object instance but is rather better to extends your persistent objects with Model superclass that provide better API to this class
 * @author yuri.blanc
 * @version 0.2
 */

class Database implements ObjectRelationalMapping, ObjectsDataAccess{
    private $pdo;
    private $stm;
    private $dbDriver;
    private $dbHost;
    private $dbName;
    private $dbUser;
    private $dbPwd;
    private $options;
    /**
     * TODO exception handlers
     * Constructor connects to database
     */
    public function __construct() {
        $database = Application::getDatabase();
        $dsn = $database['DRIVER'] . ':host=' . $database['HOST'] . ";dbname=" . $database['NAME'];
        try {
            $this->pdo = new PDO($dsn, $database['USER'], $database['PSW']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        } catch (PDOException $ex) {
            $ex->getMessage();

        }

    }

    /**
     * Query and return an associative array of one result.
     * @param $object
     * @param $query
     * @param $params
     * @return mixed
     */
    public function findAsArray($object,$query,$params) {
        $table = $this->objectInjector($object);
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->select(ReflectionUtils::getProperties($object))
            ->from(array($table))
            ->where($query);
        $this->query($queryBuilder->getQuery());
        $this->paramsBinder($params);
        $this->execute();
        return $this->singleResult();
    }

    /**
     * Query and return an array of associative arrays of results.
     * @param $object
     * @param $query
     * @param $params
     * @return mixed
     */
    public function findAllAsArray($object,$query,$params) {
        $table = $this->objectInjector($object);

        $queryBuilder = new QueryBuilder();

        $queryBuilder
            ->select(ReflectionUtils::getProperties($object))
            ->from(array($table))
            ->where($query);
        $this->query($queryBuilder->getQuery());
        $this->paramsBinder($params);
        $this->execute();
        return $this->resultList();
    }
    /**
     * Returns a stdClass represention of the target table.
     * @param string $query
     * @param mixed[] $params
     * @return stdClass
     */
    public function findAs($object,$query, $params) {
        $table = $this->objectInjector($object);
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->select(ReflectionUtils::getProperties($object))
            ->from(array($table));

        if ($query!==null && $params!==null) {
            $queryBuilder
                ->where($query);
            $this->query($queryBuilder->getQuery());
            $this->paramsBinder($params);
        } else {
            $this->query($queryBuilder->getQuery());
        }
        return $this->stm->fetch(PDO::FETCH_OBJ);
    }
    /**
     * Returns an array of stdClass represention of the target table.
     * @param string $query
     * @param mixed[] $params
     * @return stdClass[]
     */
    public function findAsAll($object, $query, $params) {
        $table = $this->objectInjector($object);
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->select(ReflectionUtils::getProperties($object))
            ->from(array($table));

        if ($query!==null && $params!==null) {
            $queryBuilder
                ->where($query);
            $this->query($queryBuilder->getQuery());
            $this->paramsBinder($params);
        } else {
            $this->query($queryBuilder->getQuery());
        }
        return $this->stm->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Build a query using an object and returning that object instance from the datasource.
     * The object passed is used as table name, converted in lowercase. (e.g. Posts() = table post).
     * @param string $object
     * @param string $query
     * @param mixed[] $params array with all params data non assoc.
     * @return object
     */
    public function find($object,$query,$params) {
        $table = $this->objectInjector($object);
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->select(ReflectionUtils::getProperties($object))
            ->from(array($table));

        if ($query!==null && $params!==null) {
            $queryBuilder
                ->where($query);
            $this->query($queryBuilder->getQuery());
            $this->paramsBinder($params);
        } else {
            $this->query($queryBuilder->getQuery());
        }

        return $this->fetchSingleObject($object);
    }
    /**
     * Find an object instance using the table id. Table primary key must be called id.
     * @param object $object
     * @param int    $id
     * @return object Object instance
     **/
    public function findById($object, $id) {
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->select(ReflectionUtils::getProperties($object))
            ->from(array($this->objectInjector($object)))
            ->where('id=?');
        $this->query($queryBuilder->getQuery());
        $this->bindValue(1,$id);
        return $this->fetchSingleObject($object);
    }
    /**
     * Find all instances of a given object
     * @param object $object
     * @param string $query
     * @param array $values
     * @param int $current
     * @param int $min
     * @param int $max
     * @return list
     */
    /*  public function findAll($object,$query=null,$values=null,$current=null,$max=null,$order=null) {
          try {
              $table = $this->objectInjector($object);
          } catch (Exception $e) {
              return;
          }
          $isWhere = $query!=null ? ' WHERE ' : '';
          $statement = "SELECT * FROM " . $table.$isWhere.$query;
          if (isset($current) && isset($max)) {
              if(isset($order)) {
                  $statement.=" ".$order;
              }
              $statement.= " LIMIT ?, ? ";
          }
          $this->query($statement);
          $lastValue = 0;
          if (isset($query) && isset($values)){
              foreach ($values as $key => $value) {
                  $key++; // + 1 for bindParams
                  $this->bindValue($key, $value);
                  $lastValue++;
              }
          }
          if (isset($current) && isset($max)) {
              $this->bindValue(++$lastValue, $current, PDO::PARAM_INT);
              $this->bindValue(++$lastValue, $max, PDO::PARAM_INT);

          }
          return $this->fetchObjectSet($object);
      }*/


    public function findAll ($object, $query=null, $params=null) {
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->select(ReflectionUtils::getProperties($object))
            ->from(array($this->objectInjector($object)));
        $isQuery = $query!==null ? $queryBuilder->where($query) : false;
        $this->query($queryBuilder->getQuery());
        if ($isQuery) {
            $this->paramsBinder($params);
        }
        return $this->fetchObjectSet($object);
    }



    /**
     * Counts the occurrencies of a give object type
     * @param string $object
     * @return int
     */

    public function countObjects($object) {
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->select(array(' COUNT(*) '))
            ->from(array($this->objectInjector($object)));
        $this->query($queryBuilder->getQuery());
        return $this->rowCount();
    }

    public function _countObjects($object,$query,$params) {
        $table = $this->objectInjector($object);
        $query = 'SELECT COUNT(*) FROM '. $table . ' WHERE ' . $query;
        $this->query($query);
        $this->paramsBinder($params);
        return $this->rowCount();
    }


    /**
     * Persist an object to the target database
     * @param $object
     * @return mixed
     */
    public function save($object) {
        $table = $this->objectInjector(get_class($object));
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->insert($table, ReflectionUtils::getInstanceProperties($object));
        $this->query($queryBuilder->getQuery());
        $this->paramsBinder(ReflectionUtils::getInstancePropertiesValues($object));
        return $this->execute($object);
    }
    /**
     * Update an object instance in the data-layer
     * @param object $object
     * @param int $id
     */
    /*public function update($object) {
        $table = $this->objectInjector(get_class($object));
        $statement = "UPDATE $table SET ";
        $values="";
        foreach ($object as $key => $value) {
            if(isset($value) && $value!=null) {
                $values .= $key . "=:" . $key . ",";
            }
        }
        $values = substr($values, 0, -1);
        $values.=" WHERE id=:id";
        $statement.=$values;

        $this->stm = $this->pdo->prepare($statement);

        foreach ($object as $key =>$value) {
            if(isset($value) && $value!=null) {
                $this->bindValue($key, $value);
            }
        }


        return $this->execute();
    }*/
    public function update($object) {
        $table = $this->objectInjector(get_class($object));
        $properties =  $this->excludeId(ReflectionUtils::getDeferredInstanceProperties($object));
        $queryBuilder = new QueryBuilder();

        $queryBuilder
            ->update($table, array_keys($properties))
            ->where('id=?');
        $this->query($queryBuilder->getQuery());
        $this->paramsBinder(array_values($properties));
        $this->bindValue(count($properties)+1, $object->id);
        return $this->execute();
    }
    /**
     * Delete an object instance in the data-layer
     * @param object $object
     * @param int $id
     * @return bool
     */
    public function delete($object,$id) {
        $table = $this->objectInjector($object);
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->delete($table)
            ->where('id=?');
        $this->query($queryBuilder->getQuery());
        $this->bindValue(1, $id);
        return $this->stm->execute();
    }
    /**
     * Return last inserted id
     * @return int
     */
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
    /**
     * @final
     * Override DB config for current object state
     * @param type $dbDriver
     * @param type $dbHost
     * @param type $dbName
     * @param type $dbUser
     * @param type $dbPassword
     * @param type $options
     */

    // ::: ORM ::: \\
    // MUST BE OBJECTS
    /**
     * Create a 1-1 relationship from two objects. Returns the target object of the relationship.
     * e.g (Posts() 1 <=> 1 Category())
     * The parent table must contain table_id of the foreign key. (e.g. post contains category_id column)
     * @param object $object
     * @param string $target
     * @return object
     */
    public function oneToOne($object, $target) {
        $parent = $this->objectInjector(get_class($object));
        $child = $this->objectInjector($target);
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->select($this->setTableToProperties(ReflectionUtils::getProperties($target), $child))
            ->from(array($child))
            ->innerJoin($child,$parent.'_id',$parent,'id')
            ->where($parent.'.id=?');
        $this->query($queryBuilder->getQuery());
        $this->bindValue(1,$object->id);
        return $this->fetchObjectSet($this->objectRelocator($child));
    }
    /**
     * Create 1-N relationship from two objects. Returns an array of target objects of the relationship.
     * e.g. (Posts() 1 <=> N Tags())
     * The child table should contain the parent id. (e.g. tags contains post_id column)
     * @param object $object
     * @param string $target
     * @return array
     */
    public function oneToMany($object, $target) {
        $parent = $this->objectInjector(get_class($object));
        $child = strtolower(ReflectionUtils::stripNamespace($target));
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->select($this->setTableToProperties(ReflectionUtils::getProperties($target),$child))
            ->from(array($child))
            ->where($parent.'_id =?');
        $this->query($queryBuilder->getQuery());
        $this->bindValue(1, $object->id);
        return $this->fetchObjectSet($target);
    }

    /**
     * * Create N-1 relationship from two objects. Returns an array of target objects of the relationship.
     * Is the inverse of oneToMany
     * e.g. (Posts() N <=> 1 Tags())
     * The parent table should contain the child id. (e.g. post contains tags_id column)
     * @param $object
     * @param $target
     */

    public function manyToOne($object, $target) {
        try {
            $parent = $this->objectInjector(get_class($object));
            $child = $this->objectInjector($target);
        } catch (Exception $e) {
            return;
        }
        // TODO join instead will be faster
        $query = "SELECT * FROM $child WHERE id = (SELECT ". $child ."_id FROM $parent WHERE id=?)";
        $this->query($query);
        $this->bindValue(1, $object->id);
        return $this->fetchSingleObject($target);

    }

    /**
     * Return a collection of ojects of a N to N relationship. The table must be called $object_$target, the N/N table must contain
     * $object_id reference. The table names uses the convetion of lowercase (@see ObjectInjector).
     * Return an ArrayObject of the $target object class.
     * @param object $object
     * @param string $target
     * @return \ArrayObject
     */
    public function manyToMany($object, $target) {
        $parent = $this->objectInjector(get_class($object));
        $child = strtolower(ReflectionUtils::stripNamespace($target));
        $queryBuilder = new QueryBuilder();
        $queryBuilder
            ->select($this->setTableToProperties(ReflectionUtils::getProperties($target),$child))
            ->from(array($child))
            ->innerJoin($child,'id',$parent.'_'.$child,$child.'_id');
        $this->query($queryBuilder->getQuery());
        return $this->fetchObjectSet($target);
    }

    /**
     * Return a collection of ojects of a N to N relationship. The table must be called $target_$object
     * , the N/N table must contain
     * $object_id reference. The table names uses the convetion of lowercase (@see ObjectInjector).
     * Return an ArrayObject of the $target object class.
     * @param type $object
     * @param type $target
     * @return \ArrayObject
     */
    public function _manyToMany($object, $target) {

        $parent = $this->objectInjector(get_class($object));
        $child = $this->objectInjector($target);
        $query = "SELECT * FROM ".$child ."_". $parent ." WHERE ". $parent ."_id = ?";
//        echo $query;
        $this->query($query);
        $this->bindValue(1, $object->id);
        $relations = $this->fetchObj();


//        print_r($relations);
        $list = new ArrayObject();
        $child_id = $child.'_id';
        foreach ($relations as $key => $value) {

            $query = "SELECT * from $target WHERE id=?";
            $this->query($query);
            $this->bindValue(1, $value->$child_id);
            $obj = $this->fetchSingleObject($this->objectInjector($target));
            $list->append($obj);

        }

        return $list;
    }



    final public function changeDb($dbDriver, $dbHost, $dbName, $dbUser, $dbPassword, $options=null ) {
        $this->pdo = null;
        $this->dbDriver = $dbDriver;
        $this->dbHost = $dbHost;
        $this->dbName = $dbName;
        $this->dbPwd = $dbPassword;
        $this->dbUser = $dbUser;
        $this->options = $options;
        // make a new conn
        $dsn = $this->dbDriver.':host=' .$this->dbHost . ";dbname=".$this->dbName;
        try {
            $this->pdo = new PDO($dsn, $this->dbUser, $this->dbPwd, $this->options);
        } catch (PDOException $ex) {
            $this->ex = $ex->getMessage();
        }

    }

    final public function revertDb() {
        $this->pdo = null;
        $this->dbDriver = DB_DRIVER;
        $this->dbHost = DB_HOST;
        $this->dbName = DB_NAME;
        $this->dbUser = DB_USER;
        $this->dbPwd = DB_PSW;
        $this->options = DB_OPTIONS;
        // make a new conn
        $dsn = $this->dbDriver . ':host=' . $this->dbHost . ";dbname=" . $this->dbName;
        try {
            $this->pdo = new PDO($dsn, $this->dbUser, $this->dbPwd, $this->options);
        } catch (PDOException $ex) {
            $this->ex = $ex->getMessage();
        }
    }

    private function objectInjector($object) {
//        if (!is_object($object)) {
//            throw new Exception ('PlayPHP exception: the argument passed is not an object');
//        } else {
//            return strtolower(get_class($object));
//        }

        return strtolower(ReflectionUtils::stripNamespace($object));
    }
    private function objectRelocator($object) {
        return Application::getNameSpace()['MODEL'].$object;
    }

    private function setTableToProperties(array $properties, string $table) {
        $result = array();
        foreach ($properties as $property) {
            $result[] = $table.'.'.$property;
        }
        return $result;
    }

    /**
     * @internal parse params from a given array and bind them in a prepared statement
     * @param array $params
     */
    private function paramsBinder(array $params) {
        foreach ($params as $key => $value) {
            $key++; // + 1 for bindParams
            $this->bindValue($key, $value);
        }
    }
    /**
     * @param SQL_QUERY $statament SQL query with : placeholders
     * @param SQL_QUERY $statament SQL query with : placeholders
     * @param Array $params names of placeholders
     * @param Array $params placeholders values
     */
    private function query($statement) {
        $stm = $this->pdo->prepare($statement);
        $this->stm = $stm;
    }
    /**
     *
     * @param mixed $param
     * @param mixed $value
     */
    private function bindValue ($param, $value) {
        $this->stm->bindParam($param,$value);
    }

    /**
     * Exclude 'id' or 'ID' index for update operations
     * @param array $properties
     * @return array
     */
    private function excludeId(array $properties):array {
        $result = array();
        foreach ($properties as $key => $property) {
            if ($key ==='id'|| $key==='ID'){
                continue;
            }
            $result[$key] = $property;
        }
        return $result;
    }


    /**
     *
     * @param object $object
     */
    private function execute($object=null) {
        return $this->stm->execute();
    }
    private function rowCount() {
        $this->execute();
        return $this->stm->fetchColumn();
    }
    private function resultList() {
        $this->execute();
        return $this->stm->fetchAll(PDO::FETCH_ASSOC);
    }
    private function singleResult() {
        $this->execute();
        return $this->stm->fetch(PDO::FETCH_ASSOC);
    }
    private function fetchObj() {
        $this->execute();
        return $this->stm->fetchAll(PDO::FETCH_OBJ);
    }
    private function fetchSingleObject($object) {
        $this->stm->setFetchMode(PDO::FETCH_INTO, new $object());
        $this->execute();
        return $this->stm->fetch();
    }
    private function fetchObjectSet($object) {
        $this->execute();
        return $this->stm->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $object);
    }


}


