<?php

function loadClass($class) {
    if (file_exists(PATH . 'classes/' . $class . '.php')) {
        require_once PATH . 'classes/' . $class . '.php';
    } elseif (file_exists(PATH . 'managers/' . $class . '.php')) {
        require_once PATH . 'managers/' . $class . '.php';
    }
}

spl_autoload_register('loadClass');
