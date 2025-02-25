<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "SELECT substr(start_date,1,7) start_month, network, carrier, rate, sum(nb_calls) nb_calls, round(sum(volume_min),3) volume_min, round(sum(charge_euro),3) charge_euro, round(sum(charge_cfa),3) charge_cfa from `charge`";
$params = [];

// #0 Affichage sans parametre
if(empty($_SESSION['q1']) && empty($_SESSION['q2']) && empty($_SESSION['q3']) ){
	$query .= " WHERE substr(start_date,1,7) = concat(substr(current_date-1,1,4),'-',substr(current_date-1,5,2))";
}

// #1 Recherche par start_month, network, carrier
if(!empty($_SESSION['q1']) && !empty($_SESSION['q2']) && !empty($_SESSION['q3']) ){
	$query .= " WHERE substr(start_date,1,7) LIKE :start_month AND network LIKE :network AND carrier LIKE :carrier";
    $params['start_month'] = '%' . $_SESSION['q1'] . '%';
    $params['network'] = '%' . $_SESSION['q2'] . '%';
	$params['carrier'] = '%' . $_SESSION['q3'] . '%';
}

// #2 Recherche par start_month
if(!empty($_SESSION['q1']) && empty($_SESSION['q2']) && empty($_SESSION['q3']) ){
	$query .= " WHERE substr(start_date,1,7) LIKE :start_month";
	$params['start_month'] = '%' . $_SESSION['q1'] . '%';
}

//#3 Recherche par network
if(empty($_SESSION['q1']) && !empty($_SESSION['q2']) && empty($_SESSION['q3']) ){
	$query .= " WHERE network LIKE :network";
	$params['network'] = '%' . $_SESSION['q2'] . '%';
}

//#4 Recherche par carrier
if(empty($_SESSION['q1']) && empty($_SESSION['q2']) && !empty($_SESSION['q3']) ){
	$query .= " WHERE carrier LIKE :carrier";
	$params['carrier'] = '%' . $_SESSION['q3'] . '%';
}

// #5 Recherche par start_month, network
if(!empty($_SESSION['q1']) && !empty($_SESSION['q2']) && empty($_SESSION['q3']) ){
	$query .= " WHERE substr(start_date,1,7) LIKE :start_month AND network LIKE :network";
	$params['start_month'] = '%' . $_SESSION['q1'] . '%';
    $params['network'] = '%' . $_SESSION['q2'] . '%';
}

// #6 Recherche par start_month, carrier
if(!empty($_SESSION['q1']) && empty($_SESSION['q2']) && !empty($_SESSION['q3']) ){
	$query .= " WHERE substr(start_date,1,7) LIKE :start_month AND carrier LIKE :carrier";
	$params['start_month'] = '%' . $_SESSION['q1'] . '%';
    $params['carrier'] = '%' . $_SESSION['q3'] . '%';
}

// #7 Recherche par network, carrier
if(empty($_SESSION['q1']) && !empty($_SESSION['q2']) && !empty($_SESSION['q3']) ){
	$query .= " WHERE network LIKE :network AND carrier LIKE :carrier";
	$params['network'] = '%' . $_SESSION['q2'] . '%';
    $params['carrier'] = '%' . $_SESSION['q3'] . '%';
}

$query .= " GROUP BY start_month, network, carrier, rate";

$statement = $bdd->prepare($query);
$statement->execute($params);

$list = array ();

// Append results to array
array_push($list, array("start_month, network, carrier, rate, nb_calls, volume_min, charge_euro, charge_cfa"));

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
