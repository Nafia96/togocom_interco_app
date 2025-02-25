<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');
if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "SELECT direction, start_date, start_hour, carrier, attempt, ner, asr, acd_sec FROM hourly_carrier";
$params = [];
$direction_list = ["Incoming", "Outgoing"];
$hour_list = ["00","01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23"];

// #0 Affichage sans parametre
if(empty($_SESSION['direction']) && empty($_SESSION['q1']) && empty($_SESSION['q2']) && empty($_SESSION['start_hour'])){
	$query .= " WHERE start_date = CURRENT_DATE-1";
}

// #1 Recherche par direction, start_date, start_hour, carrier
if(in_array($_SESSION['direction'], $direction_list) && in_array($_SESSION['start_hour'], $hour_list) && !empty($_SESSION['q1']) && !empty($_SESSION['q2']) ){
	$query .= " WHERE direction LIKE :direction AND start_hour LIKE :start_hour AND start_date LIKE :start_date AND carrier LIKE :carrier";
    $params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['start_hour'] = '%' . $_SESSION['start_hour'] . '%'; 
    $params['start_date'] = '%' . $_SESSION['q1'] . '%'; 
    $params['carrier'] = '%' . $_SESSION['q2'] . '%'; 
}

// #2 Recherche par direction
if(in_array($_SESSION['direction'], $direction_list) && !in_array($_SESSION['start_hour'], $hour_list) && empty($_SESSION['q1']) && empty($_SESSION['q2'])){
	$query .= " WHERE direction LIKE :direction AND start_date = CURRENT_DATE-1";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
}

// #3 Recherche par start_hour
if(!in_array($_SESSION['direction'], $direction_list) && in_array($_SESSION['start_hour'], $hour_list) && empty($_SESSION['q1']) && empty($_SESSION['q2'])){
    $query .= " WHERE start_hour LIKE :start_hour AND start_date = CURRENT_DATE-1";
    $params['start_hour'] = '%' . $_SESSION['start_hour'] . '%';
}

// #4 Recherche par start_date
if(!in_array($_SESSION['direction'], $direction_list) && !in_array($_SESSION['start_hour'], $hour_list) && !empty($_SESSION['q1']) && empty($_SESSION['q2'])){
	$query .= " WHERE start_date LIKE :start_date";
	$params['start_date'] = '%' . $_SESSION['q1'] . '%';
}

// #5 Recherche par carrier
if(!in_array($_SESSION['direction'], $direction_list) && !in_array($_SESSION['start_hour'], $hour_list) && empty($_SESSION['q1']) && !empty($_SESSION['q2'])){
	$query .= " WHERE carrier LIKE :carrier AND start_date = CURRENT_DATE-1";
	$params['carrier'] = '%' . $_SESSION['q2'] . '%';
}

// #6 Recherche par direction, start_hour
if(in_array($_SESSION['direction'], $direction_list) && in_array($_SESSION['start_hour'], $hour_list) && empty($_SESSION['q1']) && empty($_SESSION['q2'])){
	$query .= " WHERE direction LIKE :direction AND start_hour LIKE :start_hour AND start_date = CURRENT_DATE-1";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['start_hour'] = '%' . $_SESSION['start_hour'] . '%';
}

// #7 Recherche par direction, start_date
if(in_array($_SESSION['direction'], $direction_list) && !in_array($_SESSION['start_hour'], $hour_list) && !empty($_SESSION['q1']) && empty($_SESSION['q2'])){
	$query .= " WHERE direction LIKE :direction AND start_date LIKE :start_date";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['start_date'] = '%' . $_SESSION['q1'] . '%';
}

// #8 Recherche par direction, carrier
if(in_array($_SESSION['direction'], $direction_list) && !in_array($_SESSION['start_hour'], $hour_list) && empty($_SESSION['q1']) && !empty($_SESSION['q2'])){
	$query .= " WHERE direction LIKE :direction AND carrier LIKE :carrier AND start_date = CURRENT_DATE-1";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['carrier'] = '%' . $_SESSION['q2'] . '%';
}

