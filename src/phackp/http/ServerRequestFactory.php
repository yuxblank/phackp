<?php
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 16/07/2017
 * Time: 12:49
 */

namespace yuxblank\phackp\http;


use UnexpectedValueException;

class ServerRequestFactory extends \Zend\Diactoros\ServerRequestFactory
{
    public static function fromGlobals(
        array $server = null,
        array $query = null,
        array $body = null,
        array $cookies = null,
        array $files = null
    )
    {
        $server  = static::normalizeServer($server ?: $_SERVER);
        $files   = static::normalizeFiles($files ?: $_FILES);
        $headers = static::marshalHeaders($server);

        return new ServerRequest(
            $server,
            $files,
            static::marshalUriFromServer($server, $headers),
            static::get('REQUEST_METHOD', $server, 'GET'),
            'php://input',
            $headers,
            $cookies ?: $_COOKIE,
            $query ?: $_GET,
            $body ?: $_POST,
            static::marshalProtocolVersion($server)
        );
    }


    /**
     * Ported from zend\diactoros\ServerRequestFactory because it was private
     * Return HTTP protocol version (X.Y)
     *
     * @param array $server
     * @return string
     * @throws \UnexpectedValueException
     */
    private static function marshalProtocolVersion(array $server)
    {
        if (! isset($server['SERVER_PROTOCOL'])) {
            return '1.1';
        }

        if (! preg_match('#^(HTTP/)?(?P<version>[1-9]\d*(?:\.\d)?)$#', $server['SERVER_PROTOCOL'], $matches)) {
            throw new UnexpectedValueException(sprintf(
                'Unrecognized protocol version (%s)',
                $server['SERVER_PROTOCOL']
            ));
        }

        return $matches['version'];
    }


}