<?php

session_start();
$bdd = new PDO('mysql:host=localhost;dbname=espace_admin;','Isaac', 'Kaes@2021');
if(!$_SESSION['pass']){
	header('Location: login.php');
}


?>


<!DOCTYPE html>
<!DOCTYPE html>
<html>
<head>
	<title>Afiicher les membres</title>
	<meta charset="utf-8">
</head>

<body>
	<?php
		$recupUsers = $bdd->query('SELECT * FROM users');
		while($user = $recupUsers->fetch()){
			//echo $user['pseudo'];
		?>

		<p><?= $user['pseudo']; ?> <a href="bannir.php?id=<?= $user['id']; ?>" style="color:red; text-decoration: none;">Bannir le membre </a></p>


		<?php
		}
	?>
</body>
</html>