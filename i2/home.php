<?php
	$pdo = new PDO('mysql:host=localhost;dbname=inter_traffic;','Isaac', 'Kaes@2021');

	echo"data base connectee !";

	//$cdrs = $pdo->query('SELECT * FROM rec202106 LIMIT 20')->fetchAll();

?>





<!DOCTYPE html>
<html>
<head>
	<title>International Traffic</title>
	<meta charset="utf-8">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>


<h1>CDR TRAFIC INTERNATIONAL</h1>

<form action="" class="mp-15">
	<div class="form-group">
		<input type="text" class="form-control "name="q" placeholder="Recherche par appelant">		
	</div>
	<button class="btn btn-primary">Rechercher</button>	
</form>

</head>
<body class="p-4">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Direction</th>
				<th>Calling_number</th>
				<th>Called_number</th>
				<th>Seize_date</th>
				<th>Seize_time</th>
				<th>Charge_time</th>
				<th>Completion_code</th>
				<th>Ressource_group</th>
				<th>Other_res_group</th>
				<th>Release_cause</th>	
				<th>Route_list</th>	
				<th>ENS_release</th>	
				<th>Tag</th>	
			</tr>
			
		</thead>

		<tbody>
			<?php foreach($cdrs as $cdr): ?>
				<tr>
					<td><?= $cdr['Direction']?></td>
					<td><?= $cdr['Calling_number']?></td>
					<td><?= $cdr['Called_number']?></td>
					<td><?= $cdr['Seize_date']?></td>
					<td><?= $cdr['Seize_time']?></td>
					<td><?= $cdr['Charge_time']?></td>
					<td><?= $cdr['Completion_code']?></td>
					<td><?= $cdr['Ressource_group']?></td>
					<td><?= $cdr['Other_res_group']?></td>
					<td><?= $cdr['Release_cause']?></td>	
					<td><?= $cdr['Route_list']?></td>	
					<td><?= $cdr['ENS_release']?></td>	
					<td><?= $cdr['Tag']?></td>
				</tr>
		   	<?php endforeach ?>			
		</tbody>
	</table>

</body>
</html>