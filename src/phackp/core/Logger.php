<?php

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

namespace yuxblank\phackp\core;

/**
 * Description of Logger
 *
 * @author yuri.blanc
 */
class Logger {
    
    public static function error($message) {
        $date = date("d/M/y H:m:s");
        error_log("!> ERROR: ".$date. " - ". $message ."\n", 3, "logs/app.log");
    }
    
    public static function info($message) {
        $date = date("d/M/y H:m:s");
        error_log("?> INFO: " . $date. " - ". $message ."\n", 3, "logs/app.log");
    }
    
    public static function exception($exception) {
        $date = date("d/M/y H:m:s");
        error_log("!> EXCEPTION: " . $date. " - " 
                . $exception->getMessage() 
                . "\nCode: " . $exception->getCode()
                . "\nFile: " . $exception->getFile()
                . "\n.::StackTrace::.\n" .$exception->getTraceAsString()
                ."\n", 3, "logs/app.log");
    }
    
    public static function appLog() {
        
    }


}
