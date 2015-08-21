<?php

class Database extends PDO {

    private static $_db = DB_NAME;
    private static $_host = DB_HOST;
    private static $_user = DB_USER;
    private static $_pass = DB_PASSWORD;
    private static $_instance;

    public function __construct() {}

    public static function getInstance() {
        if (!isset(self::$_instance)) {
            try {
                self::$_instance = new PDO('mysql:dbname='.self::$_db.';host='.self::$_host, self::$_user, self::$_pass,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
            } 
            catch (PDOException $e) {
                echo 'Connection à MySQL impossible : ', $e->getMessage();
            }
        } 
        return self::$_instance;	
    }
}