<?php
return [

    /**
     * Database array support many database, just specify ids. Default db id is default
     */

    "app.database" =>
    [
        "ID"      => "default",
        "DRIVER"  => "sqlite::memory",
        "HOST"    => null,
        "PORT"    => null,
        "USER"    => null,
        "PSW"     => null,
        "NAME"    => null,
        "OPTIONS" => [PDO::ATTR_PERSISTENT => true],
    ]

];