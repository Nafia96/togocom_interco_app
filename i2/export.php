<?php

//define("LIGNES_PAR_PAGE", 10);

session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}


$stmt = $bdd->prepare("SELECT direction, start_date, carrier, answered, duration_min FROM `carrier_traffic`");
$stmt->execute();
//$carriers = $stmt->fetchAll();

/*$filelocation = 'file/';
$filename     = 'export-'.date('Y-m-d H.i.s').'.csv';
$file_export  =  $filelocation . $filename;
header( 'Content-Type: text/csv; charset=utf-8; encoding=UTF-8' );
header("Cache-Control: cache, must-revalidate");
header("Pragma: public");
header( 'Content-Disposition: attachment;filename='.$filename.'' );
$data = fopen($file_export, 'w');

$csv_fields = array();
$csv_fields[] = 'direction';
$csv_fields[] = 'start_date';
$csv_fields[] = 'carrier';
$csv_fields[] = 'answered';
$csv_fields[] = 'duration_min';

fputcsv($data, $csv_fields);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($data, $row);
}*/
            $list = array ();

            // Append results to array
            array_push($list, array("direction, start_date, carrier, nb_call, volume_min"));
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($list, array_values($row));
            }
            //array_push($list, array("## END OF USER TABLE ##"));

            // Output array into CSV file
            $filename = 'export-'.date('Y-m-d H.i.s').'.csv';
            $fp = fopen('php://output', 'w');
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
/*
            $csv_fields = array();
            $csv_fields[] = 'direction';
            $csv_fields[] = 'start_date';
            $csv_fields[] = 'carrier';
            $csv_fields[] = 'answered';
            $csv_fields[] = 'duration_min';
 */

            foreach ($list as $ferow) {
                fputcsv($fp, $ferow);
            }

?>
