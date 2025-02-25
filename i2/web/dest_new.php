 <?php

define("PER_PAGE", 10);

session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');
if(!$_SESSION['pass']){
	header('Location: login.php');
}
$query = "SELECT * FROM etl_destination";
$queryCount = "SELECT COUNT(destination) AS count FROM etl_destination";
$params = [];

//Recherche par destination
if(!empty($_GET['q'])){
	$query .= " WHERE destination LIKE :destination";
	$params['destination'] = '%' . $_GET['q'] . '%';
}

$query .= " LIMIT " . PER_PAGE;

$statement = $bdd->prepare($query);
$statement->execute($params);
$destinations = $statement->fetchAll();
/*value="<?= htmlentities($_GET['q'] ?? null)?>"*/

$statement = $bdd->prepare($queryCount);
$statement->execute();
$count = (int)$statement->fetch()['count'];
$nb_pages = ceil($count / PER_PAGE);

var_dump($nb_pages);

?>

<!DOCTYPE html>
<html>
<head>
	<title>Trafic sortant par destination et par Carrier</title>
	<meta charset="utf-8">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</head>


<body class="p-4">

	<h1>Trafic sortant par destination et par Carrier</h1>
	<form action="" class="mb-4">
		<div class="form-group">
			<input type="text" class="form-control" name="q" placeholder="Recherche par destination" value="<?= htmlentities($_GET['q'] ?? null)?>">		
		</div><br>
		<button class="btn btn-primary">Rechercher</button>	
	</form>

	<table class="table table-striped">
		<thead>
			<tr>
				<th>start_time</th>
				<th>destination</th>
				<th>carrier</th>	
				<th>attempt</th>
				<th>completed</th>
				<th>answered</th>
				<th>ner</th>
				<th>asr</th>
				<th>acd_sec</th>	
				<th>duration_min</th>		
			</tr>			
		</thead>

		<tbody>
			<?php foreach($destinations as $destination): ?>
				<tr>
					<td><?= $destination['start_time']?></td>
					<td><?= $destination['destination']?></td>
					<td><?= $destination['carrier']?></td>	
					<td><?= $destination['attempt']?></td>
					<td><?= $destination['completed']?></td>
					<td><?= $destination['answered']?></td>		
					<td><?= $destination['ner']?></td>
					<td><?= $destination['asr']?></td>
					<td><?= $destination['acd_sec']?></td>		
					<td><?= $destination['duration_min']?></td>		
				</tr>
		   	<?php endforeach ?>			
		</tbody>
	</table>

</body>
</html>