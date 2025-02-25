<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "SELECT direction, start_date, network, attempt, ner, asr, acd_sec FROM network_traffic2";
$params = [];

// dates controle
if((empty($_GET['q1']) && !empty($_GET['q2'])) || (!empty($_GET['q1']) && empty($_GET['q2']))){
    $date_error = "Paramètrage de dates invalide !";
}

// #0 Affichage sans parametre
if(empty($_GET['q']) && empty($_GET['q1']) && empty($_GET['q2'])){
	$query .= " WHERE start_date = CURRENT_DATE-1";
}

//Recherche par date, network
if(!empty($_GET['q']) && !empty($_GET['q1']) && !empty($_GET['q2'])){
	$query .= " WHERE start_date BETWEEN :begin_date AND :end_date AND network LIKE :network";
	$params['network'] = '%' . $_GET['q'] . '%';
    $params['begin_date'] = $_GET['q1'];
    $params['end_date'] = $_GET['q2'];
} 

//Recherche par date
if(empty($_GET['q']) && !empty($_GET['q1']) && !empty($_GET['q2'])){
	$query .= " WHERE start_date BETWEEN :begin_date AND :end_date";
	$params['begin_date'] = $_GET['q1'];
    $params['end_date'] = $_GET['q2'];
}

//Recherche par network
if(!empty($_GET['q']) && empty($_GET['q1']) && empty($_GET['q2'])){
	$query .= " WHERE network LIKE :network AND start_date = CURRENT_DATE-1";
	$params['network'] = '%' . $_GET['q'] . '%';
}

$query .= " AND direction = 'Outgoing' AND net_code in ('30109','30108','30402','30848','30164','30837','30166','30838','30570','30572','30839','30846','30691','30569','30351','31254','30847','30165','30444','30401','30359','30694','30403','30360','30373','31008','30371','30358','30107','30185','30190','30353','31250','30354','30362','31088','30393','30204','30184','30356','30386','30370','30845','30199','30573','30318','30375','30779','30200','30395')";

$statement = $bdd->prepare($query);
$statement->execute($params);

$list = array ();

// Append results to array
array_push($list, array("direction, start_date, network, attempt, ner, asr, acd_sec"));

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
