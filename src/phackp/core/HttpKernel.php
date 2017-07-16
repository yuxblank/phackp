<?php

namespace yuxblank\phackp\core;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use yuxblank\phackp\http\ServerRequestFactory;
use Zend\Diactoros\ServerRequest;

/**
 * Class HttpKernel
 * @author Yuri Blanc
 * @package yuxblank\phackp\core
 */
final class HttpKernel
{
    /** @var  ServerRequestInterface */
    private $request;
    private $params;
    private $config;

    /**
     * HttpKernel constructor. When i run it capture all the information about the request and parse the url.
     * @param array $config
     * @throws \InvalidArgumentException
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->request = ServerRequestFactory::fromGlobals();
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
        if ($this->params === null) {
            $this->params = $params;
        } else {
            $this->params = array_merge($this->params, $params);
        }
    }

    private function setRouteParams($route)
    {
        if ($this->getRequest()->getMethod() !== 'GET' && array_key_exists('params', $route)) {
            $this->setParams($route['params']);
        }
    }


    /**
     * @return RequestInterface|ServerRequest
     */
    public function getRequest(): ServerRequest
    {
        return $this->request;
    }

    public function getContentType(): string
    {
        return $this->request->getHeaderLine('Content-type');
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
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decode;
        }
        return $this->params = null;
    }


    /**
     * Parse the request content type. If application/json serialize to array.
     * If other, parse body.
     * @return mixed
     * @throws \RuntimeException
     */
    protected function parseBodyByContentType()
    {
        switch ($this->getContentType()) {
            case "application/json":
                return $this->parseJson($this->getRequest()->getBody()->getContents());
                break;
            case "application/x-www-form-urlencoded":
                return $this->request->getParsedBody();
                break;
            default:
                return $this->request->getParsedBody();
        }
    }

    /**
     * Dispatch HTTP request and parameters to the current HttpKernel instance.
     * todo refactor read route params into Router class
     * @param array $route (the current route object)
     * @throws \RuntimeException
     */
    public function parseRequest(array $route)
    {
        switch ($this->request->getMethod()) {
            case 'GET':
                if (array_key_exists('params', $route)) {
                    $this->setParams($route['params']);
                }
                break;
            case 'POST':
                $this->parseBodyByContentType();
                $this->setRouteParams($route);
            // if break, we cant receive body so we continue
            case ('PUT' || 'DELETE' || 'HEAD' || 'PATCH' || 'OPTIONS'):
                $this->setRouteParams($route);
                $this->parseBodyByContentType();
                break;
            default:
                break;
        }
        if ($this->params!==null) {
            $this->request = $this->request->withPathParams($this->params);
        }
    }

}