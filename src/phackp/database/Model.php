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
abstract class Model
{
    /** @var HackORM  */
    private $ormInstance;

    /**
     * Model constructor.
     * @param $ormInstance
     */
    public function __construct($ormInstance)
    {
        if ($this->ormInstance===null) {
            $this->ormInstance = new HackORM();
        }
    }

    /**
     * @return HackORM
     */
    private function getOrmInstance(): HackORM
    {
        return $this->ormInstance;
    }




    /**
     * @return \PDO
     */
    protected final function getPDO(): \PDO
    {
        return $this->getOrmInstance()->getDB()->getPDO();
    }

    protected function count(string $query = null, ...$params)
    {
        return $this->getOrmInstance()->countObjects($this, $query, $params);
    }

    protected function delete($id = null)
    {
        $id = $id == null ? $this->id : $id;
        return $this->getOrmInstance()->remove($this, $id);
    }


    protected function find(string $query, ...$params)
    {
        return $this->getOrmInstance()->search($this, $query, $params);
    }

    protected function findAll(string $query = null, ...$params)
    {
        return $this->getOrmInstance()->searchAll($this, $query, $params);
    }

    protected function findById($id)
    {
        return $this->getOrmInstance()->searchByKey($this, $id);
    }

    protected function findAsArray(string $query, ...$params)
    {
        return $this->getOrmInstance()->_searchAll($this, $query, $params);
    }

    protected function findAllAsArray(string $query = null, ...$params)
    {
        return $this->getOrmInstance()->_searchAll($this, $query, $params);
    }

    protected function lastInsertId()
    {
        return $this->getOrmInstance()->getDB()->getPDO()->lastInsertId();
    }

    protected function save()
    {
        return $this->getOrmInstance()->persist($this);
    }

    protected function update()
    {
        return $this->getOrmInstance()->merge($this);
    }

    protected function belongsTo(string $target)
    {
        return $this->getOrmInstance()->oneToOne($this, $target);
    }

    protected function hasMany(string $target)
    {
        return $this->getOrmInstance()->oneToMany($this, $target);
    }

    protected function HasOne(string $target)
    {
        return $this->getOrmInstance()->manyToOne($this, $target);
    }

    protected function hasManyThrough(string $target)
    {
        return $this->getOrmInstance()->manyToMany($this, $target);
    }

    protected function _hasManyThrough(string $target)
    {
        return $this->getOrmInstance()->_manyToMany($this,  $target);
    }
}