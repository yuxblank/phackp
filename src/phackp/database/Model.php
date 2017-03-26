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
abstract class Model extends HackORM
{

    /**
     * @return \PDO
     */
    public final function getPDO(): \PDO
    {
        return $this->getDB()->getPDO();
    }

    public function count(string $query = null, ...$params)
    {
        return $this->countObjects($this, $query, $params);
    }

    public function delete($id = null)
    {
        $id = $id == null ? $this->id : $id;
        return $this->remove($this, $id);
    }


    public function find(string $query, ...$params)
    {
        return $this->search($this, $query, $params);
    }

    public function findAll(string $query = null, ...$params)
    {
        return $this->searchAll($this, $query, $params);
    }

    public function findById($id)
    {
        return $this->searchByKey($this, $id);
    }

    public function findAsArray(string $query, ...$params)
    {
        return $this->_searchAll($this, $query, $params);
    }

    public function findAllAsArray(string $query = null, ...$params)
    {
        return $this->_searchAll($this, $query, $params);
    }

    public function lastInsertId()
    {
        return $this->getDB()->getPDO()->lastInsertId();
    }

    public function save()
    {
        return $this->save($this);
    }

    public function update()
    {
        return $this->update($this);
    }

    public function belongsTo(string $target)
    {
        return $this->oneToOne($this, $target);
    }

    public function hasMany(string $target)
    {
        return $this->oneToMany($this, $target);
    }

    public function HasOne(string $target)
    {
        return $this->manyToOne($this, $target);
    }

    public function hasManyThrough(string $target)
    {
        return $this->manyToMany($this, $target);
    }

    public function _hasManyThrough(string $target)
    {
        return $this->_manyToMany($this,  $target);
    }
}