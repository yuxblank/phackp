<?php

namespace yuxblank\phackp\http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use yuxblank\phackp\routing\api\RouteInterface;

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
    private function parseBodyByContentType()
    {
        switch ($this->getContentType()) {
            case "application/json":
                $this->request = $this->request->withParsedBody($this->parseJson($this->getRequest()->getBody()->getContents()));
                break;
            case "application/x-www-form-urlencoded":
                break;
            default:
        }
    }

    private function parsePathParams(RouteInterface $route)
    {
        if ($route->hasParams()) {
            $this->request = $this->request->withPathParams($route->getParams());
        }
    }

    /**
     * Dispatch HTTP request and parameters to the current HttpKernel instance.
     * todo refactor read route params into Router class
     * @param RouteInterface $route (the current route object)
     */
    public function parseRequest(RouteInterface $route)
    {
        $this->parsePathParams($route);
        switch ($this->request->getMethod()) {
            case 'GET':
                break;
            case 'POST':
                // if break, we cant receive body so we continue
            case ('PUT' || 'DELETE' || 'HEAD' || 'PATCH' || 'OPTIONS'):
                $this->parseBodyByContentType();
                break;
            default:
                break;
        }
    }


}