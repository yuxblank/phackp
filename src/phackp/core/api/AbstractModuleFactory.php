<?php
/**
 * Created by IntelliJ IDEA.
 * User: yux
 * Date: 13/03/18
 * Time: 19.06
 */

namespace yuxblank\phackp\core\api;


abstract class AbstractModuleFactory implements Module
{
    protected $name;
    protected $routes=[];
    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function setRoutes(array $routes = [])
    {
        $this->routes = $routes;
    }


}