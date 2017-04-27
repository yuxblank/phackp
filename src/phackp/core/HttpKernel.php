<?php
namespace yuxblank\phackp\core;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ServerRequestFactory;

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
        if ($this->params===null) {
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
     * @return RequestInterface
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    public function getContentType():string {
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
        if (json_last_error() === JSON_ERROR_NONE){
            return $decode;
        }
        return $this->params = null;
    }


    /**
     * Parse the request content type. If application/json serialize to array.
     * If other, parse body.
     * @param $body
     * @return mixed
     */
    private function parseContentType($body)
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
     * Dispatch HTTP request and parameters to the current HttpKernel instance.
     * @param array $route (the current route object)
     * @throws \RuntimeException
     */
    public function parseBody(array $route)
    {
        switch ($this->request->getMethod()) {
            case 'GET':
                // get paramets ?name=value
                if ($this->config['INJECT_QUERY_STRING']) {
                    $this->setParams($this->getRequest()->getQueryParams());
                }
                if (array_key_exists('params', $route)) {
                    $this->setParams($route['params']);
                }
                break;
            case 'POST':
                $this->setParams($this->parseContentType($this->getRequest()->getBody()->getContents())); // todo refactor to read streams
                $this->setRouteParams($route);
            // if break, we cant receive body so we continue
            case ('PUT' || 'DELETE' || 'HEAD' || 'PATCH' || 'OPTIONS'):
                $body = $this->getRequest()->getBody()->getContents(); // todo refactor to read streams
                $this->setParams($this->parseContentType($body));
                $this->setRouteParams($route);
                break;
            default:
                break;
        }
    }

}