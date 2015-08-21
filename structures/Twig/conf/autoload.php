<?php

    function loadClass($class){
        if(file_exists('classes/' . $class . '.php')) {
            require_once 'classes/' . $class . '.php';
        }
        elseif(file_exists('managers/' . $class . '.php')) {
            require_once 'managers/' . $class . '.php';
        }
    }

    spl_autoload_register('loadClass'); 