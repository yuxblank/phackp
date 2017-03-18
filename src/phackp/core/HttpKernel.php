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

    /**
     * HttpKernel constructor. When i run it capture all the information about the request and parse the url.
     * @throws \InvalidArgumentException
     */
    public function __construct()
    {
        $this->request = ServerRequestFactory::fromGlobals();

    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }


}