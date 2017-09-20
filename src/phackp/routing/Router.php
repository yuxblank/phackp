<?php

namespace yuxblank\phackp\routing;

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
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use yuxblank\phackp\core\api\ApplicationController;
use yuxblank\phackp\exceptions\InvocationException;
use yuxblank\phackp\routing\api\RouteInterface;
use yuxblank\phackp\routing\exception\RouterException;
use yuxblank\phackp\utils\ReflectionUtils;
use Zend\Diactoros\Uri;

/**
 * This class provides routing methods for index.php. Some methods can be used also externally for inverse routing and url
 * retrive echoing the output.
 * @author yuri.blanc
 * @copyright (c) 2015, Yuri Blanc
 * @since 0.1
 */
class Router implements api\Router
{

    private $routes;
    private $appGlobals;
    private $serverRequest;
    const WILDCARD_REGEXP = '({[aA-zZ0-9]+})';


    /**
     * Router constructor.
     * @param $routes
     * @param $appGlobals
     * @param ServerRequestInterface $serverRequest
     */
    public function __construct($routes, $appGlobals, ServerRequestInterface $serverRequest)
    {
        $this->routes = $routes;
        $this->appGlobals = $appGlobals;
        $this->serverRequest = $serverRequest;
    }


    /**
     * Get a link url without checking if the route is really defined.
     * To set params, specify the ordinal position in the link as {param}, the array must preserve params ordinal position.
     * This is the faster way to get a real link.
     * @param string $link
     * @param array $params
     * @return string
     */

    public function link(string $link, array $params = null): string
    {
        if ($params !== null) {
            $url = $this->fastParamBind($link, $params);
            return $this->appGlobals['APP_URL'] . '/' . implode('/', $url);
        }
        return $link !== '/' ? $this->appGlobals['APP_URL'] . '/' . $link : $this->appGlobals['APP_URL'] . $link;
    }

    /**
     * @deprecated
     * fixme not working anymore with new routing vars class and method
     * Get the link by a given action. This way to get links is slower but allow the developer to change urls without changing code,
     * referencing to urls with the action instead of a link.
     * Passing the HTTP method will make it faster.
     * For dynamic urls, just pass the array of parameters in ordinal position.
     * If not found (or not defined in routes) return 404 page url.
     * @param string $action
     * @param String|null $method
     * @param array|null $params
     * @return string
     * @throws \yuxblank\phackp\routing\exception\RouterException
     */

    public function action(string $action, String $method = null, array $params = null)
    {
        $link = $this->searchThroughRoutes($action, 'action', $method);
        if ($link === null) {
            $link = $this->getErrorRoute(404)->getURI();
        }

        if ($params !== null) {
            $url = $this->fastParamBind($link, $params);
            return $this->appGlobals['APP_URL'] . implode('/', $url);
        }
        return $link !== '/' ? $this->appGlobals['APP_URL'] . '/'. $link : $this->appGlobals['APP_URL'] . $link;
    }

    /**
     * Get the link by a given alias. This way to get links is slower but allow the developer to change urls without changing code,
     * referencing to urls with an alias instead of a link.
     * Passing the HTTP method will make it faster.
     * For dynamic urls, just pass the array of parameters in ordinal position.
     * If not found (or not defined in routes) return 404 page url.
     * @param string $alias
     * @param String|null $method
     * @param array|null $params
     * @return string
     * @throws \yuxblank\phackp\routing\exception\RouterException
     */

    public function alias(string $alias, String $method = null, array $params = null):string
    {

        $link = $this->searchThroughRoutes($alias, 'alias', $method);
        if ($link === null) {
            $link = $this->getErrorRoute(404)->getURI();
        }

        if ($params !== null) {
            $url = $url = $this->fastParamBind($link, $params);
            return $this->appGlobals['APP_URL']  . implode('/', $url);
        }
        return $link !== '/' ? $this->appGlobals['APP_URL'] . '/' : $this->appGlobals['APP_URL'] . $link;
    }

    /**
     * Redirect (302) to another action from an action.
     * @param string $uri
     * @param array $params
     * @return mixed|void
     * @internal param RouteInterface $route
     */

    public function switchAction(string $uri, array $params = null)
    {
        $r = $this->link($uri);
        header("location:$r", true, 302);
    }

    public function _switchAction(string $alias, array $params=null)
    {
        $r = $this->alias($alias,null,$params);
        header("location:$r", true, 302);
    }


    /**
     * External url redirect
     * @param UriInterface $uri
     * @return mixed|void
     */

    public function redirect(UriInterface $uri)
    {
        header("location:".$uri, true, 302);
    }

    /**
     * Find the action from httpKernel and set routed parameters if any.
     * if the route has been found return the route.
     * @return RouteInterface
     * @throws RouterException
     */

