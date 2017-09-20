<?php
namespace yuxblank\phackp\routing\api;
use Psr\Http\Message\UriInterface;
use yuxblank\phackp\http\api\ServerRequestInterface;
use yuxblank\phackp\routing\exception\RouterException;

interface Router
{
    /**
     * Return a link URL.
     * @param string $link
     * @param array|null $params
     * @throws RouterException
     * @return string
     */
    public function link(string $link, array $params = null):string;

    /**
     * Return the link URL by a given alias
     * @param string $alias
     * @param String|null $method
     * @param array|null $params
     * @throws RouterException
     * @return string
     */
    public function alias(string $alias, String $method = null, array $params = null):string;

    /**
     * Redirect to an internal or by link (path)
     * @param UriInterface $uri
     * @throws RouterException
     * @return mixed
     */
    public function redirect(UriInterface $uri);

    /**
     * Redirect to an internal route
     * @param string $uri
     * @param array|null $params
     * @return mixed
     * @throws RouterException
     */
    public function switchAction(string $uri, array $params = null);
    /**
     * Redirect to an internal route by alias
     * @param $alias $uri
     * @param array|null $params
     * @return mixed
     * @throws RouterException
     */
    public function _switchAction(string $alias, array $params = null);

    /**
     * Find the action for the give ServerRequest. The method it's invoked by pHackp runtime in order to detect
     * the current route.
     * Must return the actual route.
     * @throws RouterException
     * @return RouteInterface
     */
    public function findAction():RouteInterface;

    /**
     * Get an Error route by error code.
     * @param int $code
     * @throws RouterException
     * @return RouteInterface
     */
    public function getErrorRoute(int $code):RouteInterface;

    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function match(ServerRequestInterface $request):bool;

    /**
     * @param RouteInterface $route
     * @throws RouterException
     * @return UriInterface
     */
    public function generateUri(RouteInterface $route):UriInterface;
}