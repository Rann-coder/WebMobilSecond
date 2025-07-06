<?php

namespace Uph\Mobilsecond;

class DB{
    public static function getDB(){
        return new \PDO(
            'mysql:host=127.0.0.1;dbname=web_mobil_second',
            'root', //user
            '' //password
        );
    }
}