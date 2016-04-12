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
    //private $template = TEMPLATE;
    /**
     *
     * @var array
     */
    private $var = array();
    private $view;

    /**
     * Add data to the View object specifing a name and a value,
     * set variables will be accessible with their $name in the rendered view.
     * @param type $name Description
     */
    public function renderArgs($name, $value){
        $this->var[$name] = $value;
    }
    /**
     * // TODO conventional render()
     * Render the view. Using relative paths you can specify subfolders of view root. (e.g. blog/post = view/blog/post.php)
     * Automatically set .php extension to the view name.
     * @param string $view
     */
    public function render($view) {

        $appRoot = Application::getAppRoot();

        if (strpos($view, "/")!==false) {
            $path = implode("/",array_slice(explode("/", $view), 0,-1));
        }

        $this->page_content = $this->view = $appRoot."/src/view/$view.php";
        //$this->renderArgs("template", $this->template);
        $this->renderArgs("page_content", $this->page_content);
        extract($this->var, EXTR_OVERWRITE);

        if (!$path) {
            include $appRoot."/src/view/main.php";
        } else {
            include $appRoot."/src/view/$path/main.php";
        }

        /*if (!file_exists($appRoot."template/$this->template/index.php")) {
             throw new \IOException ("File not found: " . $appRoot."template/$this->template/index.php");
        }*/

    }
    // good as controller function
    public function renderJson($data,$options=null) {
        echo json_encode($data,$options);
    }



}
