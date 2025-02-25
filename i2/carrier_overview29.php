<?php

define("LIGNES_PAR_PAGE", 10);

session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$params = [];
$sortable = ["direction", "date_range", "carrier", "attempt", "completed", "answered"];
$direction_list = ["Incoming", "Outgoing"];
$group_list = ["Day", "Month", "Year"];

$queryCarrier = "SELECT DISTINCT carrier_name FROM carrier_tab ORDER BY carrier_name";
$statement = $bdd->query($queryCarrier);
$carriers = $statement->fetchAll(PDO::FETCH_ASSOC);
$all_carrier = "All Carriers";
$carriers[]['carrier_name'] = $all_carrier;

#1 View by group
if(!isset($_GET['group']) && !isset($_GET['direction']) && !isset($_GET['carrier_name']) && empty($_GET['q1']) && empty($_GET['q2'])){
    $query = "SELECT direction, start_date date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE start_date BETWEEN current_date - interval 1 day AND current_date - interval 1 day";
    $queryCount = "SELECT COUNT(*) count FROM (SELECT direction, start_date date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE start_date BETWEEN current_date - interval 1 day AND current_date - interval 1 day";
}elseif(!in_array($_GET['direction'], $direction_list) && strcmp($_GET['carrier_name'],$all_carrier) === 0  && empty($_GET['q1']) && empty($_GET['q2']) && $_GET['group'] === $group_list[0]){
    $query = "SELECT direction, start_date date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE start_date BETWEEN current_date - interval 1 day AND current_date - interval 1 day";
    $queryCount = "SELECT COUNT(*) count FROM (SELECT direction, start_date date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE start_date BETWEEN current_date - interval 1 day AND current_date - interval 1 day";
}elseif(!in_array($_GET['direction'], $direction_list) && strcmp($_GET['carrier_name'],$all_carrier) === 0  && empty($_GET['q1']) && empty($_GET['q2']) && $_GET['group'] === $group_list[1]){
    $query = "SELECT direction, substr(start_date,1,7) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE substr(start_date,1,7) = substr(current_date - interval 1 day,1,7)";
    $queryCount = "SELECT COUNT(*) count FROM (SELECT direction, substr(start_date,1,7) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE substr(start_date,1,7) = substr(current_date - interval 1 day,1,7)";
}elseif(!in_array($_GET['direction'], $direction_list) && strcmp($_GET['carrier_name'],$all_carrier) === 0  && empty($_GET['q1']) && empty($_GET['q2']) && $_GET['group'] === $group_list[2]){
    $query = "SELECT direction, substr(start_date,1,4) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE substr(start_date,1,4) = substr(current_date - interval 1 day,1,4)";
    $queryCount = "SELECT COUNT(*) count FROM (SELECT direction, substr(start_date,1,4) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE substr(start_date,1,4) = substr(current_date - interval 1 day,1,4)";
}

#2 View by period, group
if(!in_array($_GET['direction'], $direction_list) && strcmp($_GET['carrier_name'],$all_carrier) === 0 && !empty($_GET['q1']) && !empty($_GET['q2']) && $_GET['group'] === $group_list[0] ){
    $query = "SELECT direction, start_date date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE start_date BETWEEN :begin_date AND :end_date";
    $queryCount = "SELECT COUNT(*) count FROM (SELECT direction, start_date date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE start_date BETWEEN :begin_date AND :end_date";
    $params['begin_date'] = $_GET['q1'];
    $params['end_date'] = $_GET['q2'];
}elseif(!in_array($_GET['direction'], $direction_list) && strcmp($_GET['carrier_name'],$all_carrier) === 0 && !empty($_GET['q1']) && !empty($_GET['q2']) && $_GET['group'] === $group_list[1] ){
    $query = "SELECT direction, substr(start_date,1,7) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE substr(start_date,1,7) BETWEEN :start_month AND :end_month";
    $queryCount = "SELECT COUNT(*) count FROM (SELECT direction, substr(start_date,1,7) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE substr(start_date,1,7) BETWEEN :start_month AND :end_month";
    $params['start_month'] = substr($_GET['q1'],0,7);
    $params['end_month'] = substr($_GET['q2'],0,7);
}elseif(!in_array($_GET['direction'], $direction_list) && strcmp($_GET['carrier_name'],$all_carrier) === 0 && !empty($_GET['q1']) && !empty($_GET['q2']) && $_GET['group'] === $group_list[2] ){
    $query = "SELECT direction, substr(start_date,1,4) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE substr(start_date,1,4) BETWEEN :start_year AND :end_year";
    $queryCount = "SELECT COUNT(*) count FROM (SELECT direction, substr(start_date,1,4) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE substr(start_date,1,4) BETWEEN :start_year AND :end_year";
    $params['start_year'] = substr($_GET['q1'],0,4);
    $params['end_year'] = substr($_GET['q2'],0,4);
}

