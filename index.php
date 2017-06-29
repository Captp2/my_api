<!DOCTYPE html>
<?php ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); ?>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>My API</title>
	<link rel="stylesheet" type="text/css" href="public/css/reset_css.css"/>
	<link rel="stylesheet" type="text/css" href="public/css/style.css"/>
</head>
<?php require('./api.php');
$api = new MyAPI();
$api->getAllUsers();
?>
<body id="home">
	<h1>Welcome on my API !</h1>
</body>
</html>