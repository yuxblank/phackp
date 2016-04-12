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
 * This class provides routing methods for index.php. Some methods can be used also externally for inverse routing and url
 * retrive echoing the output.
 * @author yuri.blanc
 * @copyright (c) 2015, Yuri Blanc
 * @since 0.1
 */

class Router
{
    protected static $routes;
    const WILDCARD_REGEXP = '({[aA-zZ0-9]+})';
    /**
     * Costructor reads the routes.json file as a stdClass();
     */
    private function __construct()
    {
        Router::$routes = Application::getInstance()->getConfig()['ROUTES'];
        /*Router::$routes = json_decode(file_get_contents(APP_ROOT . 'config/routes.json'));*/
    }

    /**
     * Return stdClass respresentation of routes.json
     * @return Router
     */
    public static function getInstance()
    {
        if (Router::$routes == null) {
            new Router();
        }
        return Router::$routes;
    }

    /**
     * The method returns the route URL from a action string params pointing to the controller and action.
     * The $action must be present in routes or it returns 404 (not found).
     * $params if set, must contain an associative array of GET query string. (e.x. ['id' => 'number']).
     * @static
     * @param string $action
     * @param mixed[] $params
     * @return string
     */
    public static function go($action, $params = null, $method = null)
    {

        $route = Router::findUrl($action, $method);
        if ($route) {
            // case with N params
            if (isset($params)) {
                if (strpos($route->url, "{") || strpos($route->url, "}")) {
                    $queryString = "";
                    foreach ($params as $key => $value) {
                        $queryString = $route->url;
                        // find position of key
                        $currentParam = "{" . $key . "}";
                        if (strpos($queryString, $currentParam)) {
                            $queryString = str_replace($currentParam, $value, $queryString);
                        }
                    }
                    // return queryString url

                    return APP_URL . $queryString;
                } else {
                    return APP_URL . $route->url . "?" . http_build_query($params);
                }
            }
            // return url from json
            return APP_URL . "$route->url";
        } else {
            // not found, return /404
            //return APP_URL . "404";
            http_response_code(404);
            return APP_URL . "404";
        }
    }


/*    // TODO new routing faster link
    public function link($action, $method = null)
    {
        $varArgs = func_get_arg(2);
        $route = $this->_findUrl($action, $method);


        //TODO define a valid regexp
        if ($varArgs && count(preg_grep('({[aA-zZ 0-9]+})',$route))>0) {

            $queryString = "";
            foreach ($varArgs as $key => $value) {
                $queryString = $route->url;
                // find position of key
                $currentParam = "{" . $key . "}";
                if (strpos($queryString, $currentParam)) {
                    $queryString = str_replace($currentParam, $value, $queryString);
                }
            }


        }


    }*/

    /**
     * Get a link url without checking if the route is really defined.
     * To set params, specify the ordinal position in the link as {param}, the array must preserve params ordinal position.
     * This is the faster way to get a real link, but will return 404 not found when the route does not exist.
     * @param string $link
     * @param array $params
     * @return string
     */
    public static function link(string $link, array $params =null):string {
        if ($params!==null){
            $url = explode('/', $link);
            $wildcards = preg_grep(self::WILDCARD_REGEXP,$url);
            $i=0;
            foreach ($wildcards as $key => $wildcard) {
                $url[$key] = $params[$i];
                $i++;
            }
            return Application::getAppUrl().'/'.implode('/', $url);
        } else {
            return Application::getAppUrl().'/'.$link;
        }
    }

    /**
     * Redirect (302) to another action from an action.
     * @static
     * @param string $action
     * @param mixed[] $params
     */
    public static function switchAction($action, $params = null)
    {
        $r = Router::go($action, $params);
        header("location:$r", true, 302);
    }

    /**
     * Redirect (302) to another action from an url.
     * @static
     * @param string $action
     * @param mixed[] $params
     */
    public static function redirect($url)
    {
        $action = Router::findAction($url);
        self::switchAction($action);
    }


    public static function _redirect($url)
    {
        header("location:$url", true, 302);
    }


    /**
     * Find the url in routes from an action. The url returned is the first of the routes list.
     * @static
     * @param string $action
     * @return stdClass
     */
    public static function findUrl($action, $method = null)
    {
        foreach (Router::getInstance()->routes as $route) {
            if (!isset($method)) {
                if ($route->action == $action) {
                    return $route;
                }
            } else {
                if ($route->action == $action && $route->method == $method) {
                    return $route;
                }
            }
        }
        // not found
        http_response_code(404);
        return null;
    }

    // TODO new routing finder
    public function _findUrl($action, $method = null)
    {
        $routes = array(); // test

        foreach ($routes as $uri => $route) {
            if (null === $method) {
                if ($route->action === $action) {
                    return $route;
                }
            } else {
                if ($route->action === $action && $route->method === $method) {
                    return $route;
                }
            }

        }
        // not found
        http_response_code(404);
        return null;
    }

