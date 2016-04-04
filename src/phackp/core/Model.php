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
    private static $db;
    public function __construct() {
        if (self::$db == null) {
            self::$db = new Database();
        }
    }

    public final function countObjects() {
        return self::$db->countObjects(get_called_class());
    }
    public final function _countObjects($query, $params) {
        return self::$db->_countObjects(get_called_class(),$query,$params);
    }

    public final  function delete($id) {
        return self::$db->delete(get_called_class(), $id);
    }


    public final function find($query, $params) {
        return self::$db->find(get_called_class(), $query, $params);
    }

    public final function findAll($query = null, $values = null, $current = null, $max = null, $order = null) {
        return self::$db->findAll(get_called_class(), $query, $values, $current, $max, $order);
    }

    public final function findById($id) {
        return self::$db->findById(get_called_class(), $id);
    }

    public final function findMagic($query, $params=null) {
        return self::$db->findMagic($query, $params);
    }

    public final function findMagicSet($query, $params) {
        return self::$db->findMagicSet($query, $params);
    }

    public final function lastInsertId() {
        return self::$db->lastInsertId();
    }

    public final function nativeQuery($query, $params) {
        return self::$db->nativeQuery($query, $params);
    }

    public function save() {
        return self::$db->save($this);
    }

    public function update() {
        return self::$db->update($this);
    }

    public function oneToOne($object, $target) {
        return self::$db->oneToOne($object, $target);
    }

    public function oneToMany($object, $target) {
        return self::$db->oneToMany($object, $target);
    }
    public function manyToOne($object, $target) {
        return self::$db->manyToOne($object, $target);
    }

    public function manyToMany($object, $target) {
        return self::$db->manyToMany($object, $target);
    }
    public function _manyToMany($object, $target) {
        return self::$db->_manyToMany($object, $target);
    }
}
