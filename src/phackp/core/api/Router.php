<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 28/06/2017
 * Time: 11:58
 */

namespace yuxblank\phackp\core\api;


interface Router
{
    public function link(string $link, array $params = null);
    public function alias(string $alias, String $method = null, array $params = null);
    public function redirect(string $url, bool $external=null);
    public function findAction();
    public function getErrorRoute(int $code);
}