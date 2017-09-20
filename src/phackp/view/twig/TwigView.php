<?php
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 20/09/2017
 * Time: 20:08
 */

namespace yuxblank\phackp\view\twig;


use yuxblank\phackp\view\exception\ViewException;

class TwigView extends \Twig_Environment
{

    /**
     * TwigView constructor.
     * @param array $viewConfig
     * @param string $root
     * @throws ViewException
     */
    public function __construct(array $viewConfig, string $root=null)
    {
        if ($viewConfig['paths']) {

            throw new ViewException('View configuration does not contains any path!', ViewException::CONFIGURATION_ERROR);
        }
        parent::__construct(new \Twig_Loader_Filesystem($viewConfig['paths'], $root));
    }
}