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

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        if (isset($_SERVER['CONTENT_TYPE'])) {
            $this->content_type = $_SERVER['CONTENT_TYPE'];
        }
        $this->url = $this->parseUrl();

    }

    private function parseUrl()
    {
        $path = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1));
        $uri = substr($_SERVER['REQUEST_URI'], strlen($path));
        $root = $uri!=='/' ? ltrim($uri, '/') : $uri;
        return $root;
        //return explode('/', parse_url($root, PHP_URL_PATH));
    }

    /**
     * @return array
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getContentType()
    {
        return $this->content_type;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }


    public function setParams($params){
        $this->params[$this->getMethod()] = $params;
    }

    // todo implements all content type
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

    private function parseJson($jsonData) {
        return json_decode($jsonData);
    }


}