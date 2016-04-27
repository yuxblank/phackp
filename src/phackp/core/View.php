<?php
namespace yuxblank\phackp\core;
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
class View {
    /**
     *
     * @var array
     */
    private $var = array();

    private $content;

    /**
     * Add data to the View object specifing a name and a value,
     * set variables will be accessible with their $name in the rendered view.
     * @param string $name Description
     */
    public function renderArgs($name, $value){
        $this->var[$name] = $value;
    }

    public function getArgs($name) {
        return $this->var[$name];
    }

    /**
     * // TODO conventional render()
     * Render the view. Using relative paths you can specify subfolders of view root. (e.g. blog/post = view/blog/post.php)
     * Automatically set .php extension to the view name.
     * @param string $view
     */
    public function render(string $view) {

        $appRoot = Application::getViewRoot();

        if (strpos($view, "/")!==false) {
            $path = implode("/", array_slice(explode("/", $view), 0, -1));
        }

        //$this->renderArgs('PAGE_CONTENT', $appRoot."/src/view/$view.php");
        $this->content = $appRoot.'/'.$view.".php";
        extract($this->var, EXTR_OVERWRITE);

        if (!$path) {
            include $appRoot."/main.php";
        } else {
            include $appRoot."/$path/main.php";
        }


        /*if (!file_exists($appRoot."template/$this->template/index.php")) {
             throw new \IOException ("File not found: " . $appRoot."template/$this->template/index.php");
        }*/

    }

    /**
     * Render view hook
     */

    public function content() {
        extract($this->var, EXTR_SKIP);
        include $this->content;
    }


    /**
     * Render an hook. To pass child variables to the hook scope use the array params
     * with an associative array of 'variable_name' => 'variable_value'.
     * The variable will be available in the hook view as $_varName. ('$_' prefix)
     * @param string $hook name of the hook
     * @param array|null $args associative array of params for the hook scope.
     */
    public function hook(string $hook, array $args=null) {
        if ($args!==null) {
            extract($args, EXTR_PREFIX_ALL,'');
        }
        if ($hook)
            $path = Application::getAppRoot()  . '/src/view/' . Application::getConfig()['VIEW']['HOOKS'][$hook];
        if (file_exists($path) ) {
            include $path;
        }
    }



}
