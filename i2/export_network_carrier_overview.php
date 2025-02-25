<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');
if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "SELECT direction, start_date, network, carrier, attempt, ner, asr, acd_sec FROM network_carrier";
$params = [];
$$direction_list = ["Incoming", "Outgoing"];

// dates controle
if((empty($_SESSION['q1']) && !empty($_SESSION['q2'])) || (!empty($_SESSION['q1']) && empty($_SESSION['q2']))){
    $date_error = "Paramètrage de dates invalide !";
}

// #0 Affichage sans parametre
if(!in_array($_SESSION['direction'], $$direction_list) && empty($_SESSION['q1']) && empty($_SESSION['q2']) && empty($_SESSION['q3']) && empty($_SESSION['q4'])){
	$query .= " WHERE start_date = CURRENT_DATE-1";
    $queryCount .= " WHERE start_date = CURRENT_DATE-1";
}

// #1 Recherche par direction, dates, network, carrier
if(in_array($_SESSION['direction'], $$direction_list) && !empty($_SESSION['q1']) && !empty($_SESSION['q2']) && !empty($_SESSION['q3']) && !empty($_SESSION['q4'])){
	$query .= " WHERE direction LIKE :direction AND start_date BETWEEN :begin_date AND :end_date AND network LIKE :network AND carrier LIKE :carrier";
    $queryCount .= " WHERE direction LIKE :direction AND start_date BETWEEN :begin_date AND :end_date AND network LIKE :network AND carrier LIKE :carrier";
    $params['direction'] = '%' . $_SESSION['direction'] . '%'; 
    $params['network'] = '%' . $_SESSION['q1'] . '%'; 
    $params['carrier'] = '%' . $_SESSION['q2'] . '%';
    $params['begin_date'] = $_SESSION['q3'];
    $params['end_date'] = $_SESSION['q4']; 
}

// #2 Recherche par direction
if(in_array($_SESSION['direction'], $$direction_list) && empty($_SESSION['q1']) && empty($_SESSION['q2']) && empty($_SESSION['q3']) && empty($_SESSION['q4'])){
	$query .= " WHERE direction LIKE :direction AND start_date = CURRENT_DATE-1";
    $queryCount .= " WHERE direction LIKE :direction AND start_date = CURRENT_DATE-1";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
}

// #3 Recherche par dates
if(!in_array($_SESSION['direction'], $$direction_list) && empty($_SESSION['q1']) && empty($_SESSION['q2']) && !empty($_SESSION['q3']) && !empty($_SESSION['q4'])){
    $query .= " WHERE start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE start_date BETWEEN :begin_date AND :end_date";
    $params['begin_date'] = $_SESSION['q3'];
    $params['end_date'] = $_SESSION['q4']; 
}

// #4 Recherche par network
if(!in_array($_SESSION['direction'], $$direction_list) && !empty($_SESSION['q1']) && empty($_SESSION['q2']) && empty($_SESSION['q3']) && empty($_SESSION['q4'])){
	$query .= " WHERE network LIKE :network AND start_date = CURRENT_DATE-1";
    $queryCount .= " WHERE network LIKE :network AND start_date = CURRENT_DATE-1";
	$params['network'] = '%' . $_SESSION['q1'] . '%';
}

// #5 Recherche par carrier
if(!in_array($_SESSION['direction'], $$direction_list) && empty($_SESSION['q1']) && !empty($_SESSION['q2']) && empty($_SESSION['q3']) && empty($_SESSION['q4'])){
	$query .= " WHERE carrier LIKE :carrier AND start_date = CURRENT_DATE-1";
    $queryCount .= " WHERE carrier LIKE :carrier AND start_date = CURRENT_DATE-1";
	$params['carrier'] = '%' . $_SESSION['q2'] . '%';
}

// #6 Recherche par direction, start_date
if(in_array($_SESSION['direction'], $$direction_list) && empty($_SESSION['q1']) && empty($_SESSION['q2']) && !empty($_SESSION['q3']) && !empty($_SESSION['q4'])){
	$query .= " WHERE direction LIKE :direction AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE direction LIKE :direction AND start_date BETWEEN :begin_date AND :end_date";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['begin_date'] = $_SESSION['q3'];
    $params['end_date'] = $_SESSION['q4']; 
}

// #7 Recherche par direction, network
if(in_array($_SESSION['direction'], $$direction_list) && !empty($_SESSION['q1']) && empty($_SESSION['q2']) && empty($_SESSION['q3']) && empty($_SESSION['q4'])){
	$query .= " WHERE direction LIKE :direction AND network LIKE :network AND start_date = CURRENT_DATE-1";
    $queryCount .= " WHERE direction LIKE :direction AND network LIKE :network AND start_date = CURRENT_DATE-1";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['network'] = '%' . $_SESSION['q1'] . '%';
}

