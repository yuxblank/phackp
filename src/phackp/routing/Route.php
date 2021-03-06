<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 17/07/2017
 * Time: 11:27
 */

namespace yuxblank\phackp\routing;



use yuxblank\phackp\routing\api\RouteInterface;

class Route implements RouteInterface
{
    /** @var  string */
    private $URI;
    /** @var  string */
    private $class;
    /** @var  string */
    private $action;
    /** @var  string */
    private $alias;
    /** @var  bool */
    private $isError;
    /** @var  array */
    private $params;

    /**
     * Route constructor.
     * @param string $URI
     * @param string $class
     * @param string $method
     * @param string|null $alias
     * @param bool $isError
     * @param array $params
     */
    public function __construct(string $URI, string $class, string $method, array $params=[], string $alias=null, bool $isError=false)
    {
        $this->URI = $URI;
        $this->class = $class;
        $this->action = $method;
        $this->params = $params;
        $this->alias = $alias;
        $this->isError = $isError;
    }

    /**
     * @return string
     */
    public function getURI(): string
    {
        return $this->URI;
    }

    /**
     * @param string $URI
     */
    public function setURI(string $URI)
    {
        $this->URI = $URI;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass(string $class)
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $method
     */
    public function setAction(string $method)
    {
        $this->action = $method;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     */
    public function setAlias(string $alias)
    {
        $this->alias = $alias;
    }

    public function setIsError(bool $isError)
    {
        $this->isError = $isError;
    }

    public function isError():bool
    {
        return $this->isError;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params)
    {
        $this->params = $params;
    }

    public function hasParams(): bool
    {
        return count($this->params) > 0;
    }


}