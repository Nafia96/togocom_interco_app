<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}


$query = "SELECT substr(start_date,1,7) start_month, carrier, sum(duration) duration, round(sum(revenue_cfa)/655.957) revenue_euro, round(sum(revenue_cfa)) revenue_cfa FROM `revenu`";
$params = [];

// #0 Affichage sans parametre
if(empty($_SESSION['q1']) and empty($_SESSION['q2'])){
	$query .= " WHERE substr(start_date,1,7) = concat(substr(current_date-1,1,4),'-',substr(current_date-1,5,2))";
}

// #1 Recherche par start_month, carrier
if(!empty($_SESSION['q1']) and !empty($_SESSION['q2'])){
	$query .= " WHERE substr(start_date,1,7) LIKE :start_month AND carrier LIKE :carrier";
    $params['start_month'] = '%' . $_SESSION['q1'] . '%';
	$params['carrier'] = '%' . $_SESSION['q2'] . '%';
}

// #2 Recherche par start_month
if(!empty($_SESSION['q1']) and empty($_SESSION['q2'])){
	$query .= " WHERE substr(start_date,1,7) LIKE :start_month";
	$params['start_month'] = '%' . $_SESSION['q1'] . '%';
}

//#3 Recherche par carrier
if(empty($_SESSION['q1']) and !empty($_SESSION['q2'])){
	$query .= " WHERE carrier LIKE :carrier";
	$params['carrier'] = '%' . $_SESSION['q2'] . '%';
}

$query .= " GROUP BY start_month, carrier";

$statement = $bdd->prepare($query);
$statement->execute($params);

$list = array ();

// Append results to array
array_push($list, array("start_month, carrier, volume_min, revenue_euro, revenu_cfa"));

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
