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
use yuxblank\phackp\core\Application;

/**
     *
     * @author yuri.blanc
     */
abstract class Model
{
    /**
     * @Inject
     * @var HackORM  */
    private $ormInstance;


    public function __construct()
    {
        // create ORM instance when used outside DI container or when de-serializing.
        if ($this->ormInstance=== null) {
            $this->ormInstance =  Application::getInstance()->container()->get(HackORM::class);
        }
    }

    /**
     * Facade for making an instance of the model
     * @return Model
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public static function make():Model{
        return Application::getInstance()->container()->get(get_called_class());
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
    public final function getPDO(): \PDO
    {
        return $this->getOrmInstance()->getDB()->getPDO();
    }

    public function count(string $query = null, ...$params)
    {
        return $this->getOrmInstance()->countObjects($this, $query, $params);
    }

    public function delete($id = null)
    {
        $id = $id == null ? $this->id : $id;
        return $this->getOrmInstance()->remove($this, $id);
    }


    public function find(string $query, ...$params)
    {
        return $this->getOrmInstance()->search($this, $query, $params);
    }

    public function findAll(string $query = null, ...$params)
    {
        return $this->getOrmInstance()->searchAll($this, $query, $params);
    }

    public function findById($id)
    {
        return $this->getOrmInstance()->searchByKey($this, $id);
    }

    public function findAsArray(string $query, ...$params)
    {
        return $this->getOrmInstance()->_searchAll($this, $query, $params);
    }

    public function findAllAsArray(string $query = null, ...$params)
    {
        return $this->getOrmInstance()->_searchAll($this, $query, $params);
    }

    public function lastInsertId()
    {
        return $this->getOrmInstance()->getDB()->getPDO()->lastInsertId();
    }

    public function save()
    {
        return $this->getOrmInstance()->persist($this);
    }

    public function update()
    {
        return $this->getOrmInstance()->merge($this);
    }

    public function belongsTo(string $target)
    {
        return $this->getOrmInstance()->oneToOne($this, $target);
    }

    public function hasMany(string $target)
    {
        return $this->getOrmInstance()->oneToMany($this, $target);
    }

    public function HasOne(string $target)
    {
        return $this->getOrmInstance()->manyToOne($this, $target);
    }

    public function hasManyThrough(string $target)
    {
        return $this->getOrmInstance()->manyToMany($this, $target);
    }

    public function _hasManyThrough(string $target)
    {
        return $this->getOrmInstance()->_manyToMany($this,  $target);
    }
}