// #8 Recherche par direction, carrier
if(in_array($_SESSION['direction'], $$direction_list) && empty($_SESSION['q1']) && !empty($_SESSION['q2']) && empty($_SESSION['q3']) && empty($_SESSION['q4'])){
	$query .= " WHERE direction LIKE :direction AND carrier LIKE :carrier AND start_date = CURRENT_DATE-1";
    $queryCount .= " WHERE direction LIKE :direction AND carrier LIKE :carrier AND start_date = CURRENT_DATE-1";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['carrier'] = '%' . $_SESSION['q2'] . '%';
}

// #9 Recherche par start_date, network
if(!in_array($_SESSION['direction'], $$direction_list) && !empty($_SESSION['q1']) && empty($_SESSION['q2']) && !empty($_SESSION['q3']) && !empty($_SESSION['q4'])){
	$query .= " WHERE network LIKE :network AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE network LIKE :network AND start_date BETWEEN :begin_date AND :end_date";
    $params['network'] = '%' . $_SESSION['q1'] . '%';
    $params['begin_date'] = $_SESSION['q3'];
    $params['end_date'] = $_SESSION['q4']; 
}

// #10 Recherche par start_date, carrier
if(!in_array($_SESSION['direction'], $$direction_list) && empty($_SESSION['q1']) && !empty($_SESSION['q2']) && !empty($_SESSION['q3']) && !empty($_SESSION['q4'])){
	$query .= " WHERE carrier LIKE :carrier AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE carrier LIKE :carrier AND start_date BETWEEN :begin_date AND :end_date";
	$params['carrier'] = '%' . $_SESSION['q2'] . '%';
    $params['begin_date'] = $_SESSION['q3'];
    $params['end_date'] = $_SESSION['q4']; 
}

// #11 Recherche par network, carrier
if(!in_array($_SESSION['direction'], $$direction_list) && !empty($_SESSION['q1']) && !empty($_SESSION['q2']) && empty($_SESSION['q3']) && empty($_SESSION['q4'])){
	$query .= " WHERE network LIKE :network AND carrier LIKE :carrier AND start_date = CURRENT_DATE-1";
    $queryCount .= " WHERE network LIKE :network AND carrier LIKE :carrier AND start_date = CURRENT_DATE-1";
	$params['network'] = '%' . $_SESSION['q1'] . '%';
    $params['carrier'] = '%' . $_SESSION['q2'] . '%';
}

// #12 Recherche par direction, start_date, network
if(in_array($_SESSION['direction'], $$direction_list) && !empty($_SESSION['q1']) && empty($_SESSION['q2']) && !empty($_SESSION['q3']) && !empty($_SESSION['q4'])){
	$query .= " WHERE direction LIKE :direction AND network LIKE :network AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE direction LIKE :direction AND network LIKE :network AND start_date BETWEEN :begin_date AND :end_date";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['network'] = '%' . $_SESSION['q1'] . '%';
    $params['begin_date'] = $_SESSION['q3'];
    $params['end_date'] = $_SESSION['q4']; 
}

// #13 Recherche par direction, start_date, carrier
if(in_array($_SESSION['direction'], $$direction_list) && empty($_SESSION['q1']) && !empty($_SESSION['q2']) && !empty($_SESSION['q3']) && !empty($_SESSION['q4'])){
	$query .= " WHERE direction LIKE :direction AND carrier LIKE :carrier AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE direction LIKE :direction AND carrier LIKE :carrier AND start_date BETWEEN :begin_date AND :end_date";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['carrier'] = '%' . $_SESSION['q1'] . '%';
    $params['begin_date'] = $_SESSION['q3'];
    $params['end_date'] = $_SESSION['q4']; 
}

// #14 Recherche par direction, network, carrier
if(in_array($_SESSION['direction'], $$direction_list) && !empty($_SESSION['q1']) && !empty($_SESSION['q2']) && empty($_SESSION['q3']) && empty($_SESSION['q4'])){
	$query .= " WHERE direction LIKE :direction AND network LIKE :network AND carrier LIKE :carrier AND start_date = CURRENT_DATE-1";
    $queryCount .= " WHERE direction LIKE :direction AND network LIKE :network AND carrier LIKE :carrier AND start_date = CURRENT_DATE-1";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['network'] = '%' . $_SESSION['q1'] . '%';
    $params['carrier'] = '%' . $_SESSION['q2'] . '%';
}

// #15 Recherche par start_date, network, carrier
if(!in_array($_SESSION['direction'], $$direction_list) && !empty($_SESSION['q1']) && !empty($_SESSION['q2']) && !empty($_SESSION['q3']) && !empty($_SESSION['q4'])){
	$query .= " WHERE network LIKE :network AND carrier LIKE :carrier AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE network LIKE :network AND carrier LIKE :carrier AND start_date BETWEEN :begin_date AND :end_date";
    $params['network'] = '%' . $_SESSION['q1'] . '%';
    $params['carrier'] = '%' . $_SESSION['q2'] . '%';
    $params['begin_date'] = $_SESSION['q3'];
    $params['end_date'] = $_SESSION['q4']; 
}

$statement = $bdd->prepare($query);
$statement->execute($params);

$list = array ();

// Append results to array
array_push($list, array("direction, start_date, network, carrier, attempt, ner, asr, acd_sec"));

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
