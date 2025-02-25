<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "SELECT * FROM trunk_sip_error";
$params = [];
$direction_list = ["Incoming", "Outgoing"];

// dates controle
if((empty($_SESSION['q1']) && !empty($_SESSION['q2'])) || (!empty($_SESSION['q1']) && empty($_SESSION['q2']))){
    $date_error = "Paramètrage de dates invalide !";
}

// #0 Affichage sans parametre
if(!in_array($_SESSION['direction'], $direction_list) && empty($_SESSION['q1']) && empty($_SESSION['q2']) && empty($_SESSION['q3']) && empty($_SESSION['q4'])){
	$query .= " WHERE start_date = CURRENT_DATE-1";
}

// #1 Recherche par direction, dates, trunk_id, carrier
if(in_array($_SESSION['direction'], $direction_list) && !empty($_SESSION['q1']) && !empty($_SESSION['q2']) && !empty($_SESSION['q3']) && !empty($_SESSION['q4'])){
	$query .= " WHERE direction LIKE :direction AND start_date BETWEEN :begin_date AND :end_date AND trunk_id LIKE :trunk_id AND carrier LIKE :carrier";
    $params['direction'] = '%' . $_SESSION['direction'] . '%'; 
    $params['trunk_id'] = '%' . $_SESSION['q1'] . '%'; 
    $params['carrier'] = '%' . $_SESSION['q2'] . '%';
    $params['begin_date'] = $_SESSION['q3'];
    $params['end_date'] = $_SESSION['q4']; 
}

// #2 Recherche par direction
if(in_array($_SESSION['direction'], $direction_list) && empty($_SESSION['q1']) && empty($_SESSION['q2']) && empty($_SESSION['q3']) && empty($_SESSION['q4'])){
	$query .= " WHERE direction LIKE :direction AND start_date = CURRENT_DATE-1";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
}

// #3 Recherche par dates
if(!in_array($_SESSION['direction'], $direction_list) && empty($_SESSION['q1']) && empty($_SESSION['q2']) && !empty($_SESSION['q3']) && !empty($_SESSION['q4'])){
    $query .= " WHERE start_date BETWEEN :begin_date AND :end_date";
    $params['begin_date'] = $_SESSION['q3'];
    $params['end_date'] = $_SESSION['q4']; 
}

// #4 Recherche par trunk_id
if(!in_array($_SESSION['direction'], $direction_list) && !empty($_SESSION['q1']) && empty($_SESSION['q2']) && empty($_SESSION['q3']) && empty($_SESSION['q4'])){
	$query .= " WHERE trunk_id LIKE :trunk_id AND start_date = CURRENT_DATE-1";
	$params['trunk_id'] = '%' . $_SESSION['q1'] . '%';
}

// #5 Recherche par carrier
if(!in_array($_SESSION['direction'], $direction_list) && empty($_SESSION['q1']) && !empty($_SESSION['q2']) && empty($_SESSION['q3']) && empty($_SESSION['q4'])){
	$query .= " WHERE carrier LIKE :carrier AND start_date = CURRENT_DATE-1";
	$params['carrier'] = '%' . $_SESSION['q2'] . '%';
}

// #6 Recherche par direction, start_date
if(in_array($_SESSION['direction'], $direction_list) && empty($_SESSION['q1']) && empty($_SESSION['q2']) && !empty($_SESSION['q3']) && !empty($_SESSION['q4'])){
	$query .= " WHERE direction LIKE :direction AND start_date BETWEEN :begin_date AND :end_date";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['begin_date'] = $_SESSION['q3'];
    $params['end_date'] = $_SESSION['q4']; 
}

// #7 Recherche par direction, trunk_id
if(in_array($_SESSION['direction'], $direction_list) && !empty($_SESSION['q1']) && empty($_SESSION['q2']) && empty($_SESSION['q3']) && empty($_SESSION['q4'])){
	$query .= " WHERE direction LIKE :direction AND trunk_id LIKE :trunk_id AND start_date = CURRENT_DATE-1";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['trunk_id'] = '%' . $_SESSION['q1'] . '%';
}

// #8 Recherche par direction, carrier
if(in_array($_SESSION['direction'], $direction_list) && empty($_SESSION['q1']) && !empty($_SESSION['q2']) && empty($_SESSION['q3']) && empty($_SESSION['q4'])){
	$query .= " WHERE direction LIKE :direction AND carrier LIKE :carrier AND start_date = CURRENT_DATE-1";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['carrier'] = '%' . $_SESSION['q2'] . '%';
}

