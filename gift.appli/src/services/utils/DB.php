<?php

namespace gift\app\services\utils;

use Illuminate\Database\Capsule\Manager;

class DB extends Manager
{
    public static function initConnection(): void
    {
        $db = new Manager();
        $db->addConnection(parse_ini_file(ROOT.'conf/gift.db.conf.ini'));
        $db->setAsGlobal();
        $db->bootEloquent();
    }
}