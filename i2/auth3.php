<?php

session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

	if(!empty($_POST['login']) and !empty($_POST['pwd'])){
 		//$myLogin = "admin";
 		//$myPass = "@dmin";
 		$sentLogin = htmlspecialchars($_POST['login']);
 		//$sentPass = $_POST['pass'];

        $req = $bdd->prepare('SELECT * FROM aduba_users WHERE user_name LIKE ?');
        $req->execute(array($sentLogin));
        $count = $req->rowCount();

       // var_dump($count);
       // exit();

 		if(($count > 0) && (password_verify($_POST['pwd'], $req->fetch()['password']))){
 			$_SESSION['ID'] = $req->fetch()['userID'];
            $_SESSION['pwd'] = $_POST['pwd'];
			$_SESSION['user_name'] = $sentLogin;
 			header('Location: daily_overview.php');
 		}else{
 			$error="Wrong login or pass! Please retry ..";
             //header('Location: auth3.php');
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

            <input type="password" placeholder="Entrer le mot de passe" name="pwd" required>

            <input type="submit" id='submit' value='LOGIN' >
        </form>
    </div>
 </body>
</html>
