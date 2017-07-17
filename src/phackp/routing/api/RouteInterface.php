<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 17/07/2017
 * Time: 11:32
 */

namespace yuxblank\phackp\routing\api;


interface RouteInterface
{
    /**
     * @return string
     */
    public function getURI(): string;

    /**
     * @param string $URI
     */
    public function setURI(string $URI);

    /**
     * @return string
     */
    public function getClass(): string;

    /**
     * @param string $class
     */
    public function setClass(string $class);

    /**
     * @return string
     */
    public function getAction(): string;

    /**
     * @param string $method
     */
    public function setAction(string $method);

    /**
     * @return string
     */
    public function getAlias(): string;

    /**
     * @param string $alias
     */
    public function setAlias(string $alias);

    public function isError():bool;
    public function setIsError(bool $isError);


}