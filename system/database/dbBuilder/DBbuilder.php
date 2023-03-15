<?php

namespace System\database\dbBuilder;

use System\config\Config;
use System\database\DB;

class DBbuilder
{
    public static function run(): void
    {
        foreach (glob(Config::get("app.BASE_DIR") . "/app/db/*.php") as $filePath) {
            $columns = require $filePath;
            $tableName = str_replace(Config::get("app.BASE_DIR") . "/app/db/", "", $filePath);
            $tableName = str_replace(".php", "", $tableName);
            if (DB::createTable($tableName, $columns)) {
                die("creat table successful");
            } else {
                die("have error in create table");
            }
        }
    }
}