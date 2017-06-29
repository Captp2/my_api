<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 
require('./my_rest.php');
require('./api.php');
$api = new MyAPI();
?>