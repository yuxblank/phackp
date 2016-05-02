<?php
namespace yuxblank\phackp\core;
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
    private  $hackORM;

    public function __construct() {
        if ($this->hackORM === null) {
            $this->hackORM = new HackORM();
        }
    }

    /**
     * @return \PDO
     */
    public function getPDO() {
        return $this->hackORM->getDB()->getPDO();
    }

    public final function countObjects($query=null, $params=null) {
        return $this->hackORM->countObjects(get_called_class(),$query,$params);
    }

    public final function delete($id) {
        return $this->hackORM->delete(get_called_class(), $id);
    }


    public final function find($query, $params) {
        return $this->hackORM->find(get_called_class(), $query, $params);
    }

    public final function findAll($query = null, $params = null) {
        return $this->hackORM->findAll(get_called_class(), $query, $params);
    }

    public final function findById($id) {
        return $this->hackORM->findById(get_called_class(), $id);
    }

    public final function findAs($query, $params=null) {
        return $this->hackORM->findAs(get_called_class(),$query, $params);
    }
    public final function findAsArray($query,$params=null){
        return $this->hackORM->findAsArray(get_called_class(),$query, $params);
    }
    public final function findAllAsArray($query,$params){
        return $this->hackORM->findAllAsArray(get_called_class(),$query, $params);
    }

    public final function findMagicSet($query, $params) {
        return $this->hackORM->findAsAll(get_called_class(),$query, $params);
    }

    public final function lastInsertId() {
        return $this->hackORM->getDB()->lastInsertId();
    }

    public final function nativeQuery($query, $params) {
        return $this->hackORM->findAsArray(get_called_class(),$query, $params);
    }

    public function save() {
        return $this->hackORM->save($this);
    }

    public function update() {
        return $this->hackORM->update($this);
    }

    public function oneToOne($object, $target) {
        return $this->hackORM->oneToOne($object, $target);
    }

    public function oneToMany($object, $target) {
        return $this->hackORM->oneToMany($object, $target);
    }
    public function manyToOne($object, $target) {
        return $this->hackORM->manyToOne($object, $target);
    }

    public function manyToMany($object, $target) {
        return $this->hackORM->manyToMany($object, $target);
    }
}
