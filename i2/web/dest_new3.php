 <?php

define('LIGNES_PAR_PAGE', 20);

session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');
if(!$_SESSION['pass']){
	header('Location: login.php');
}
$query = "SELECT * FROM etl_destination";
$queryCount = "SELECT COUNT(destination) as count FROM etl_destination";
dd($queryCount);
$params = [];
$sortable = ["start_time", "destination", "carrier", "attempt", "completed", "answered", "ner", "asr", "acd_sec", "duration_min"];

//Recherche par date, destination, carrier
if(!empty($_GET['q1']) and !empty($_GET['q2']) and !empty($_GET['q3'])){
	$query .= " WHERE start_time LIKE :start_time AND destination LIKE :destination AND carrier LIKE :carrier";
    $queryCount .= " WHERE start_time LIKE :start_time AND destination LIKE :destination AND carrier LIKE :carrier";
	$params['start_time'] = '%' . $_GET['q1'] . '%';
    $params['destination'] = '%' . $_GET['q2'] . '%';
    $params['carrier'] = '%' . $_GET['q3'] . '%';  
}

//Recherche par date
if(!empty($_GET['q1']) and empty($_GET['q2']) and empty($_GET['q3'])){
	$query .= " WHERE start_time LIKE :start_time";
    $queryCount .= " WHERE start_time LIKE :start_time";
	$params['start_time'] = '%' . $_GET['q1'] . '%';
}

//Recherche par destination
if(empty($_GET['q1']) and !empty($_GET['q2']) and empty($_GET['q3'])){
	$query .= " WHERE destination LIKE :destination";
    $queryCount .= " WHERE destination LIKE :destination";
	$params['destination'] = '%' . $_GET['q2'] . '%';
}

//Recherche par carrier
if(empty($_GET['q1']) and empty($_GET['q2']) and !empty($_GET['q3'])){
	$query .= " WHERE carrier LIKE :carrier";
    $queryCount .= " WHERE carrier LIKE :carrier";
	$params['carrier'] = '%' . $_GET['q3'] . '%';
}

//Recherche par date, destination
if(!empty($_GET['q1']) and !empty($_GET['q2']) and empty($_GET['q3'])){
	$query .= " WHERE start_time LIKE :start_time AND destination LIKE :destination";
    $queryCount .= " WHERE start_time LIKE :start_time AND destination LIKE :destination";
	$params['start_time'] = '%' . $_GET['q1'] . '%';
    $params['destination'] = '%' . $_GET['q2'] . '%';
}

//Recherche par date, carrier
if(!empty($_GET['q1']) and empty($_GET['q2']) and !empty($_GET['q3'])){
	$query .= " WHERE start_time LIKE :start_time AND carrier LIKE :carrier";
    $queryCount .= " WHERE start_time LIKE :start_time AND carrier LIKE :carrier";
	$params['start_time'] = '%' . $_GET['q1'] . '%';
    $params['carrier'] = '%' . $_GET['q3'] . '%';
}

//Recherche par destination, carrier
if(empty($_GET['q1']) and !empty($_GET['q2']) and !empty($_GET['q3'])){
	$query .= " WHERE destination LIKE :destination AND carrier LIKE :carrier";
    $queryCount .= " WHERE destination LIKE :destination AND carrier LIKE :carrier";
	$params['destination'] = '%' . $_GET['q2'] . '%';
    $params['carrier'] = '%' . $_GET['q3'] . '%';
}


//tri
if(!empty($_GET['sort']) && in_array($_GET['sort'], $sortable)) {
    $direction = $_GET['dir'] ?? 'asc';
    if(!in_array($direction, ['asc', 'desc'])) {
        $direction = 'asc';
    }
    $query .= " ORDER BY " . $_GET['sort'] . " $direction";
}


//Pagination
$page = (int)($_GET['p'] ?? 1);
$offset = ($page-1) * LIGNES_PAR_PAGE;

$query .= " LIMIT " . LIGNES_PAR_PAGE . " OFFSET $offset";

$statement = $bdd->prepare($query);
$statement->execute($params);
$destinations = $statement->fetchAll();
/*value="<?= htmlentities($_GET['q'] ?? null)?>"*/

$statement = $bdd->prepare($queryCount);
$statement->execute($params);
$count = (int)$statement->fetchAll()['count'];
$pages = ceil($count / LIGNES_PAR_PAGE);

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
    <header>

    </header>

    <nav>

    </nav>
    <section>
        <h1><strong>Trafic sortant par destination et par Carrier</strong></h1>
        <form action="" class="mb-4">
            <div class="form-group">
                <input type="text" class="form-control" name="q1" placeholder="Recherche par date" value="<?= htmlentities($_GET['q1'] ?? null)?>">		
            </div><br>
            <div class="form-group">
                <input type="text" class="form-control" name="q2" placeholder="Recherche par destination" value="<?= htmlentities($_GET['q2'] ?? null)?>">		
            </div><br>
            <div class="form-group">
                <input type="text" class="form-control" name="q3" placeholder="Recherche par carrier" value="<?= htmlentities($_GET['q3'] ?? null)?>">		
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
                <?php foreach($destinations as $dest): ?>
                    <tr>
                        <td><?= $dest['start_time']?></td>
                        <td><?= $dest['destination']?></td>
                        <td><?= $dest['carrier']?></td>	
                        <td><?= $dest['attempt']?></td>
                        <td><?= $dest['completed']?></td>
                        <td><?= $dest['answered']?></td>		
                        <td><?= $dest['ner']?></td>
                        <td><?= $dest['asr']?></td>
                        <td><?= $dest['acd_sec']?></td>		
                        <td><?= $dest['duration_min']?></td>		
                    </tr>
                <?php endforeach ?>			
            </tbody>
        </table>

        <?php if ($pages > 1 && $page > 1): ?>
            <a href="?=<?= URLHelper::withParam($_GET, "p", $page - 1) ?>" class="btn btn-primary">Preview</a>
        <?php endif ?>

        <?php if ($pages > 1 && $page < $pages): ?>
            <a href="?=<?= URLHelper::withParam($_GET, "p", $page + 1) ?>" class="btn btn-primary">Next</a>
        <?php endif ?>

    </section>             
    <footer> 

    </footer>
</body>

</html>