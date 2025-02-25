 <?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');
if(!$_SESSION['pass']){
	header('Location: login.php');
}
$query = "SELECT * FROM destination";
$params = [];
//Recherche par destination
if(!empty($_GET['q'])){
	$query .= " WHERE Name LIKE :Name";
	$params['Name'] = '%' . $_GET['q'] . '%';
}

$query .= " LIMIT 500";
$statement = $bdd->prepare($query);
$statement->execute($params);
$destinations = $statement->fetchAll();
/*value="<?= htmlentities($_GET['q'] ?? null)?>"*/
?>


<!DOCTYPE html>
<html>
<head>
	<title>Destination list</title>
	<meta charset="utf-8">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</head>


<body class="p-4">

	<h1>Liste des destinations</h1>
	<form action="" class="mb-4">
		<div class="form-group">
			<input type="text" class="form-control" name="q" placeholder="Recherche par destination" value="<?= htmlentities($_GET['q'] ?? null)?>">		
		</div><br>
		<button class="btn btn-primary">Rechercher</button>	
	</form>

	<table class="table table-striped">
		<thead>
			<tr>
				<th>Id</th>
				<th>Name</th>
				<th>Country</th>				
			</tr>			
		</thead>

		<tbody>
			<?php foreach($destinations as $destination): ?>
				<tr>
					<td><?= $destination['Id']?></td>
					<td><?= $destination['Name']?></td>
					<td><?= $destination['Country']?></td>					
				</tr>
		   	<?php endforeach ?>			
		</tbody>
	</table>

</body>
</html>
