<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 05/04/2016
 * Time: 14:48
 */

namespace yuxblank\phackp\core;


final class HttpKernel
{

    private $url;
    private $method;
    private $content_type;
    private $params;

    /**
     * HttpKernel constructor. When i run it capture all the information about the request and parse the url.
     */
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        if (isset($_SERVER['CONTENT_TYPE'])) {
            $this->content_type = $_SERVER['CONTENT_TYPE'];
        }
        $this->url = $this->parseUrl();

    }

    /**
     * Get the current relative url
     * @return string
     */
    private function parseUrl()
    {
        $path = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1));
        $uri = substr($_SERVER['REQUEST_URI'], strlen($path));
        $root = $uri!=='/' ? ltrim($uri, '/') : $uri;
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
    public function setParams($params){
        $this->params[$this->getMethod()] = $params;
    }


    // todo implements all content type
    /**
     * Parse the request content type
     * @param $body
     * @return mixed
     */
    public function parseContentType($body) {

        switch($this->getContentType()){
            case "application/json":
                $this->parseJson($body);
                break;
            case "application/x-www-form-urlencoded":
                 parse_str($body,$parsed);
                 return $parsed;
                break;
        }

    }

    /**
     * Read the input data and return the json as an array
     * @param $jsonData
     * @return array
     */
    private function parseJson($jsonData) {
        return json_decode($jsonData);
    }


}