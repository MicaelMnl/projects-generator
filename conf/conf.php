<?php

session_start();

////////// twig /////
define('PATH',  dirname(__FILE__).'/../');
require_once PATH.'assets/libs/Twig/Autoloader.php';
require_once 'AutoLoader.php';

Twig_Autoloader::register();
//Twig_Extensions_Autoloader::register();

$twig = new Twig_Environment(new Twig_Loader_Filesystem('templates'), array(
    'cache' => false, // '/path/to/compilation_cache',
    'debug' => true,
    
));
$twig->addExtension(new Twig_Extension_Debug());
$twig->addExtension(new Twig_Extension_I18n());

