<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');
if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "SELECT direction, carrier, sum(answered) nb_calls, sum(duration_min) duration_min FROM carrier_traffic";
$params = [];
$direction_list = ["Incoming", "Outgoing"];


// #0 Affichage sans parametre
if(!in_array($_SESSION['direction'], $direction_list) && empty($_SESSION['q']) && empty($_SESSION['q1']) && empty($_SESSION['q2'])){
	$query .= " WHERE substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
}

// #1 Recherche par direction, dates, carrier
if(in_array($_SESSION['direction'], $direction_list) && !empty($_SESSION['q']) && !empty($_SESSION['q1']) && !empty($_SESSION['q2'])){
	$query .= " WHERE direction LIKE :direction AND carrier LIKE :carrier AND start_date BETWEEN :begin_date AND :end_date";
    $params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['carrier'] = '%' . $_SESSION['q'] . '%';
    $params['begin_date'] = $_SESSION['q1'];
    $params['end_date'] = $_SESSION['q2'];
}

// #2 Recherche par direction
if(in_array($_SESSION['direction'], $direction_list) && empty($_SESSION['q']) && empty($_SESSION['q1']) && empty($_SESSION['q2'])){
	$query .= " WHERE direction LIKE :direction AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
}

// #3 Recherche par start_date
if(!in_array($_SESSION['direction'], $direction_list) && empty($_SESSION['q']) && !empty($_SESSION['q1']) && !empty($_SESSION['q2'])){
	$query .= " WHERE start_date BETWEEN :begin_date AND :end_date";
    $params['begin_date'] = $_SESSION['q1'];
    $params['end_date'] = $_SESSION['q2'];
}

// #4 Recherche par carrier
if(!in_array($_SESSION['direction'], $direction_list) && !empty($_SESSION['q']) && empty($_SESSION['q1']) && empty($_SESSION['q2'])){
    $query .= " WHERE carrier LIKE :carrier AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2)) ";
    $params['carrier'] = '%' . $_SESSION['q'] . '%';
}

// #5 Recherche par direction, date
if(in_array($_SESSION['direction'], $direction_list)  && empty($_SESSION['q']) && !empty($_SESSION['q1']) && !empty($_SESSION['q2'])){
	$query .= " WHERE direction LIKE :direction AND start_date BETWEEN :begin_date AND :end_date";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['begin_date'] = $_SESSION['q1'];
    $params['end_date'] = $_SESSION['q2'];
}

// #6 Recherche par direction, carrier
if(in_array($_SESSION['direction'], $direction_list) && !empty($_SESSION['q']) && empty($_SESSION['q1']) && empty($_SESSION['q2'])){
	$query .= " WHERE direction LIKE :direction AND carrier LIKE :carrier AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
	$params['direction'] = '%' . $_SESSION['direction'] . '%';
    $params['carrier'] = '%' . $_SESSION['q'] . '%';
}

// #7 Recherche par date, carrier
if(!in_array($_SESSION['direction'], $direction_list) && !empty($_SESSION['q']) && !empty($_SESSION['q1']) && !empty($_SESSION['q2'])){
	$query .= " WHERE start_date BETWEEN :begin_date AND :end_date AND carrier LIKE :carrier";
    $params['begin_date'] = $_SESSION['q1'];
    $params['end_date'] = $_SESSION['q2'];
    $params['carrier'] = '%' . $_SESSION['q'] . '%';
}

    $query .= " group by direction, carrier";

$statement = $bdd->prepare($query);
$statement->execute($params);

$list = array ();

// Append results to array
array_push($list, array("direction, carrier, nb_call, volume_min"));

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
