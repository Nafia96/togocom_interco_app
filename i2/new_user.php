<?php

session_start();
require ("menu25.php");

$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(isset($_POST['submit'])){
	if(!empty($_POST['user_name']) && !empty($_POST['password']) && !empty($_POST['confirm_password']) && !empty($_POST['rank'])){
        if($_POST['password'] === $_POST['confirm_password']){
            $sent_user_name = htmlspecialchars($_POST['user_name']);
            $sent_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $sent_rank = htmlspecialchars($_POST['rank']);   
            echo "user_name : $sent_user_name \t";
            echo "pass : $sent_password\t";
            echo "rank : $sent_rank\t";
           // exit();            
            //$sql = "INSERT INTO aduba_users(user_name, password, rank) values('$sent_user_name', '$sent_password', '$sent_rank)";
            //$req = $bdd->prepare($sql);
            //$req->execute();
            $insertion = $bdd->prepare('INSERT INTO aduba_users(user_name, password, rank) VALUES(?, ?, ?)');
            $insertion->execute(array($sent_user_name, $sent_password, $sent_rank));
            $succes = "Creation de compte effectuée avec succès ";
 		}else{
 			$error = "Fill valid informations Please !";
            header('location: new_user.php');
 		}
	}else{
        $error = "Fill valid informations Please !";
        header('location: new_user.php');
    }
}
?>

<?php if($error ): ?>
    <div class="error">
        <?= $error ?>
    </div>
<?php elseif($succes): ?>
    <div class="error">
        <?= $succes ?>
    </div>
<?php endif ?>


<!DOCTYPE html>
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
            <!-- h1>@duba connect</ -->
            
            <input type="text" placeholder="Entrer le nom d'utilisateur" name="user_name" required>

            <input type="password" placeholder="Entrer votre mot de passe" name="password" required>

            <input type="password" placeholder="Confirmer le mot de passe" name="confirm_password" required>

            <input type="text" placeholder="Niveau d'accès" name="rank" required>

            <input type="submit" id="submit" value="CREATE" name="submit" >
        </form>
    </div>
 </body>
</html>