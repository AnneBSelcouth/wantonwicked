<?php
define('ROOT_PATH', dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR);

include ROOT_PATH . "cgi-bin/dbconnect.php";
include ROOT_PATH . "cgi-bin/common_functions.php";

// load composer
require_once ROOT_PATH . '../../vendor/autoload.php';

ini_set('session.name', 'CAKEPHP');
session_start();