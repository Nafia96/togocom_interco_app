<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');
if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "SELECT direction, start_date, network, answered, duration_min FROM `network_traffic`";
$params = [];
$direction_list = ["Incoming", "Outgoing"];

// dates controle
if((empty($_SESSION['q1']) && !empty($_SESSION['q1'])) || (!empty($_SESSION['q1']) && empty($_SESSION['q1']))){
    $date_error = "Paramètrage de dates invalide !";
}

// #0 Affichage sans parametre
if(!in_array($_SESSION['direction'], $direction_list) && empty($_SESSION['q']) && empty($_SESSION['q1']) && empty($_SESSION['q2'])){
	$query .= " WHERE start_date = CURRENT_DATE-1";
}

// #1 Recherche par direction, date, network
if(in_array($_SESSION['direction'], $direction_list) && !empty($_SESSION['q']) && !empty($_SESSION['q1']) && !empty($_SESSION['q2'])){
	$query .= " WHERE direction LIKE :direction AND network LIKE :network AND start_date BETWEEN :begin_date AND :end_date";
    $params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['network'] = '%' . $_SESSION['q'] . '%';
    $params['begin_date'] = $_SESSION['q1'];
    $params['end_date'] = $_SESSION['q2'];
} 

// #2 Recherche par direction
if(in_array($_SESSION['direction'], $direction_list) && empty($_SESSION['q']) && empty($_SESSION['q1']) && empty($_SESSION['q2'])){
	$query .= " WHERE direction LIKE :direction AND start_date = CURRENT_DATE-1";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
}

// #3 Recherche par dates
if(!in_array($_SESSION['direction'], $direction_list) && empty($_SESSION['q']) && !empty($_SESSION['q1']) && !empty($_SESSION['q2'])){
	$query .= " WHERE start_date BETWEEN :begin_date AND :end_date";
	$params['begin_date'] = $_SESSION['q1'];
    $params['end_date'] = $_SESSION['q2'];
}

// #4 Recherche par network
if(!in_array($_SESSION['direction'], $direction_list) && !empty($_SESSION['q']) && empty($_SESSION['q1']) && empty($_SESSION['q2'])){
    $query .= " WHERE network LIKE :network AND start_date = CURRENT_DATE-1";
    $params['network'] = '%' . $_SESSION['q'] . '%';
}

// #5 Recherche par direction, date
if(in_array($_SESSION['direction'], $direction_list) && empty($_SESSION['q']) && !empty($_SESSION['q1']) && !empty($_SESSION['q2'])){
	$query .= " WHERE direction LIKE :direction AND start_date BETWEEN :begin_date AND :end_date";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['begin_date'] = $_SESSION['q1'];
    $params['end_date'] = $_SESSION['q2'];
}

// #6 Recherche par direction, network
if(in_array($_SESSION['direction'], $direction_list) && !empty($_SESSION['q']) && empty($_SESSION['q1']) && empty($_SESSION['q2'])){
	$query .= " WHERE direction LIKE :direction AND network LIKE :network AND start_date = CURRENT_DATE-1";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['network'] = '%' . $_SESSION['q'] . '%';
}

// #7 Recherche par date, network
if(!in_array($_SESSION['direction'], $direction_list) && !empty($_SESSION['q']) && !empty($_SESSION['q1']) && !empty($_SESSION['q2'])){
	$query .= " WHERE start_date BETWEEN :begin_date AND :end_date AND network LIKE :network";
    $params['network'] = '%' . $_SESSION['q'] . '%';
    $params['begin_date'] = $_SESSION['q1'];
    $params['end_date'] = $_SESSION['q2'];
}

$statement = $bdd->prepare($query);
$statement->execute($params);

$list = array ();

// Append results to array
array_push($list, array("direction, start_date, network, nb_call, volume_min"));

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
