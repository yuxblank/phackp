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

/**
 * This class is a API based on top of PDO. The class allow query building, Object relationship mapping and db access.
 * The class can be used as an object instance but is rather better to extends your persistent objects with Model superclass that provide better API to this class
 * @author yuri.blanc
 * @version 0.3
 */

class Database{
    /**
     * @var array Database configuration
     */
    private $conf;
    /**
     * @var \PDO
     */
    private $pdo;
    /**
     * @var \PDOStatement
     */
    private $stm;


    /**
     * Database constructor.
     */
    public function __construct() {
        $this->conf = Application::getDatabase();
        $this->connect();

    }

    /**
     * Save Database instance state for serialization
     * @return array
     */
    public function __sleep()
    {
        return array('conf');

    }

    /**
     * Deserialize Database class and reconnect to datasource.
     */
    public function __wakeup()
    {
        $this->connect();
    }


    /**
     * Connect to database and create pdo instance
     */
    protected function connect(){

        $dsn = $this->conf['DRIVER'] . ':host=' . $this->conf['HOST'] . ";dbname=" . $this->conf['NAME'];
        try {
            $this->pdo = new PDO($dsn, $this->conf['USER'], $this->conf['PSW'], $this->conf['OPTIONS']);
        } catch (\PDOException $ex) {
            $ex->getMessage();

        }

    }

    /**
     * This method will return the current instance of the PDO object, already connected.
     * If you need to directly access pdo, you can do it.
     * @return PDO
     */

    public function getPDO() {
        return $this->pdo;
    }

    public function setTableToProperties(array $properties, string $table) {
        $result = array();
        foreach ($properties as $property) {
            $result[] = $table.'.'.$property;
        }
        return $result;
    }

    /**
     * @param array $params
     * @return \PDOStatement
     */
    public function paramsBinder(array $params) {
        foreach ($params as $key => $value) {
            $key++; // + 1 for bindParams
            $this->bindValue($key, $value);
        }
        return $this->stm;
    }

    /**
     * @param $statement
     * @return \PDOStatement
     */
    public function query($statement) {
        $this->stm = $this->pdo->prepare($statement);
        return $this->stm;
    }
    /**
     * bind a a value
     * @param mixed $param
     * @param mixed $value
     * @return \PDOStatement
     */
    public function bindValue ($param, $value) {
        $this->stm->bindParam($param,$value);
        return $this->stm;
    }

    /**
     * Exclude 'id' or 'ID' index for update operations
     * @param array $properties
     * @return array
     */
    public function excludeId(array $properties):array {
        $result = array();
        foreach ($properties as $key => $property) {
            if ($key ==='id'|| $key==='ID'){
                continue;
            }
            $result[$key] = $property;
        }
        return $result;
    }



    public function execute() {
        return $this->stm->execute();
    }
    public function rowCount() {
        $this->execute();
        return $this->stm->fetchColumn();
    }
    public function resultList() {
        $this->execute();
        return $this->stm->fetchAll(PDO::FETCH_ASSOC);
    }
    public function singleResult() {
        $this->execute();
        return $this->stm->fetch(PDO::FETCH_ASSOC);
    }
    public function fetchObj() {
        $this->execute();
        return $this->stm->fetch(PDO::FETCH_OBJ);
    }
    public function fetchObjectSet() {
        $this->execute();
        return $this->stm->fetchAll(PDO::FETCH_OBJ);
    }
    public function fetchSingleClass($object) {
        $this->stm->setFetchMode(PDO::FETCH_INTO, new $object());
        $this->execute();
        return $this->stm->fetch();
    }
    public function fetchClassSet($object) {
        $this->execute();
        return $this->stm->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $object);
    }


}


