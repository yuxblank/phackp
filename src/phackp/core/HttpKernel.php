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

    /**
     * HttpKernel constructor. When i run it capture all the information about the request and parse the url.
     * @throws \InvalidArgumentException
     */
    public function __construct()
    {
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
    public function parseParams($params)
    {
        if ($params===null) {
            return $params;
        }
        return array_merge($this->params, $params);
    }

    private function parseRouteParams($route)
    {
        if ($this->getRequest()->getMethod() !== 'GET' && array_key_exists('params', $route)) {
            return $this->parseParams($route['params']);
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
     * @return array
     * @throws \RuntimeException
     */
    public function parseBody(array $route):array
    {
        $parsed  = [];
        switch ($this->request->getMethod()) {
            case 'GET':
                // get paramets ?name=value
                if (Application::getConfig()['INJECT_QUERY_STRING']) {
                    $parsed[]= $this->parseParams($this->getRequest()->getQueryParams());
                }
                if (array_key_exists('params', $route)) {
                    $parsed[] = $this->parseParams($route['params']);
                }
                break;
            case 'POST':
                $parsed[] =  $this->parseParams($this->parseContentType($this->getRequest()->getBody()->getContents())); // todo refactor to read streams
                $parsed[]=  $this->parseRouteParams($route);
            // if break, we cant receive body so we continue
            case ('PUT' || 'DELETE' || 'HEAD' || 'PATCH' || 'OPTIONS'):
                $body = $this->getRequest()->getBody()->getContents(); // todo refactor to read streams
                $parsed[]= $this->parseParams($this->parseContentType($body));
                $parsed[]= $this->parseRouteParams($route);
                break;
            default:
                break;
        }
        return $parsed;
    }

}