<?php

session_start();
if(!$_SESSION['pass']){
	header('Location: login.php');
}

?>

<!DOCTYPE html>

<!DOCTYPE html>
<html>
	<head>
		<title>Home</title>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Aduba Traffic Tool</h2><br/>
		<a href="stat.php">Statistics</a>
		<br>
		<a href="#">Billing</a>
		<br>
		<a href="#">Records</a>		
	</body>
</html>
