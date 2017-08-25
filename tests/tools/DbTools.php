<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 25/08/2017
 * Time: 12:03
 */

namespace test\tools;


class DbTools
{
    public static function createDatabase(){
        $path = defined("CONFIG_PATH") ? CONFIG_PATH : "../../config/";
        $config = require $path."app.php";
        $dbConf = require $path."database.php";
        $install = false;
        $db = new \yuxblank\phackp\database\Database($dbConf['database']);
        $scriptPath = defined("SCRIPT_PATH") ? SCRIPT_PATH : "../../scripts/";
        $install &= $db->query(file_get_contents($scriptPath."DDL.sql"))->execute();
        $install &= $db->query(file_get_contents($scriptPath."DML.sql"))->execute();
        return $install;
    }

    public static function truncateDatabase()
    {
        $path = defined("CONFIG_PATH") ? CONFIG_PATH : "../../config/";
        $dbConf = require $path."database.php";
        $db = new \yuxblank\phackp\database\Database($dbConf['database']);

        /** Truncate all data */
        $db->query(
            'TRUNCATE category;
                      TRUNCATE post;
                      TRUNCATE comment;
                      TRUNCATE tag;'
        );
        $db->execute();

    }



}