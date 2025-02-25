<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');
if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "SELECT direction, start_date, start_hour, attempt, ner, asr, acd_sec FROM hourly_overview";
$params = [];
$direction_list = ["Incoming", "Outgoing"];

//Recherche sans parametre
if(!in_array($_SESSION['direction'], $direction_list) && empty($_SESSION['q'])){
	$query .= " WHERE start_date = CURRENT_DATE-1";
    $queryCount .= " WHERE start_date = CURRENT_DATE-1";
}

//Recherche par direction, date
if(in_array($_SESSION['direction'], $direction_list) && !empty($_SESSION['q'])){
	$query .= " WHERE direction LIKE :direction AND start_date LIKE :start_date";
    $queryCount .= " WHERE direction LIKE :direction AND start_date LIKE :start_date";
    $params['direction'] = '%' . $_SESSION['direction'] . '%';
	$params['start_date'] = $_SESSION['q'];
}

//Recherche par direction
if(in_array($_SESSION['direction'], $direction_list) && empty($_SESSION['q'])){
	$query .= " WHERE direction LIKE :direction";
    	$queryCount .= " WHERE direction LIKE :direction";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
}

//Recherche par date
if(!in_array($_SESSION['direction'], $direction_list) && !empty($_SESSION['q'])){
	$query .= " WHERE start_date LIKE :start_date";
    $queryCount .= " WHERE start_date LIKE :start_date";
	$params['start_date'] = $_SESSION['q'];
}

$statement = $bdd->prepare($query);
$statement->execute($params);

$list = array ();

// Append results to array
array_push($list, array("direction, start_date, start_hour, attempt, ner, asr, acd_sec"));

while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    array_push($list, array_values($row));
}

// Output array into CSV file
$filename = 'export-'.date('Y-m-d H.i.s').'.csv';
$fp = fopen('php://output', 'w');
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="'.$filename.'"');

foreach ($list as $ferow){
    fputcsv($fp, $ferow);
}

?>
