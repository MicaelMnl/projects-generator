<?php
 
session_start();

include_once 'autoload.php';
include_once 'assets/libs/Twig/Autoloader.php';

Twig_Autoloader::register();

$twig = new Twig_Environment(new Twig_Loader_Filesystem('templates'), array(
    'cache' => false, // '/path/to/compilation_cache',
    'debug' => false
));

$twig->addExtension(new Twig_Extension_Debug());
$ip = $_SERVER['SERVER_ADDR'];

if( $ip == '127.0.0.1' ){

    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'DB_NAME');
    define('DB_PATH', 'DB_PATH');
    define('PATH', 'PATH');
    define('ADMIN_ID', 'root');
    define('ADMIN_PASSWORD', '');

}
else {
    define('ADMIN_ID', 'test');
    define('ADMIN_PASSWORD', 'test');
}

$twig->addGlobal('path', DB_PATH);
if(empty($_SESSION['user']['ID']) && !defined('NO_LOGIN_REQUIRED')) {
    header('Location: login.html');
    exit();
}
    