    public function findAction():RouteInterface
    {

        foreach ($this->routes[$this->serverRequest->getMethod()] as $key => $route) {
            // case without params

            // if the url is the same static route, just return!
            if ($route['url'] === $this->serverRequest->getUri()->getPath()) {
                return $this->createRouteFromArray($route);
            }
            // find wildcard
            if (preg_match(self::WILDCARD_REGEXP, $route['url'])) {
                $routeArray = preg_split('@/@', $route['url'], NULL, PREG_SPLIT_NO_EMPTY);
                $queryArray = preg_split('@/@', $this->serverRequest->getUri()->getPath(), NULL, PREG_SPLIT_NO_EMPTY);
                $url = $this->compareRoutes($routeArray, $queryArray);
                // if compare routes matched and the url has been recreated, return this route
                if ($url !== null) {
                    $route['params'] = $this->getWildCardParams($routeArray, $queryArray);
                    return $this->createRouteFromArray($route);
                }
            }

        }
        throw new RouterException("Route not found", RouterException::NOT_FOUND);
    }

    /**
     * @param int $code
     * @return RouteInterface
     * @throws RouterException
     */
    public function getErrorRoute(int $code):RouteInterface{
        if (isset($this->routes['ERROR'][$code])){
            return $this->createRouteFromArray($this->routes['ERROR'][$code]);
        }
        throw new RouterException('Error route with code: ' .$code. ' not defined', RouterException::ROUTE_NOT_DEFINED);
    }


    /*
     * PRIVATE
     */


    /**
     * Search the url of a given alias. When passing the method it's faster.
     * @param string $value
     * @param String $type
     * @param string|null $method
     * @return mixed
     */

    private function searchThroughRoutes(string $value, String $type, string $method = null)
    {

        if ($method !== null) {
            foreach ($this->routes[$method] as $key => $route) {
                if (array_key_exists($type, $route) && $route[$type] === $value) {
                    return $route['url'];
                }
            }
        } else {
            foreach ($this->routes as $key => $rest) {
                foreach ($rest as $innerKey => $innerRoute) {
                    if (array_key_exists($type, $innerRoute) && $innerRoute[$type] === $value) {
                        return $innerRoute['url'];
                    }
                }
            }
        }
    }

    /**
     * Process the route URI and return the url with given parameters.
     * Use positions of Wildcards and the ordinal replace them with params
     * @param $routeUrl
     * @param $params
     * @return array
     */

    private function fastParamBind($routeUrl, $params)
    {
        $url = explode('/', $routeUrl);
        $wildcards = preg_grep(self::WILDCARD_REGEXP, $url);
        $i = 0;
        foreach ($wildcards as $key => $wildcard) {
            $url[$key] = $params[$i];
            $i++;
        }
        return $url;
    }


    /**
     * Read the url and the route and watch if it matches, Replacing the wildcards {val} until the url match then return the url
     * Return null if the given url does not match the route.
     * @param $routeParams
     * @param $realParams
     * @return null|string
     */

    private function compareRoutes(array $routeParams, array $realParams)
    {

        // try checking if wildcards static params are less than the difference with real
        $staticParams = preg_grep(self::WILDCARD_REGEXP, $routeParams, PREG_GREP_INVERT);
        if (count(array_diff($staticParams, $realParams)) > 0) {
            return null;
        }
        // if the count of real and the count of route does not match, the route does not match
        $count = count($realParams);
        if ($count !== count($routeParams)) {
            return null;
        }

        // now loops and replace wicards will params, check, rerun until a difference has been spot
        for ($i = 0; $i < $count; $i++) {
            // faster, if a static param match continue!
            if ($realParams[$i] === $routeParams[$i]) {
                continue;
            } else {
                if (preg_match(self::WILDCARD_REGEXP, $routeParams[$i])) {
                    // replace {value} wildcard with the same url parameter e.g {value} -> value
                    $replaceParam = preg_replace(self::WILDCARD_REGEXP, $realParams[$i], $routeParams[$i]);
                    $routeParams[$i] = $replaceParam;
                    // if match, continue
                    if ($realParams[$i] === $routeParams[$i]) {
                        continue;
                        // not the same route
                    } else {
                        return null;
                    }
                    // the route is not the same or doesn't have the wildcard {}
                } else {
                    return null;
                }
            }

        }
        // if loop has ended, all params matched. return the url string
        return implode('/', $routeParams);
    }

    /**
     * Read the route wildCards {name} and return an associative array paired on {name} => value.
     * The value is taken from the current request parameter.
     * @param $routeParams
     * @param $queryArray
     * @return array
     */

    private function getWildCardParams(array $routeParams, array $queryArray): array
    {
        $params = preg_grep(self::WILDCARD_REGEXP, $routeParams);
        $getParams = array();
        foreach ($params as $key => $param) {
            $index = str_replace(array('{', '}'), '', $routeParams[$key]);
            $getParams[$index] = $queryArray[$key];
        }
        return $getParams;
    }

    private function createRouteFromArray(array $routeArray){
        return new Route(
            $routeArray['url'],
            $routeArray['class'],
            $routeArray['method'],
            $routeArray['params'] ?? [], $routeArray['alias'] ?? null);
    }

    public function match(\yuxblank\phackp\http\api\ServerRequestInterface $request): bool
    {
        return $this->findAction()->getURI() === $request->getUri();
    }

    public function generateUri(RouteInterface $route): UriInterface
    {
        return new Uri($route->getURI());
    }


}
