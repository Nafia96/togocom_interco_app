<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "SELECT * FROM daily_sip_error";
$params = [];
$direction_list = ["Incoming", "Outgoing"];

// dates controle
if((empty($_SESSION['q1']) && !empty($_SESSION['q2'])) || (!empty($_SESSION['q1']) && empty($_SESSION['q2']))){
    $date_error = "Paramètrage de dates invalide !";
}

// #0 Affichage sans parametre
if(!in_array($_SESSION['direction'], $direction_list) && empty($_SESSION['q1']) && empty($_SESSION['q2'])){
	$query .= " WHERE substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
}

// #1 Recherche par direction, date
if(in_array($_SESSION['direction'], $direction_list) && !empty($_SESSION['q1']) && !empty($_SESSION['q2'])){
	$query .= " WHERE direction LIKE :direction AND start_date BETWEEN :begin_date AND :end_date";
    $params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['begin_date'] = $_SESSION['q1'];
    $params['end_date'] = $_SESSION['q2'];
}

// #2 Recherche par direction
if(in_array($_SESSION['direction'], $direction_list) && empty($_SESSION['q1']) && empty($_SESSION['q2'])){
	$query .= " WHERE direction LIKE :direction AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
}

//Recherche par date
if(!in_array($_SESSION['direction'], $direction_list) && !empty($_SESSION['q1']) && !empty($_SESSION['q2'])){
	$query .= " WHERE start_date BETWEEN :begin_date AND :end_date";
    $params['begin_date'] = $_SESSION['q1'];
    $params['end_date'] = $_SESSION['q2'];
}

$statement = $bdd->prepare($query);
$statement->execute($params);

$list = array ();

// Append results to array
array_push($list, array("direction,start_date,attempt,SIP_0,SIP_1,SIP_2,SIP_400,SIP_403,SIP_404,SIP_408,SIP_410,SIP_480,SIP_481,SIP_482,SIP_483,SIP_484,SIP_486,SIP_487,SIP_500,SIP_501,SIP_502,SIP_503,SIP_504,SIP_603,SIP_604,SIP_606"));

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
