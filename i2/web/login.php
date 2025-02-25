<?php

session_start();

if(isset($_POST['submit'])){
	if(!empty($_POST['login']) and !empty($_POST['pass'])){
 		$myLogin = "admin";
 		$myPass = "@dmin";

 		$sentLogin = htmlspecialchars($_POST['login']);
 		$sentPass = htmlspecialchars($_POST['pass']);

 		if($sentLogin == $myLogin and $sentPass == $myPass){

 			$_SESSION['pass'] = $myPass;
 			header('Location: index.php');


 		}else{
 			echo "Wrong login or pass! Please retry ..";
 		}

	}else{
		echo "Please fill all forms";
	}
}

?>





<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<meta charset="utf-8">	
	</head>
	<body>
		<form method="POST" action="" align="center">
			<input type="text" name="login">
			<br>
			<input type="password" name="pass">
			<br><br>
			<input type="submit" name="submit">
		</form>
	</body>
</html>