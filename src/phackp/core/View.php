<?php
namespace yuxblank\phackp\core;
use yuxblank\phackp\routing\api\Router;
/*
 * Copyright (C) 2015 yuri.blanc
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


/**
 * The view object has all methods used for creating views and passing data.
 * @version 0.1
 * @author yuri.blanc
 */
class View
{
    /**
     *
     * @var array
     */
    private $var = array();

    private $content;
    protected $router;
    protected $viewConfig;

    /**
     * View constructor.
     * @param array $viewConfig
     * @param array $appGlobals
     * @param Router $router
     * @internal param array $var
     */
    public function __construct(array $viewConfig, array $appGlobals, Router $router)
    {
        $this->viewConfig = array_merge($appGlobals, $viewConfig);
        $this->router = $router;

    }

    public function getConfig()
    {
        return $this->viewConfig;
    }

    /**
     * Add data to the View object specifing a name and a value,
     * set variables will be accessible with their $name in the rendered view.
     * @param string $name Description
     */
    public function renderArgs($name, $value)
    {
        $this->var[$name] = $value;
    }

    public function getArgs($name)
    {
        return $this->var[$name];
    }

    /**
     * Render the view. Using relative paths you can specify subfolders of view root. (e.g. blog/post = view/blog/post.php)
     * Automatically set .php extension to the view name.
     * @param string $view
     */
    public function render(string $view)
    {


        $appRoot = $this->viewConfig['APP_ROOT'] . DIRECTORY_SEPARATOR . $this->viewConfig['ROOT'];
        if ($view !== null) {
            $path = null;
            if (strpos($view, "/") !== false) {
                $path = implode("/", array_slice(explode("/", $view), 0, -1));
            }
            $this->content = $appRoot . DIRECTORY_SEPARATOR . $view . ".php";
            extract(array_merge($this->var, $this->viewConfig), EXTR_OVERWRITE);
            if (!$path) {
                include $appRoot . DIRECTORY_SEPARATOR . 'main.php';
            } else {
                include $appRoot . DIRECTORY_SEPARATOR . $path .DIRECTORY_SEPARATOR. 'main.php';
            }
        }
    }

    /**
     * Render view hook
     */

    public function content()
    {
        extract(array_merge($this->var, $this->viewConfig), EXTR_SKIP);
        include $this->content;
    }


    /**
     * Render an hook. To pass child variables to the hook scope use the array params
     * with an associative array of 'variable_name' => 'variable_value'.
     * The variable will be available in the hook view as $_varName. ('$_' prefix)
     * @param string $hook name of the hook
     * @param array|null $args associative array of params for the hook scope.
     */
    public function hook(string $hook, array $args = null)
    {
        if ($args !== null) {
            extract(array_merge($this->var, $this->viewConfig), EXTR_OVERWRITE);
            extract($args, EXTR_PREFIX_ALL, '');
        }
        $path = null;
        if ($hook)
            $path = $this->viewConfig['APP_ROOT'] . DIRECTORY_SEPARATOR .  $this->viewConfig['ROOT'] . DIRECTORY_SEPARATOR . $this->viewConfig['HOOKS'][$hook];
        if ($path!==null && file_exists($path)) {
            include $path;
        }
    }


}
