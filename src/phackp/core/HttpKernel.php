<?php
namespace yuxblank\phackp\core;

/**
 * Class HttpKernel
 * @author Yuri Blanc
 * @package yuxblank\phackp\core
 */
final class HttpKernel
{

    private $url;
    private $method;
    private $content_type;
    private $params = array();

    /**
     * HttpKernel constructor. When i run it capture all the information about the request and parse the url.
     */
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];

        if (array_key_exists('CONTENT_TYPE', $_SERVER)) {
            $this->content_type = $_SERVER['CONTENT_TYPE'];
        }
        $this->url = $this->parseUrl();
        if (array_key_exists('QUERY_STRING', $_SERVER)) {
            $this->queryString = '?' . $_SERVER['QUERY_STRING'];
            // remove query string from internal url
            $this->url = str_replace($this->queryString, '', $this->url);
            if ($this->url === '') {
                $this->url = '/';
            }
        }

    }

    /**
     * Get the current relative url
     * @return string
     */
    private function parseUrl()
    {
        $path = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1));
        $uri = substr($_SERVER['REQUEST_URI'], strlen($path));
        $root = $uri !== '/' ? ltrim($uri, '/') : $uri;
        return $root;
        //return explode('/', parse_url($root, PHP_URL_PATH));
    }

    /**
     * Return actual url
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Return the request METHOD
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Return the request content-type
     * @return string
     */
    public function getContentType()
    {
        return $this->content_type;
    }

    /**
     * return the array of params if any.
     * if no parameters has been found return null
     * @return array|null
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set parameters to the request
     * @param $params
     */
    public function setParams($params)
    {
        if ($this->params===null) {
            $this->params = $params;
        } else {
            $this->params = array_merge($this->params, $params);
        }
    }

    /**
     * Parse the request content type. If application/json serialize to array.
     * If other, parse body.
     * @param $body
     * @return mixed
     */
    public function parseContentType($body)
    {

        switch ($this->getContentType()) {
            case "application/json":
                return $this->parseJson($body);
                break;
            case "application/x-www-form-urlencoded":
                parse_str($body, $parsed);
                return $parsed;
                break;

            default:
                parse_str($body, $parsed);
                return $parsed;
        }

    }


    /**
     * Set http response code by given number
     * @param int $status
     */
    public function HTTPStatus(int $status)
    {

        http_response_code($status);
        /*   switch($status){
               case 415:
                   http_response_code(415);
           }*/

    }

    /**
     * Read the input data and return the json as an array
     * Todo manage json errors
     * @param $jsonData
     * @return array
     */
    private function parseJson($jsonData)
    {
        $decode = json_decode($jsonData, true);
        if (json_last_error() === JSON_ERROR_NONE){
            return $decode;
        }
        return $this->params = null;
    }


    /**
     * Dispatch HTTP request and parameters to the current HttpKernel instance.
     * @param array $route (the current route object)
     */
    public function dispatch(array $route)
    {

        switch ($this->getMethod()) {
            case 'GET':
                // get paramets ?name=value
                if (Application::getConfig()['INJECT_QUERY_STRING']) {
                    $this->setParams($_GET);
                }
                if (array_key_exists('params', $route)) {
                    $this->setParams($route['params']);
                }
                break;

            case 'POST':
                $this->setParams($_POST);
                $this->setRouteParams($route);

                // if break, we cant receive body so we continue

            case ('PUT' || 'DELETE' || 'HEAD' || 'PATCH' || 'OPTIONS'):
                $body = file_get_contents('php://input');
                $this->setParams($this->parseContentType($body));
                $this->setRouteParams($route);
                break;

            default:
                break;
        }
    }

    private function setRouteParams($route)
    {
        if ($this->getMethod() !== 'GET' && array_key_exists('params', $route)) {
            $this->setParams($route['params']);
        }

    }

}