<?php
namespace yuxblank\phackp\core;
/**
 * Created by PhpStorm.
 * User: TheCo
 * Date: 19/12/2015
 * Time: 21:20
 */



class Upload
{
    // TODO object oriented
    private $folder;
    private $extensions;



    public static function upload($file,$folder) {
        if (move_uploaded_file($file,$folder.$file)) {
            return true;
        }
    }
    public static function _upload($file,$folder,$name) {
        if (move_uploaded_file($file,$folder.$name)) {
            return true;
        }
    }

    public function fileExist($file,$folder) {
       return  file_exists($folder.$file);
    }

    public function folderExist($file,$folder) {

    }

    public function isExtensionAllowed($file,$extensions) {

    }






}