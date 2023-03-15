<?php

namespace System\database\dbconnection;

use PDO, PDOException;
use System\config\Config;

class DBConnection
{
    private static $dbconn = null;

    private function __construct()
    {
    }

    public static function getDBConnection(): PDO
    {
        if (self::$dbconn == null) {
            $DBConnObj = new DBConnection();
            self::$dbconn = $DBConnObj->dbConnection();
        }
        return self::$dbconn;
    }

    private function dbConnection(): bool|PDO
    {
        $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8");
        try {
            return new PDO("mysql:host=" . Config::get("db.SERVER_NAME") . ";dbname=" . Config::get("db.DB_NAME"), Config::get("db.USERNAME"), Config::get("db.PASSWORD"), $options);
        } catch (PDOException $e) {
            echo "error in database connection: " . $e->getMessage();
            return false;
        }
    }

    public static function lastInsertID(): bool|string
    {
        return self::getDBConnection()->lastInsertId();
    }

}