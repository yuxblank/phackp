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
 * Class with helper methods for string encryption, security object and tools.
 *
 * @author yuri.blanc
 */
class Crypto {

    
    public function generateHash($password) {
        return sha1($password);
    }
    
    public function checkHash($password,$string) {
        if (sha1($string) == $password) {
            return true;
        }
    }
    
    
    
    
    

}
