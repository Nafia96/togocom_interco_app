<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "SELECT substr(start_date,1,7) start_month, sum(duration) duration_min, round(sum(revenue_cfa)/655.957) revenue_euro, round(sum(revenue_cfa)) revenue_cfa FROM `revenu`";
$params = [];

//Recherche par start_month
if(!empty($_SESSION['q'])){
	$query .= " WHERE substr(start_date,1,7) LIKE :start_month";
    $params['start_month'] = '%' . $_SESSION['q'] . '%';
}

$query .= " GROUP BY start_month";

$statement = $bdd->prepare($query);
$statement->execute($params);

$list = array ();

// Append results to array
array_push($list, array("start_month, duration_min, revenue_euro, revenue_cfa"));

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
