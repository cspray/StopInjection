<?php

// This is a convenience mechanism for testing to get the directory that libs, app
// and web directory is stored in
defined('SI_ROOT') or define('SI_ROOT', \dirname(\dirname(__DIR__)));

// Displays all array indices and object properties
ini_set('xdebug.var_display_max_children', -1);

// Displays all string data dumped
ini_set('xdebug.var_display_max_data', -1);

// Controls nested level displayed, maximum is 1023
ini_set('xdebug.var_display_max_depth', -1);

include \SI_ROOT . '/vendor/autoload.php';
