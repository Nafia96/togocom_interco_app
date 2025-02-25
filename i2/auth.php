<?php

session_start();

	if(!empty($_POST['login']) and !empty($_POST['pass'])){
 		$myLogin = "admin";
 		$myPass = "@dmin";
 		$sentLogin = htmlspecialchars($_POST['login']);
 		$sentPass = htmlspecialchars($_POST['pass']);
 		if($sentLogin == $myLogin and $sentPass == $myPass){
 			$_SESSION['pass'] = $myPass;
 			header('Location: daily_overview.php');
 		}else{
 			$error="Wrong login or password ! Please try again...";
 		}
	}
?>

<?php if($error): ?>
    <div class="error">
        <?= $error ?>
    </div>
<?php endif ?>


<!doctype html>
<html>
 <head>
 <meta charset="utf-8">
 <!-- importer le fichier de style -->
 <link rel="stylesheet" href="style1.css" media="screen" type="text/css" />
 </head>
 <body>
    <div id="container">
    <!-- zone de connexion -->
    
        <form action="" method="POST">
            <h1>@duba connect</h1>
            
            <input type="text" placeholder="Entrer le nom d'utilisateur" name="login" required>

            <input type="password" placeholder="Entrer le mot de passe" name="pass" required>

            <input type="submit" id='submit' value='LOGIN' >
        </form>
    </div>
 </body>
</html>
