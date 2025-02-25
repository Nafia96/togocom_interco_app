<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "SELECT direction, substr(start_date,1,7) start_month, sum(attempt) attempt, concat(round(sum(completed)/sum(attempt)*100),'%') ner, concat(round(sum(answered)/sum(attempt)*100),'%') asr, round(sum(duration_min)/sum(answered)*60) acd_sec FROM `carrier_traffic`";
$params = [];
$direction_list = ["Incoming", "Outgoing"];

//Recherche sans parametre
if(!in_array($_SESSION['direction'], $direction_list) && empty($_SESSION['q'])){
	$query .= " WHERE 1";
    $queryCount .= " WHERE 1";
}

//Recherche par direction, start_month
if(in_array($_SESSION['direction'], $direction_list) && !empty($_SESSION['q'])){
	$query .= " WHERE direction LIKE :direction AND substr(start_date,1,7) LIKE :start_month";
    $queryCount .= " WHERE direction LIKE :direction AND substr(start_date,1,7) LIKE :start_month";
    $params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['start_month'] = '%' . $_SESSION['q'] . '%';
}

//Recherche par direction
if(in_array($_SESSION['direction'], $direction_list) && empty($_SESSION['q'])){
	$query .= " WHERE direction LIKE :direction";
    $queryCount .= " WHERE direction LIKE :direction";
    $params['direction'] = '%' . $_SESSION['direction'] . '%';
}

//Recherche par start_month
if(!in_array($_SESSION['direction'], $direction_list) && !empty($_SESSION['q'])){
	$query .= " WHERE substr(start_date,1,7) LIKE :start_month";
    $queryCount .= " WHERE substr(start_date,1,7) LIKE :start_month";
    $params['start_month'] = '%' . $_SESSION['q'] . '%';
}

$query .= " GROUP BY direction, start_month";

$statement = $bdd->prepare($query);
$statement->execute($params);

$list = array ();

// Append results to array
array_push($list, array("direction, start_month, attempt, ner, asr, acd_sec"));

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
