<?php
namespace yuxblank\phackp\database;
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
 *
 * @author yuri.blanc
 */
abstract class Model  {
    private  $ormInstance;

    private function getORMInstance(){
        if ($this->ormInstance === null) {
            $this->ormInstance = new HackORM();
        }
        return $this->ormInstance;
    }

    /**
     * @return \PDO
     */
    public final function getPDO() {
        return $this->getORMInstance()->getDB()->getPDO();
    }

    public function countObjects($query=null, $params=null) {
        return $this->getORMInstance()->countObjects(get_called_class(),$query,$params);
    }

    public function delete($id=null) {
        return $this->getORMInstance()->delete(get_called_class(), $id);
    }


    public function find($query, $params) {
        return $this->getORMInstance()->find(get_called_class(), $query, $params);
    }

    public function findAll($query = null, $params = null) {
        return $this->getORMInstance()->findAll(get_called_class(), $query, $params);
    }

    public function findById($id) {
        return $this->getORMInstance()->findById(get_called_class(), $id);
    }

    public function findAs($query, $params=null) {
        return $this->getORMInstance()->findAs(get_called_class(),$query, $params);
    }
    public function findAsArray($query,$params=null){
        return $this->getORMInstance()->findAsArray(get_called_class(),$query, $params);
    }
    public function findAllAsArray($query,$params){
        return $this->getORMInstance()->findAllAsArray(get_called_class(),$query, $params);
    }

    public function findMagicSet($query, $params) {
        return $this->getORMInstance()->findAsAll(get_called_class(),$query, $params);
    }

    public function lastInsertId() {
        return $this->getORMInstance()->getDB()->getPDO()->lastInsertId();
    }

    public function nativeQuery($query, $params) {
        return $this->getORMInstance()->findAsArray(get_called_class(),$query, $params);
    }

    public function save() {
        return $this->getORMInstance()->save($this);
    }

    public function update() {
        return $this->getORMInstance()->update($this);
    }

    public function oneToOne($object, $target) {
        return $this->getORMInstance()->oneToOne($object, $target);
    }

    public function oneToMany($object, $target) {
        return $this->getORMInstance()->oneToMany($object, $target);
    }
    public function manyToOne($object, $target) {
        return $this->getORMInstance()->manyToOne($object, $target);
    }

    public function manyToMany($object, $target) {
        return $this->getORMInstance()->manyToMany($object, $target);
    }
}
