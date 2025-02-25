<?php

define("LIGNES_PAR_PAGE", 11);

session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}

/*
$query = "SELECT * FROM etl_destination";
$queryCount = "SELECT COUNT(destination) as count FROM etl_destination";
$params = [];

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

//Pagination
$page = (int)($_GET['p'] ?? 1);
$offset = ($page-1) * LIGNES_PAR_PAGE;

$query .= " LIMIT " . LIGNES_PAR_PAGE . " OFFSET $offset";

$statement = $bdd->prepare($query);
$statement->execute($params);
$destinations = $statement->fetchAll();
/*value="<?= htmlentities($_GET['q'] ?? null)?>"*

$statement = $bdd->prepare($queryCount);
$statement->execute($params);
$count = (int)$statement->fetch()['count'];
$pages = ceil($count / LIGNES_PAR_PAGE);


class URLHelper {
    public static function withParam(string $param, $value): string{
        return http_build_query(array_merge($_GET, [$param => $value]));
    }
    public static function withParams(array $params): string {
        return http_build_query(array_merge($_GET, $params));
    }
} 
*/
?>

        <?php
            require("live_header.php");
        ?>


		    <img src="image11.png" alt="image background">


        <script src="/docs/5.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

        <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="dashboard.js"></script>
    
    </body>

</html>
