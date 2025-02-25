<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "SELECT start_date, carrier, sum(duration) duration_min, round(sum(revenue_cfa)/655.957) revenue_euro, round(sum(revenue_cfa)) revenue_cfa FROM `revenu`";
$params = [];

// dates controle
if((empty($_SESSION['q1']) && !empty($_SESSION['q2'])) || (!empty($_SESSION['q1']) && empty($_SESSION['q2']))){
    echo "Dates invalides";
}

// #0 Affichage sans parametre
if(empty($_SESSION['q']) &&  empty($_SESSION['q1']) && empty($_SESSION['q2'])){
	$query .= " WHERE start_date = current_date-1";
}

// #1 Recherche par start_date, carrier
if(!empty($_SESSION['q']) &&  !empty($_SESSION['q1']) && !empty($_SESSION['q2'])){
	$query .= " WHERE start_date BETWEEN :begin_date AND :end_date AND carrier LIKE :carrier";
	$params['carrier'] = '%' . $_SESSION['q'] . '%';
    $params['begin_date'] = $_SESSION['q1'];
    $params['end_date'] = $_SESSION['q2'];
}

// #2 Recherche par start_date
if(empty($_SESSION['q']) &&  !empty($_SESSION['q1']) && !empty($_SESSION['q2'])){
	$query .= " WHERE start_date BETWEEN :begin_date AND :end_date";
    $params['begin_date'] = $_SESSION['q1'];
    $params['end_date'] = $_SESSION['q2'];
}

// #3 Recherche par carrier
if(!empty($_SESSION['q']) &&  empty($_SESSION['q1']) && empty($_SESSION['q2'])){
	$query .= " WHERE carrier LIKE :carrier AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
	$params['carrier'] = '%' . $_SESSION['q'] . '%';
}

$query .= " GROUP BY start_date, carrier";

$statement = $bdd->prepare($query);
$statement->execute($params);

$list = array ();

// Append results to array
array_push($list, array("start_date, carrier, volume_min, revenue_euro, revenu_cfa"));

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
