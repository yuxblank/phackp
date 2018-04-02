<?php
/**
 * Created by IntelliJ IDEA.
 * User: yux
 * Date: 13/03/18
 * Time: 19.06
 */

namespace yuxblank\phackp\core\api;


abstract class AbstractModule implements Module
{
    protected static $root;
    protected $name;
    protected $routes=[];
    protected $entityPaths=[];

    /**
     * AbstractModule constructor.
     * @param $name
     */
    public function __construct(string $root)
    {
        if (!is_dir($root)){
            throw new \RuntimeException('Invalid root dir: ' . $root);
        }
        self::$root = $root;
    }


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

    public function setEntityPaths(array $paths = [])
    {
        $this->entityPaths = $paths;
    }

    public function getEntitiesPaths(): array
    {
        return $this->entityPaths;
    }


}