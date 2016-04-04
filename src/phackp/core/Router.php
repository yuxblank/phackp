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
require 'http/Request.php';

class Router {
    protected static $routes;
    /**
     * Costructor reads the routes.json file as a stdClass();
     */
    private function __construct() {
        Router::$routes = json_decode(file_get_contents(APP_ROOT.'config/routes.json'));
    }
    /**
     * Return stdClass respresentation of routes.json
     * @return Router
     */
    public static function getInstance(){
        if (Router::$routes==null) {
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
    public static function go($action, $params = null, $method=null) {
        
        $route = Router::findUrl($action, $method);
        if ($route) {
            // case with N params
            if (isset($params)) {
                if (strpos($route->url, "{") || strpos($route->url, "}")) {
                    $queryString = "";
                foreach($params as $key => $value) {
                    $queryString = $route->url;
                    // find position of key
                    $currentParam = "{".$key."}";
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
            return APP_URL."404";
        }
    }
    /**
     * Redirect (302) to another action from an action.
     * @static
     * @param string $action
     * @param mixed[] $params
     */
    public static function switchAction($action,$params=null) {
        $r = Router::go($action,$params);
        header("location:$r", true, 302);
    }
    /**
     * Redirect (302) to another action from an url.
     * @static
     * @param string $action
     * @param mixed[] $params
     */
    public static function redirect($url){
        $action = Router::findAction($url);
        self::switchAction($action);
    }


    public static function _redirect($url) {
        header("location:$url",true,302);
    }


    /**
     * Find the url in routes from an action. The url returned is the first of the routes list.
     * @static
     * @param string $action
     * @return stdClass
     */
    public static function findUrl($action, $method=null) {
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
    /**
     * Read the real URL and check if exist in routes. If the route contains ? wildcards, try to replace them with current values and check for match.
     * if no indentical urls are found, returns 404.
     * @static
     * @param string $query
     * @return stdClass
     */
    public static function findAction($query) {
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
            } else if (preg_match("({[aA-zZ 0-9]+})",$route->url)) {
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
                        for ($i=0;$i<count($queryArray);$i++) {
                            if ($queryArray[$i] === $routeArray[$i]) {
                                $replaceArray[$i] = $queryArray[$i];
                            } else if (preg_match("({[aA-zZ0-9]+})",$routeArray[$i])) {
                                $replaceArray[$i] = $queryArray[$i];
                                $paramsArray[str_replace(array("{","}"),"",$routeArray[$i])] = $queryArray[$i];
                            }
                        }
                        $newUrl = implode("/", $replaceArray);
                        //echo "<br>".$newUrl;
                            if ($newUrl===$query) {
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
     * Performs a check for a valid action and method in routes. if action and method belongs to a route returns the route.
     * @static
     * @param string $action
     * @param string $method
     * @return stdClass
     */
    public static function checkRoutes($action,$method){
         foreach (Router::getInstance()->routes as $valid) {
          /*   echo $valid->action . ' == ' . $action . '|||';
             echo $valid->method . ' == ' . $method . '|||';*/
             if ($valid->method == $method && $valid->action == $action) {
                 return $valid;
             }
         }
     }
    /**
     * Performs a inverse route returning returning an array with [0 => 'Controller', 1 => 'action']
     * @param string $action
     * @return mixed[]
     */
    public static function inverseRoute($action) {
        return explode("@", $action->action);
    }
    
    
    /**
     * Performs a 404 not found
     * @static
     * @param string $action
     * @param string $method
     */
     
    public static function notFound() {
      // http_response_code(404);
        if (APP_DEBUG) {
            die("Route not found:: with method <br>");
        }
        $r = Router::go("Errors@404");
        http_response_code(404);
        header("location:$r", true);
    }
    
    
    public static function methodNotAllowed() {
            
    }

}
