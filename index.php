<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 
require('./lib/controller/my_rest.php');
require('./lib/controller/api.php');
$api = new MyAPI();
?>