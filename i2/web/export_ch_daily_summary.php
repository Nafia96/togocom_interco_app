<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "select direction, start_date, sum(nb_calls) nb_calls, round(sum(volume_min),3) volume_min, round(sum(charge_euro),3) charge_euro, round(sum(charge_cfa),3) charge_cfa from `charge`";
$params = [];

//Recherche sans parametres
if(empty($_SESSION['q1']) && empty($_SESSION['q2'])) {
	$query .= " WHERE substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
}

//Recherche par start_date, date
if(!empty($_SESSION['q1']) && !empty($_SESSION['q2'])) {
	$query .= " WHERE start_date BETWEEN :st_date AND :end_date";
    $params['st_date'] = $_SESSION['q1'];
    $params['end_date'] = $_SESSION['q2'];
}

$query .= " GROUP BY direction, start_date";

$statement = $bdd->prepare($query);
$statement->execute($params);

$list = array ();

// Append results to array
array_push($list, array("direction, start_date, nb_calls, volume_min, charge_euro, charge_cfa"));

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