// #9 Recherche par start_date, trunk_id
if(!in_array($_SESSION['direction'], $direction_list) && !empty($_SESSION['q1']) && empty($_SESSION['q2']) && !empty($_SESSION['q3']) && !empty($_SESSION['q4'])){
	$query .= " WHERE trunk_id LIKE :trunk_id AND start_date BETWEEN :begin_date AND :end_date";
    $params['trunk_id'] = '%' . $_SESSION['q1'] . '%';
    $params['begin_date'] = $_SESSION['q3'];
    $params['end_date'] = $_SESSION['q4']; 
}

// #10 Recherche par start_date, carrier
if(!in_array($_SESSION['direction'], $direction_list) && empty($_SESSION['q1']) && !empty($_SESSION['q2']) && !empty($_SESSION['q3']) && !empty($_SESSION['q4'])){
	$query .= " WHERE carrier LIKE :carrier AND start_date BETWEEN :begin_date AND :end_date";
	$params['carrier'] = '%' . $_SESSION['q2'] . '%';
    $params['begin_date'] = $_SESSION['q3'];
    $params['end_date'] = $_SESSION['q4']; 
}

// #11 Recherche par trunk_id, carrier
if(!in_array($_SESSION['direction'], $direction_list) && !empty($_SESSION['q1']) && !empty($_SESSION['q2']) && empty($_SESSION['q3']) && empty($_SESSION['q4'])){
	$query .= " WHERE trunk_id LIKE :trunk_id AND carrier LIKE :carrier AND start_date = CURRENT_DATE-1";
	$params['trunk_id'] = '%' . $_SESSION['q1'] . '%';
    $params['carrier'] = '%' . $_SESSION['q2'] . '%';
}

// #12 Recherche par direction, start_date, trunk_id
if(in_array($_SESSION['direction'], $direction_list) && !empty($_SESSION['q1']) && empty($_SESSION['q2']) && !empty($_SESSION['q3']) && !empty($_SESSION['q4'])){
	$query .= " WHERE direction LIKE :direction AND trunk_id LIKE :trunk_id AND start_date BETWEEN :begin_date AND :end_date";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['trunk_id'] = '%' . $_SESSION['q1'] . '%';
    $params['begin_date'] = $_SESSION['q3'];
    $params['end_date'] = $_SESSION['q4']; 
}

// #13 Recherche par direction, start_date, carrier
if(in_array($_SESSION['direction'], $direction_list) && empty($_SESSION['q1']) && !empty($_SESSION['q2']) && !empty($_SESSION['q3']) && !empty($_SESSION['q4'])){
	$query .= " WHERE direction LIKE :direction AND carrier LIKE :carrier AND start_date BETWEEN :begin_date AND :end_date";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['carrier'] = '%' . $_SESSION['q1'] . '%';
    $params['begin_date'] = $_SESSION['q3'];
    $params['end_date'] = $_SESSION['q4']; 
}

// #14 Recherche par direction, trunk_id, carrier
if(in_array($_SESSION['direction'], $direction_list) && !empty($_SESSION['q1']) && !empty($_SESSION['q2']) && empty($_SESSION['q3']) && empty($_SESSION['q4'])){
	$query .= " WHERE direction LIKE :direction AND trunk_id LIKE :trunk_id AND carrier LIKE :carrier AND start_date = CURRENT_DATE-1";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['trunk_id'] = '%' . $_SESSION['q1'] . '%';
    $params['carrier'] = '%' . $_SESSION['q2'] . '%';
}

// #15 Recherche par start_date, trunk_id, carrier
if(!in_array($_SESSION['direction'], $direction_list) && !empty($_SESSION['q1']) && !empty($_SESSION['q2']) && !empty($_SESSION['q3']) && !empty($_SESSION['q4'])){
	$query .= " WHERE trunk_id LIKE :trunk_id AND carrier LIKE :carrier AND start_date BETWEEN :begin_date AND :end_date";
    $params['trunk_id'] = '%' . $_SESSION['q1'] . '%';
    $params['carrier'] = '%' . $_SESSION['q2'] . '%';
    $params['begin_date'] = $_SESSION['q3'];
    $params['end_date'] = $_SESSION['q4']; 
}

$statement = $bdd->prepare($query);
$statement->execute($params);

$list = array ();

// Append results to array
array_push($list, array("direction,start_date,carrier,trunk_id,attempt,SIP_0,SIP_1,SIP_2,SIP_400,SIP_403,SIP_404,SIP_408,SIP_410,SIP_480,SIP_481,SIP_482,SIP_483,SIP_484,SIP_486,SIP_487,SIP_500,SIP_501,SIP_502,SIP_503,SIP_504,SIP_603,SIP_604,SIP_606"));

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
