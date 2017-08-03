<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 28/06/2017
 * Time: 11:58
 */

namespace yuxblank\phackp\routing\api;


use yuxblank\phackp\routing\Route;

interface Router
{
    /**
     * Return a link URL.
     * @param string $link
     * @param array|null $params
     * @return mixed
     */
    public function link(string $link, array $params = null);

    /**
     * Return the link URL by a given alias
     * @param string $alias
     * @param String|null $method
     * @param array|null $params
     * @return mixed
     */
    public function alias(string $alias, String $method = null, array $params = null);

    /**
     * Redirect to an internal or external url
     * @param string $url
     * @param bool|null $external
     * @return mixed
     */
    public function redirect(string $url, bool $external=null);

    /**
     * Find the action for the give ServerRequest. The method it's invoked by pHackp runtime in order to detect
     * the current route.
     * Must return the actual route.
     * @throws \RouterException
     * @return RouteInterface
     */
    public function findAction():RouteInterface;

    /**
     * Get an Error route by error code.
     * @param int $code
     * @throws \RouterException
     * @return RouteInterface
     */
    public function getErrorRoute(int $code):RouteInterface;
}