#3 View by carrier, group
if(!in_array($_GET['direction'], $direction_list) && strcmp($_GET['carrier_name'],$all_carrier) !== 0 && empty($_GET['q1']) && empty($_GET['q2']) && $_GET['group'] === $group_list[0] ){
    $query = "SELECT direction, start_date date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE carrier like :carrier AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount = "SELECT COUNT(*) count FROM (SELECT direction, start_date date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE carrier like :carrier AND start_date BETWEEN :begin_date AND :end_date";
    $params['carrier'] = $_GET['carrier_name'];
    $params['begin_date'] = "2024-02-01";
    $params['end_date'] = "2024-02-01";
}elseif(!in_array($_GET['direction'], $direction_list) && strcmp($_GET['carrier_name'],$all_carrier) !== 0 && empty($_GET['q1']) && empty($_GET['q2']) && $_GET['group'] === $group_list[1] ){
    $query = "SELECT direction, substr(start_date,1,7) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE carrier like :carrier AND substr(start_date,1,7) BETWEEN :start_month AND :end_month";
    $queryCount = "SELECT COUNT(*) count FROM (SELECT direction, substr(start_date,1,7) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE carrier like :carrier AND substr(start_date,1,7) BETWEEN :start_month AND :end_month";
    $params['carrier'] = $_GET['carrier_name'];
    $params['start_month'] = "2024-02";
    $params['end_month'] = "2024-02";
}elseif(!in_array($_GET['direction'], $direction_list) && strcmp($_GET['carrier_name'],$all_carrier) !== 0 && empty($_GET['q1']) && empty($_GET['q2']) && $_GET['group'] === $group_list[2] ){
    $query = "SELECT direction, substr(start_date,1,4) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE carrier like :carrier AND substr(start_date,1,4) BETWEEN :start_year AND :end_year";
    $queryCount = "SELECT COUNT(*) count FROM (SELECT direction, substr(start_date,1,4) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE carrier like :carrier AND substr(start_date,1,4) BETWEEN :start_year AND :end_year";
    $params['carrier'] = $_GET['carrier_name'];
    $params['start_year'] = "2024";
    $params['end_year'] = "2024";
}

#4 View by direction, group
if(in_array($_GET['direction'], $direction_list) && strcmp($_GET['carrier_name'],$all_carrier) === 0 && empty($_GET['q1']) && empty($_GET['q2']) && $_GET['group'] === $group_list[0] ){
    $query = "SELECT direction, start_date date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE direction like :direction AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount = "SELECT COUNT(*) count FROM (SELECT direction, start_date date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE direction like :direction AND start_date BETWEEN :begin_date AND :end_date";
    $params['direction'] = $_GET['direction'];
    $params['begin_date'] = "2024-02-01";
    $params['end_date'] = "2024-02-01";
}elseif(in_array($_GET['direction'], $direction_list) && strcmp($_GET['carrier_name'],$all_carrier) === 0 && empty($_GET['q1']) && empty($_GET['q2']) && $_GET['group'] === $group_list[1] ){
    $query = "SELECT direction, substr(start_date,1,7) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE direction like :direction AND substr(start_date,1,7) BETWEEN :start_month AND :end_month";
    $queryCount = "SELECT COUNT(*) count FROM (SELECT direction, substr(start_date,1,7) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE direction like :direction AND substr(start_date,1,7) BETWEEN :start_month AND :end_month";
    $params['direction'] = $_GET['direction'];
    $params['start_month'] = "2024-02";
    $params['end_month'] = "2024-02";
}elseif(in_array($_GET['direction'], $direction_list) && strcmp($_GET['carrier_name'],$all_carrier) === 0 && empty($_GET['q1']) && empty($_GET['q2']) && $_GET['group'] === $group_list[2] ){
    $query = "SELECT direction, substr(start_date,1,4) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE direction like :direction AND substr(start_date,1,4) BETWEEN :start_year AND :end_year";
    $queryCount = "SELECT COUNT(*) count FROM (SELECT direction, substr(start_date,1,4) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE direction like :direction AND substr(start_date,1,4) BETWEEN :start_year AND :end_year";
    $params['direction'] = $_GET['direction'];
    $params['start_year'] = "2024";
    $params['end_year'] = "2024";
}

#5 View by carrier, period, group
if(!in_array($_GET['direction'], $direction_list) && strcmp($_GET['carrier_name'],$all_carrier) !== 0 && !empty($_GET['q1']) && !empty($_GET['q2']) && $_GET['group'] === $group_list[0] ){
    $query = "SELECT direction, start_date date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE carrier like :carrier AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount = "SELECT COUNT(*) count FROM (SELECT direction, start_date date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE carrier like :carrier AND start_date BETWEEN :begin_date AND :end_date";
    $params['carrier'] = $_GET['carrier_name'];
    $params['begin_date'] = $_GET['q1'];
    $params['end_date'] = $_GET['q2'];
}elseif(!in_array($_GET['direction'], $direction_list) && strcmp($_GET['carrier_name'],$all_carrier) !== 0 && !empty($_GET['q1']) && !empty($_GET['q2']) && $_GET['group'] === $group_list[1] ){
    $query = "SELECT direction, substr(start_date,1,7) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE carrier like :carrier AND substr(start_date,1,7) BETWEEN :start_month AND :end_month";
    $queryCount = "SELECT COUNT(*) count FROM (SELECT direction, substr(start_date,1,7) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE carrier like :carrier AND substr(start_date,1,7) BETWEEN :start_month AND :end_month";
    $params['carrier'] = $_GET['carrier_name'];
    $params['start_month'] = substr($_GET['q1'],0,7);
    $params['end_month'] = substr($_GET['q2'],0,7);
}elseif(!in_array($_GET['direction'], $direction_list) && strcmp($_GET['carrier_name'],$all_carrier) !== 0 && !empty($_GET['q1']) && !empty($_GET['q2']) && $_GET['group'] === $group_list[2] ){
    $query = "SELECT direction, substr(start_date,1,4) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE carrier like :carrier AND substr(start_date,1,4) BETWEEN :start_year AND :end_year";
    $queryCount = "SELECT COUNT(*) count FROM (SELECT direction, substr(start_date,1,4) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE carrier like :carrier AND substr(start_date,1,4) BETWEEN :start_year AND :end_year";
    $params['carrier'] = $_GET['carrier_name'];
    $params['start_year'] = substr($_GET['q1'],0,4);
    $params['end_year'] = substr($_GET['q2'],0,4);
}

#6 View by direction, period, group
if(in_array($_GET['direction'], $direction_list) && strcmp($_GET['carrier_name'],$all_carrier) === 0 && !empty($_GET['q1']) && !empty($_GET['q2']) && $_GET['group'] === $group_list[0] ){
    $query = "SELECT direction, start_date date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE direction like :direction AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount = "SELECT COUNT(*) count FROM (SELECT direction, start_date date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE direction like :direction AND start_date BETWEEN :begin_date AND :end_date";
    $params['direction'] = $_GET['direction'];
    $params['begin_date'] = $_GET['q1'];
    $params['end_date'] = $_GET['q2'];
}elseif(in_array($_GET['direction'], $direction_list) && strcmp($_GET['carrier_name'],$all_carrier) === 0 && !empty($_GET['q1']) && !empty($_GET['q2']) && $_GET['group'] === $group_list[1] ){
    $query = "SELECT direction, substr(start_date,1,7) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE direction like :direction AND substr(start_date,1,7) BETWEEN :start_month AND :end_month";
    $queryCount = "SELECT COUNT(*) count FROM (SELECT direction, substr(start_date,1,7) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE direction like :direction AND substr(start_date,1,7) BETWEEN :start_month AND :end_month";
    $params['direction'] = $_GET['direction'];
    $params['start_month'] = substr($_GET['q1'],0,7);
    $params['end_month'] = substr($_GET['q2'],0,7);
}elseif(in_array($_GET['direction'], $direction_list) && strcmp($_GET['carrier_name'],$all_carrier) === 0 && !empty($_GET['q1']) && !empty($_GET['q2']) && $_GET['group'] === $group_list[2] ){
    $query = "SELECT direction, substr(start_date,1,4) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE direction like :direction AND substr(start_date,1,4) BETWEEN :start_year AND :end_year";
    $queryCount = "SELECT COUNT(*) count FROM (SELECT direction, substr(start_date,1,4) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE direction like :direction AND substr(start_date,1,4) BETWEEN :start_year AND :end_year";
    $params['direction'] = $_GET['direction'];
    $params['start_year'] = substr($_GET['q1'],0,4);
    $params['end_year'] = substr($_GET['q2'],0,4);
}

#6 View by direction, carrier, group
if(in_array($_GET['direction'], $direction_list) && strcmp($_GET['carrier_name'],$all_carrier) !== 0 && empty($_GET['q1']) && empty($_GET['q2']) && $_GET['group'] === $group_list[0] ){
    $query = "SELECT direction, start_date date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE direction like :direction AND carrier like :carrier AND start_date = current_date - interval 1 day";
    $queryCount = "SELECT COUNT(*) count FROM (SELECT direction, start_date date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE direction like :direction AND carrier like :carrier AND start_date = current_date - interval 1 day";
    $params['direction'] = $_GET['direction'];
    $params['carrier'] = $_GET['carrier_name'];
}elseif(in_array($_GET['direction'], $direction_list) && strcmp($_GET['carrier_name'],$all_carrier) !== 0 && empty($_GET['q1']) && empty($_GET['q2']) && $_GET['group'] === $group_list[1] ){
    $query = "SELECT direction, substr(start_date,1,7) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE direction like :direction AND carrier like :carrier AND substr(start_date,1,7) = substr(current_date - interval 1 day,1,7)";
    $queryCount = "SELECT COUNT(*) count FROM (SELECT direction, substr(start_date,1,7) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE direction like :direction AND carrier like :carrier AND substr(start_date,1,7) = substr(current_date - interval 1 day,1,7)";
    $params['direction'] = $_GET['direction'];
    $params['carrier'] = $_GET['carrier_name'];
}elseif(in_array($_GET['direction'], $direction_list) && strcmp($_GET['carrier_name'],$all_carrier) !== 0 && empty($_GET['q1']) && empty($_GET['q2']) && $_GET['group'] === $group_list[2] ){
    $query = "SELECT direction, substr(start_date,1,4) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE direction like :direction AND carrier like :carrier AND substr(start_date,1,4) = substr(current_date - interval 1 day,1,4)";
    $queryCount = "SELECT COUNT(*) count FROM (SELECT direction, substr(start_date,1,4) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE direction like :direction AND carrier like :carrier AND substr(start_date,1,4) = substr(current_date - interval 1 day,1,4)";
    $params['direction'] = $_GET['direction'];
    $params['carrier'] = $_GET['carrier_name'];
}

#7 View by direction, carrier, period, group
if(in_array($_GET['direction'], $direction_list) && strcmp($_GET['carrier_name'],$all_carrier) !== 0 && !empty($_GET['q1']) && !empty($_GET['q2']) && $_GET['group'] === $group_list[0] ){
    $query = "SELECT direction, start_date date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE direction like :direction AND carrier LIKE :carrier AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount = "SELECT COUNT(*) count FROM (SELECT direction, start_date date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE direction like :direction AND carrier LIKE :carrier AND start_date BETWEEN :begin_date AND :end_date";
    $params['direction'] = $_GET['direction'];
    $params['carrier'] = $_GET['carrier_name'];
    $params['begin_date'] = $_GET['q1'];
    $params['end_date'] = $_GET['q2'];
}elseif(in_array($_GET['direction'], $direction_list) && strcmp($_GET['carrier_name'],$all_carrier) !== 0 && !empty($_GET['q1']) && !empty($_GET['q2']) && $_GET['group'] === $group_list[1] ){
    $query = "SELECT direction, substr(start_date,1,7) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE direction like :direction AND carrier LIKE :carrier AND substr(start_date,1,7) BETWEEN :start_month AND :end_month";
    $queryCount = "SELECT COUNT(*) count FROM (SELECT direction, substr(start_date,1,7) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE direction like :direction AND carrier LIKE :carrier AND substr(start_date,1,7) BETWEEN :start_month AND :end_month";
    $params['direction'] = $_GET['direction'];
    $params['carrier'] = $_GET['carrier_name'];
    $params['start_month'] = substr($_GET['q1'],0,7);
    $params['end_month'] = substr($_GET['q2'],0,7);
}elseif(in_array($_GET['direction'], $direction_list) && strcmp($_GET['carrier_name'],$all_carrier) !== 0 && !empty($_GET['q1']) && !empty($_GET['q2']) && $_GET['group'] === $group_list[2] ){
    $query = "SELECT direction, substr(start_date,1,4) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE direction like :direction AND carrier LIKE :carrier AND substr(start_date,1,4) BETWEEN :start_year AND :end_year";
    $queryCount = "SELECT COUNT(*) count FROM (SELECT direction, substr(start_date,1,4) date_range, carrier, sum(attempt) attempt, sum(completed) completed, sum(answered) answered FROM carrier_traffic WHERE direction like :direction AND carrier LIKE :carrier AND substr(start_date,1,4) BETWEEN :start_year AND :end_year";
    $params['direction'] = $_GET['direction'];
    $params['carrier'] = $_GET['carrier_name'];
    $params['start_year'] = substr($_GET['q1'],0,4);
    $params['end_year'] = substr($_GET['q2'],0,4);
}

// organisation
$query .= " GROUP BY direction, date_range, carrier";
$queryCount .= " GROUP BY direction, date_range, carrier)t"; 

if(!empty($_GET['sort']) && in_array($_GET['sort'], $sortable)){
    $sens = $_GET['sens'] ?? 'asc';
    if(!in_array($sens, ['asc', 'desc'])){
        $sens = 'asc';
    }
    $query .= " ORDER BY " . $_GET['sort'] . " $sens";
}else if(empty($_GET['sort'])){
    $query .= " ORDER BY direction, date_range desc, carrier";
}

//Pagination
$page = (int)($_GET['p'] ?? 1);
$offset = ($page-1) * LIGNES_PAR_PAGE;

$query .= " LIMIT " . LIGNES_PAR_PAGE . " OFFSET $offset";

$statement = $bdd->prepare($query);
$statement->execute($params);
$destinations = $statement->fetchAll();
/*value="<?= htmlentities($_GET['q'] ?? null)?>"*/

$statement = $bdd->prepare($queryCount);
$statement->execute($params);
$count = (int)$statement->fetch()['count'];
$pages = ceil($count / LIGNES_PAR_PAGE);


class URLHelper {
    public static function withParam(array $data, string $param, $value): string{
        return http_build_query(array_merge($data, [$param => $value]));
    }
    public static function withParams(array $data, array $params): string {
        return http_build_query(array_merge($data, $params));
    }
} 


class TableHelper{
    const SORT_KEY = 'sort';
    const SENS_KEY = 'sens';

    public static function sort(string $sortKey, string $label, array $data): string{
        $sort = $data[self::SORT_KEY] ?? null;
        $sens = $data[self::SENS_KEY] ?? null;
        $icon = "";
        if($sort === $sortKey){
            $icon = $sens === 'asc' ? "^" : 'v';
        }
        $url = URLHelper::withParams($data, [
            'sort' => $sortKey, 
            'sens' => $sens === 'asc' && $sort === $sortKey ? 'desc' : 'asc'
        ]);
        return <<<HTML
        <a href="?$url">$label $icon</a>
HTML;
    }
}

$_SESSION['direction'] = $_GET['direction'];
$_SESSION['carrier_name'] = $_GET['carrier_name'];
$_SESSION['q1'] = $_GET['q1'];
$_SESSION['q2'] = $_GET['q2'];

?>

<?php
require("header2.php");
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2 class="h2"> Carrier Overview </h2>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="float-right">
                <a href="export_carrier_overview.php" class="btn btn-success"><i class="dwn"></i> Export to CSV</a>
            </div>
        </div>
    </div>            
<br/>

<table> 
<tr>
                            <td> <div class="form-group">
                                <select class="form-select form-select" name="direction">

                                        <option value="Both Directions" <?php if(!empty($_GET['direction'])) {
                                             if($_GET['direction'] === 'Both Directions'){
                                                echo "selected";
                                            }
                                        }?> >Both Directions</option>
                                
                                        <option value="Incoming" <?php if(!empty($_GET['direction'])) {
                                            if($_GET['direction'] === 'Incoming'){
                                                echo "selected";
                                            }
                                        } ?> >Incoming</option>

                                        <option value="Outgoing" <?php if(!empty($_GET['direction'])) {
                                            if($_GET['direction'] === 'Outgoing'){
                                                echo "selected";
                                            }
                                        } ?> >Outgoing</option>

                                       
                                </select>		
                            </div></td>
                        <td> 
                            <div class="form-group">
                                <select class="form-select form-select" name="carrier_name">
                                    <option selected><?= $_GET['carrier_name'] ?? "All Carriers" ?></option>		                
                                    <?php foreach($carriers as $carrier): ?>
                                        <option value="<?= strtolower($carrier['carrier_name'])?>">
                                            <?= $carrier['carrier_name']?>
                                        </option>		
			                        <?php endforeach ?>	
                                </select>
                            </div>
                        </td>            


                       <td> <div class="form-group">
                            <input type="date" class="form-control" name="q1" placeholder="Start Year-Month-Day" value="<?= htmlentities($_GET['q1'] ?? null)?>">		
                        </div></td>
                        <td><div class="form-group">
                            <input type="date" class="form-control" name="q2" placeholder="End Year-Month-Day" value="<?= htmlentities($_GET['q2'] ?? null)?>">		
                        </div></td>


                        <td> <div class="form-group">
                            <select class="form-select form-select" name="group">
					                    <option value="Day" <?php if(!empty($_GET['group'])) {
                                            if($_GET['group'] == 'Day'){
                                                echo "selected";
                                            }
                                        } ?> >Day</option>

                                        <option value="Month" <?php if(!empty($_GET['group'])) {
                                            if($_GET['group'] == 'Month'){
                                                echo "selected";
                                            }
                                        } ?> >Month</option>

                                        <option value="Year" <?php if(!empty($_GET['group'])) {
                                            if($_GET['group'] == 'Year'){
                                                echo "selected";
                                            }
                                        } ?> >Year</option>                                                                                                  
                                </select>		
                        </div></td> 
<!--
                        <td> <div class="form-group">
                            <select class="form-select form-select" name="p">
                                        <option selected><//?= $_GET['p'] ?? "Day" ?></option>
					                    <option value="Day">Day</option>
                                    	<option value="Month">Month</option>
                                        <option value="Year">Year</option>
                            </select>		
                        </div></td>
                                    -->


			<td><div class="generer">
				 <button class="btn btn-primary">Generate</button>
			</div></td>
</tr>
</table>
		    </form>
		<div class="tableau" >
		    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?= TableHelper::sort('direction', 'direction', $_GET) ?></th>
                                <th><?= TableHelper::sort('date_range', 'date_range', $_GET) ?></th>
                                <th><?= TableHelper::sort('carrier', 'carrier', $_GET) ?></th>	
                                <th><?= TableHelper::sort('attempt', 'attempt', $_GET) ?></th>      
                                <th><?= TableHelper::sort('completed', 'completed', $_GET) ?></th> 
                                <th><?= TableHelper::sort('answered', 'answered', $_GET) ?></th>  
                                
                            </tr>			
			</thead>

                        <tbody>  
                            <?php foreach($destinations as $dest): ?>
                                <tr>
                                    <td><?= $dest['direction']?></td>
                                    <td><?= $dest['date_range']?></td>
                                    <td><?= $dest['carrier']?></td>
                                    <td><?= $dest['attempt']?></td>	
                                    <td><?= $dest['completed']?></td>       
                                    <td><?= $dest['answered']?></td>	      	
                                </tr>
			    <?php endforeach ?>	
			</tbody>
		     </table>
		</div>

                    <?php if ($pages > 1 && $page > 1): ?>
                        <a href="?<?= URLHelper::withParam($_GET, "p", $page - 1) ?>" class="btn btn-primary">Preview page</a>
                    <?php endif ?>

                    <?php if ($pages > 1 && $page < $pages): ?>
                        <a href="?<?= URLHelper::withParam($_GET, "p", $page + 1) ?>"  class="btn btn-primary">Next page</a>
                    <?php endif ?>

                </main>
            </div>
        </div>
        <div class="row sortable-card pl-5 pr-5">
              <div class="col-12 col-md-6 col-lg-4">
                <div class="card card-primary">
                  <div class="card-header">
                    <h4>Card Header</h4>
                  </div>
                  <div class="card-body">
                    <p>Card <code>.card-primary</code></p>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg-4">
                <div class="card card-secondary">
                  <div class="card-header">
                    <h4>Card Header</h4>
                  </div>
                  <div class="card-body">
                    <p>Card <code>.card-secondary</code></p>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg-4">
                <div class="card card-danger">
                  <div class="card-header">
                    <h4>Card Header</h4>
                  </div>
                  <div class="card-body">
                    <p>Card <code>.card-danger</code></p>
                  </div>
                </div>
              </div>
        </div>
        <div class="row sortable-card pl-2 pr-2 mt-3 mb-0">
              <div class="col-12 col-md-6 col-lg-4">
                <div class="card card-warning">
                  <div class="card-header">
                    <h4>Card Header</h4>
                  </div>
                  <div class="card-body">
                    <p>Card <code>.card-warning</code></p>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg-4">
                <div class="card card-warning">
                  <div class="card-header">
                    <h4>Card Header</h4>
                  </div>
                  <div class="card-body">
                    <p>Card <code>.card-warning</code></p>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg-4">
                <div class="card card-warning">
                  <div class="card-header">
                    <h4>Card Header</h4>
                  </div>
                  <div class="card-body">
                    <p>Card <code>.card-warning</code></p>
                  </div>
                </div>
              </div>
            </div>

        <script src="/docs/5.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

        <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="dashboard.js"></script>
    
    </body>

</html>