// #9 Recherche par start_hour, start_date
if(!in_array($_SESSION['direction'], $direction_list) && in_array($_SESSION['start_hour'], $hour_list) && !empty($_SESSION['q1']) && empty($_SESSION['q2'])){
	$query .= " WHERE start_hour LIKE :start_hour AND start_date LIKE :start_date";
	$params['start_hour'] = '%' . $_SESSION['start_hour'] . '%';
    $params['start_date'] = '%' . $_SESSION['q1'] . '%';
}

// #10 Recherche par start_hour, carrier
if(!in_array($_SESSION['direction'], $direction_list) && in_array($_SESSION['start_hour'], $hour_list) && empty($_SESSION['q1']) && !empty($_SESSION['q2'])){
	$query .= " WHERE start_hour LIKE :start_hour AND carrier LIKE :carrier AND start_date = CURRENT_DATE-1";
	$params['start_hour'] = '%' . $_SESSION['start_hour'] . '%';
    $params['carrier'] = '%' . $_SESSION['q2'] . '%';
}

// #11 Recherche par start_date, carrier
if(!in_array($_SESSION['direction'], $direction_list) && !in_array($_SESSION['start_hour'], $hour_list) && !empty($_SESSION['q1']) && !empty($_SESSION['q2'])){
	$query .= " WHERE start_date LIKE :start_date AND carrier LIKE :carrier";
	$params['start_date'] = '%' . $_SESSION['q1'] . '%';
    $params['carrier'] = '%' . $_SESSION['q2'] . '%';
}

// #12 Recherche par direction, start_hour, start_date
if(in_array($_SESSION['direction'], $direction_list) && in_array($_SESSION['start_hour'], $hour_list) && !empty($_SESSION['q1']) && empty($_SESSION['q2'])){
	$query .= " WHERE direction LIKE :direction AND start_hour LIKE :start_hour AND start_date LIKE :start_date";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['start_hour'] = '%' . $_SESSION['start_hour'] . '%';
    $params['start_date'] = '%' . $_SESSION['q1'] . '%';
}

// #13 Recherche par direction, start_hour, carrier
if(in_array($_SESSION['direction'], $direction_list) && in_array($_SESSION['start_hour'], $hour_list) && empty($_SESSION['q1']) && !empty($_SESSION['q2'])){
	$query .= " WHERE direction LIKE :direction AND start_hour LIKE :start_hour AND carrier LIKE :carrier AND start_date = CURRENT_DATE-1";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['start_hour'] = '%' . $_SESSION['start_hour'] . '%';
    $params['carrier'] = '%' . $_SESSION['q2'] . '%';
}

// #14 Recherche par direction, start_date, carrier
if(in_array($_SESSION['direction'], $direction_list) && !in_array($_SESSION['start_hour'], $hour_list) && !empty($_SESSION['q1']) && !empty($_SESSION['q2'])){
	$query .= " WHERE direction LIKE :direction AND start_date LIKE :start_date AND carrier LIKE :carrier";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['start_date'] = '%' . $_SESSION['q1'] . '%';
    $params['carrier'] = '%' . $_SESSION['q2'] . '%';
}

// #15 Recherche par start_hour, start_date, carrier
if(!in_array($_SESSION['direction'], $direction_list) && in_array($_SESSION['start_hour'], $hour_list) && !empty($_SESSION['q1']) && !empty($_SESSION['q2'])){
	$query .= " WHERE start_hour LIKE :start_hour AND start_date LIKE :start_date AND carrier LIKE :carrier";
	$params['start_hour'] = '%' . $_SESSION['start_hour'] . '%';
    $params['start_date'] = '%' . $_SESSION['q1'] . '%';
    $params['carrier'] = '%' . $_SESSION['q2'] . '%';
}

$statement = $bdd->prepare($query);
$statement->execute($params);

$list = array ();

// Append results to array
array_push($list, array("direction, start_date, start_hour, carrier, attempt, ner, asr, acd_sec"));

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