    /**
     * Read the real URL and check if exist in routes. If the route contains ? wildcards, try to replace them with current values and check for match.
     * if no indentical urls are found, returns 404.
     * @static
     * @param string $query
     * @return stdClass
     */
    public static function findAction($query)
    {
        $queryArray = explode("/", $query);
        //print_r($queryArray);
        // check not parametered routes
        foreach (Router::getInstance()->routes as $route) {
            //echo "for 1";
            if ($route->url === $query) {
                //echo "1 is equal";
                // replace current routes url with incoming url
                $route->url = $query;
                return $route;
            } else if (preg_match(self::WILDCARD_REGEXP, $route->url)) {
                // check parametered routes  
                $queryReplace;
                //echo "for 2";
                $routeArray = explode("/", $route->url);
                //print_r($routeArray);
                $replaceArray = array();
                // check about size
                if (count($queryArray) === count($routeArray)) {
                    //create params array
                    $paramsArray = array();
                    for ($i = 0; $i < count($queryArray); $i++) {
                        if ($queryArray[$i] === $routeArray[$i]) {
                            $replaceArray[$i] = $queryArray[$i];
                        } else if (preg_match(self::WILDCARD_REGEXP, $routeArray[$i])) {
                            $replaceArray[$i] = $queryArray[$i];
                            $paramsArray[str_replace(array("{", "}"), "", $routeArray[$i])] = $queryArray[$i];
                        }
                    }
                    $newUrl = implode("/", $replaceArray);
                    //echo "<br>".$newUrl;
                    if ($newUrl === $query) {
                        $route->url = $query;
                        // set params array for {values} substitution
                        $route->getParams = $paramsArray;
                        return $route;
                    }
                }
            }
        }
        // return 404
        //return $route;
        http_response_code(404);
        return $route;
    }

    /**
     * Read the url and return route if found in routes file.
     * If route has not been found, retrun null
     * @param $query (relative url)
     * @return array|null
     */
    public function _findAction($query)
    {
        //test
        $routes = array(); // routes loaded
        foreach (Application::getInstance()->getConfig()['ROUTES'] as $uri => $route) {
            // case without params
            if ($uri === $query) {
                return $route;
                // case with {} params
            } else {
                if (preg_match(self::WILDCARD_REGEXP, $uri)) {
                    $routeArray = explode('/', $uri);
                    $queryArray = explode('/', $query);
                    $url = self::compareRoutes($routeArray, $queryArray);
                    if ($url !== null) {
                        $route['params'] = array();
                        $route['params'] = self::getWildCardParams($routeArray, $queryArray);
                        return $route;
                    } else {
                        continue;
                    }
                }
            }
        }
        return null;
    }

    /**
     * Read the url and the route and watch if it matches, Replacing the wildcards {val} until the url match then return the url
     * Return null if the given url does not match the route.
     * @param $routeParams
     * @param $realParams
     * @return null|string
     */
    private static function compareRoutes($routeParams, $realParams)
    {
        $count = count($realParams);
        if ($count < count($routeParams)) {
            return null;
        }

        for ($i = 0; $i < $count; $i++) {
            if ($realParams[$i] === $routeParams[$i]) {
                continue;
            } else {
                if (preg_match(self::WILDCARD_REGEXP, $routeParams[$i])) {
                    $replaceParam = preg_replace(self::WILDCARD_REGEXP, $realParams[$i], $routeParams[$i]);

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
        return implode('/', $routeParams);
    }

    /**
     * Read the route wildCards {name} and return an associative array paired on {name} => value.
     * The value is taken from the current request parameter.
     * @param $routeParams
     * @param $queryArray
     * @return array
     */
    private static function getWildCardParams($routeParams, $queryArray)
    {
        $params = preg_grep(self::WILDCARD_REGEXP, $routeParams);
        $getParams = array();
        foreach ($params as $key => $param) {
            $index = str_replace(array('{', '}'), '', $routeParams[$key]);
            $getParams[$index] = $queryArray[$key];
        }
        return $getParams;
    }


    /**
     * Performs a check for a valid action and method in routes. if action and method belongs to a route returns the route.
     * @static
     * @param string $action
     * @param string $method
     * @return stdClass
     */
    public static function checkRoutes($action, $method)
    {
        foreach (Router::getInstance()->routes as $valid) {
            /*   echo $valid->action . ' == ' . $action . '|||';
               echo $valid->method . ' == ' . $method . '|||';*/
            if ($valid->method == $method && $valid->action == $action) {
                return $valid;
            }
        }
    }

/*    public static function getController($action)
    {
        $array = explode('@', $action);
        $array[0] = 'controller\\'.$array[0];

        return $array;
    }*/

    /**
     * Performs a inverse route returning returning an array with [0 => 'Controller', 1 => 'action']
     * Recreate the current application CONTROLLER namespace using the application configuration.
     * @param string $action
     * @return mixed[]
     */
    public static function getController($action)
    {
        $namespace = Application::getNameSpace()['CONTROLLER'];
        $array = explode('@', $action);
        $array[0] = $namespace.$array[0];
        return $array;
    }


    /**
     * Performs a 404 not found
     * @static
     * @param string $action
     * @param string $method
     */

    public static function notFound()
    {
        // http_response_code(404);
        if (APP_DEBUG) {
            die("Route not found:: with method <br>");
        }
        $r = Router::go("Errors@404");
        http_response_code(404);
        header("location:$r", true);
    }


    public static function methodNotAllowed()
    {

    }


    /**
     * Dispatch HTTP request and parameters to the current HttpKernel instance.
     * @param $route
     * @param HttpKernel $httpKernel
     */
    public static function dispatch($route, HttpKernel $httpKernel)
    {

        switch ($httpKernel->getMethod()) {
            case 'GET':
                // get paramets ?name=value
                if (!empty($_GET)) {
                    $httpKernel->setParams($_GET);
                }
                // pHackp parameters {name} in URI
                // TODO make $route->hasParams() so $route->getRoutedParams()
                if (isset($route->getParams)) {
                    $httpKernel->setParams($route->getParams);
                    break;
                }
            // TODO check about waterfall
            case ('PUT' || 'DELETE'):
                $body = file_get_contents("php://input");
                $httpKernel->setParams($httpKernel->parseContentType($body));
                break;

            case 'POST':
                $httpKernel->setParams($_POST);
                break;

                break;
            case 'HEAD':
                //rest_head($request);
                break;

            case 'OPTIONS':
                //rest_options($request);
                break;
            default:
                //rest_error($request);
                break;
        }


    }

}
