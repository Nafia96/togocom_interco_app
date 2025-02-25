<?php

define("LIGNES_PAR_PAGE", 10);

session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "SELECT * FROM network_carrier_sip_error";
$queryCount = "SELECT COUNT(*) as count FROM network_carrier_sip_error";
$params = [];
$sortable = ["direction","start_date", "network_name", "carrier_name", "trunk_id","attempt", "ner","asr","acd","SIP_0","SIP_1","SIP_2","SIP_400","SIP_403","SIP_404","SIP_408","SIP_410","SIP_480","SIP_481","SIP_482","SIP_483","SIP_484","SIP_486","SIP_487","SIP_500","SIP_501","SIP_502","SIP_503","SIP_504","SIP_603","SIP_604","SIP_606"];
$direction_list = ["Incoming", "Outgoing"];

// dates controle
if((empty($_GET['q1']) && !empty($_GET['q2'])) || (!empty($_GET['q1']) && empty($_GET['q2']))){
    $date_error = "Paramètrage de dates invalide !";
}

// #0 Affichage sans parametre
if(!in_array($_GET['direction'], $direction_list) && empty($_GET['q1']) && empty($_GET['q2']) && empty($_GET['q3']) && empty($_GET['q4'])){
	$query .= " WHERE start_date = CURRENT_DATE-1";
    $queryCount .= " WHERE start_date = CURRENT_DATE-1";
}

// #1 Recherche par direction, dates, network_name, carrier_name
if(in_array($_GET['direction'], $direction_list) && !empty($_GET['q1']) && !empty($_GET['q2']) && !empty($_GET['q3']) && !empty($_GET['q4'])){
	$query .= " WHERE direction LIKE :direction AND start_date BETWEEN :begin_date AND :end_date AND network_name LIKE :network_name AND carrier_name LIKE :carrier_name";
    $queryCount .= " WHERE direction LIKE :direction AND start_date BETWEEN :begin_date AND :end_date AND network_name LIKE :network_name AND carrier_name LIKE :carrier_name";
    $params['direction'] = '%' . $_GET['direction'] . '%'; 
    $params['network_name'] = '%' . $_GET['q1'] . '%'; 
    $params['carrier_name'] = '%' . $_GET['q2'] . '%';
    $params['begin_date'] = $_GET['q3'];
    $params['end_date'] = $_GET['q4']; 
}

// #2 Recherche par direction
if(in_array($_GET['direction'], $direction_list) && empty($_GET['q1']) && empty($_GET['q2']) && empty($_GET['q3']) && empty($_GET['q4'])){
	$query .= " WHERE direction LIKE :direction AND start_date = CURRENT_DATE-1";
    $queryCount .= " WHERE direction LIKE :direction AND start_date = CURRENT_DATE-1";
	$params['direction'] = '%' . $_GET['direction'] . '%';
}

// #3 Recherche par dates
if(!in_array($_GET['direction'], $direction_list) && empty($_GET['q1']) && empty($_GET['q2']) && !empty($_GET['q3']) && !empty($_GET['q4'])){
    $query .= " WHERE start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE start_date BETWEEN :begin_date AND :end_date";
    $params['begin_date'] = $_GET['q3'];
    $params['end_date'] = $_GET['q4']; 
}

// #4 Recherche par network_name
if(!in_array($_GET['direction'], $direction_list) && !empty($_GET['q1']) && empty($_GET['q2']) && empty($_GET['q3']) && empty($_GET['q4'])){
	$query .= " WHERE network_name LIKE :network_name AND start_date = CURRENT_DATE-1";
    $queryCount .= " WHERE network_name LIKE :network_name AND start_date = CURRENT_DATE-1";
	$params['network_name'] = '%' . $_GET['q1'] . '%';
}

// #5 Recherche par carrier_name
if(!in_array($_GET['direction'], $direction_list) && empty($_GET['q1']) && !empty($_GET['q2']) && empty($_GET['q3']) && empty($_GET['q4'])){
	$query .= " WHERE carrier_name LIKE :carrier_name AND start_date = CURRENT_DATE-1";
    $queryCount .= " WHERE carrier_name LIKE :carrier_name AND start_date = CURRENT_DATE-1";
	$params['carrier_name'] = '%' . $_GET['q2'] . '%';
}

// #6 Recherche par direction, start_date
if(in_array($_GET['direction'], $direction_list) && empty($_GET['q1']) && empty($_GET['q2']) && !empty($_GET['q3']) && !empty($_GET['q4'])){
	$query .= " WHERE direction LIKE :direction AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE direction LIKE :direction AND start_date BETWEEN :begin_date AND :end_date";
	$params['direction'] = '%' . $_GET['direction'] . '%';
    $params['begin_date'] = $_GET['q3'];
    $params['end_date'] = $_GET['q4']; 
}

// #7 Recherche par direction, network_name
if(in_array($_GET['direction'], $direction_list) && !empty($_GET['q1']) && empty($_GET['q2']) && empty($_GET['q3']) && empty($_GET['q4'])){
	$query .= " WHERE direction LIKE :direction AND network_name LIKE :network_name AND start_date = CURRENT_DATE-1";
    $queryCount .= " WHERE direction LIKE :direction AND network_name LIKE :network_name AND start_date = CURRENT_DATE-1";
	$params['direction'] = '%' . $_GET['direction'] . '%';
    $params['network_name'] = '%' . $_GET['q1'] . '%';
}

// #8 Recherche par direction, carrier_name
if(in_array($_GET['direction'], $direction_list) && empty($_GET['q1']) && !empty($_GET['q2']) && empty($_GET['q3']) && empty($_GET['q4'])){
	$query .= " WHERE direction LIKE :direction AND carrier_name LIKE :carrier_name AND start_date = CURRENT_DATE-1";
    $queryCount .= " WHERE direction LIKE :direction AND carrier_name LIKE :carrier_name AND start_date = CURRENT_DATE-1";
	$params['direction'] = '%' . $_GET['direction'] . '%';
    $params['carrier_name'] = '%' . $_GET['q2'] . '%';
}

// #9 Recherche par start_date, network_name
if(!in_array($_GET['direction'], $direction_list) && !empty($_GET['q1']) && empty($_GET['q2']) && !empty($_GET['q3']) && !empty($_GET['q4'])){
	$query .= " WHERE network_name LIKE :network_name AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE network_name LIKE :network_name AND start_date BETWEEN :begin_date AND :end_date";
    $params['network_name'] = '%' . $_GET['q1'] . '%';
    $params['begin_date'] = $_GET['q3'];
    $params['end_date'] = $_GET['q4']; 
}

// #10 Recherche par start_date, carrier_name
if(!in_array($_GET['direction'], $direction_list) && empty($_GET['q1']) && !empty($_GET['q2']) && !empty($_GET['q3']) && !empty($_GET['q4'])){
	$query .= " WHERE carrier_name LIKE :carrier_name AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE carrier_name LIKE :carrier_name AND start_date BETWEEN :begin_date AND :end_date";
	$params['carrier_name'] = '%' . $_GET['q2'] . '%';
    $params['begin_date'] = $_GET['q3'];
    $params['end_date'] = $_GET['q4']; 
}

// #11 Recherche par network_name, carrier_name
if(!in_array($_GET['direction'], $direction_list) && !empty($_GET['q1']) && !empty($_GET['q2']) && empty($_GET['q3']) && empty($_GET['q4'])){
	$query .= " WHERE network_name LIKE :network_name AND carrier_name LIKE :carrier_name AND start_date = CURRENT_DATE-1";
    $queryCount .= " WHERE network_name LIKE :network_name AND carrier_name LIKE :carrier_name AND start_date = CURRENT_DATE-1";
	$params['network_name'] = '%' . $_GET['q1'] . '%';
    $params['carrier_name'] = '%' . $_GET['q2'] . '%';
}

// #12 Recherche par direction, start_date, network_name
if(in_array($_GET['direction'], $direction_list) && !empty($_GET['q1']) && empty($_GET['q2']) && !empty($_GET['q3']) && !empty($_GET['q4'])){
	$query .= " WHERE direction LIKE :direction AND network_name LIKE :network_name AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE direction LIKE :direction AND network_name LIKE :network_name AND start_date BETWEEN :begin_date AND :end_date";
	$params['direction'] = '%' . $_GET['direction'] . '%';
    $params['network_name'] = '%' . $_GET['q1'] . '%';
    $params['begin_date'] = $_GET['q3'];
    $params['end_date'] = $_GET['q4']; 
}

// #13 Recherche par direction, start_date, carrier_name
if(in_array($_GET['direction'], $direction_list) && empty($_GET['q1']) && !empty($_GET['q2']) && !empty($_GET['q3']) && !empty($_GET['q4'])){
	$query .= " WHERE direction LIKE :direction AND carrier_name LIKE :carrier_name AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE direction LIKE :direction AND carrier_name LIKE :carrier_name AND start_date BETWEEN :begin_date AND :end_date";
	$params['direction'] = '%' . $_GET['direction'] . '%';
    $params['carrier_name'] = '%' . $_GET['q2'] . '%';
    $params['begin_date'] = $_GET['q3'];
    $params['end_date'] = $_GET['q4']; 
}

// #14 Recherche par direction, network_name, carrier_name
if(in_array($_GET['direction'], $direction_list) && !empty($_GET['q1']) && !empty($_GET['q2']) && empty($_GET['q3']) && empty($_GET['q4'])){
	$query .= " WHERE direction LIKE :direction AND network_name LIKE :network_name AND carrier_name LIKE :carrier_name AND start_date = CURRENT_DATE-1";
    $queryCount .= " WHERE direction LIKE :direction AND network_name LIKE :network_name AND carrier_name LIKE :carrier_name AND start_date = CURRENT_DATE-1";
	$params['direction'] = '%' . $_GET['direction'] . '%';
    $params['network_name'] = '%' . $_GET['q1'] . '%';
    $params['carrier_name'] = '%' . $_GET['q2'] . '%';
}

// #15 Recherche par start_date, network_name, carrier_name
if(!in_array($_GET['direction'], $direction_list) && !empty($_GET['q1']) && !empty($_GET['q2']) && !empty($_GET['q3']) && !empty($_GET['q4'])){
	$query .= " WHERE network_name LIKE :network_name AND carrier_name LIKE :carrier_name AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE network_name LIKE :network_name AND carrier_name LIKE :carrier_name AND start_date BETWEEN :begin_date AND :end_date";
    $params['network_name'] = '%' . $_GET['q1'] . '%';
    $params['carrier_name'] = '%' . $_GET['q2'] . '%';
    $params['begin_date'] = $_GET['q3'];
    $params['end_date'] = $_GET['q4']; 
}

// organisation
if(!empty($_GET['sort']) && in_array($_GET['sort'], $sortable)){
    $sens = $_GET['sens'] ?? 'asc';
    if(!in_array($sens, ['asc', 'desc'])){
        $sens = 'asc';
    }
    $query .= " ORDER BY " . $_GET['sort'] . " $sens";
}else if(empty($_GET['sort'])){
    $query .= " ORDER BY direction, start_date desc, network_name, carrier_name";
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

$_SESSION['q4'] = $_GET['q4'];
$_SESSION['q3'] = $_GET['q3'];
$_SESSION['q1'] = $_GET['q1'];
$_SESSION['q2'] = $_GET['q2'];
$_SESSION['direction'] = $_GET['direction'];

?>
<?php
    require("header2.php");
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2 class="h2"> Network Carrier Trunk ID </h2>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="float-right">
                <a href="export_network_carrier_sip_error.php" class="btn btn-success"><i class="dwn"></i> Export to CSV</a>
            </div>
        </div>
    </div>

<br/>

<table> 
<tr>
                        <td> <div class="form-group">
                            <select class="form-select form-select" name="direction">
                                        <option selected><?= $_GET['direction'] ?? "Both Directions" ?></option>
                                        <option value="Both Directions">Both Directions</option>
					                    <option value="Incoming">Incoming</option>
                                    	<option value="Outgoing">Outgoing</option>
                            </select>		
                        </div></td>
                        
                       <td> <div class="form-group">
                            <input type="text" class="form-control" name="q1" placeholder="Network Name" value="<?= htmlentities($_GET['q1'] ?? null)?>">		
                        </div></td>
                        
                        <td> <div class="form-group">
                            <input type="text" class="form-control" name="q2" placeholder="Carrier Name " value="<?= htmlentities($_GET['q2'] ?? null)?>">		
                        </div></td>

                        <td><div class="form-group">
                            <input type="date" class="form-control" name="q3" placeholder="Start Year-Month-Day" value="<?= htmlentities($_GET['q3'] ?? null)?>">		
                        </div></td>
                        <td><div class="form-group">
                            <input type="date" class="form-control" name="q4" placeholder="End Year-Month-Day" value="<?= htmlentities($_GET['q4'] ?? null)?>">		
			</div></td>
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
                                <th><?= TableHelper::sort('start_date', 'start_date', $_GET) ?></th>
                                <th><?= TableHelper::sort('network_name', '___________________network_name___________________', $_GET) ?></th>
                                <th><?= TableHelper::sort('carrier_name', '__________carrier_name__________', $_GET) ?></th>
                                <th><?= TableHelper::sort('trunk_id', 'trunk_id', $_GET) ?></th>
                                <th><?= TableHelper::sort('attempt', 'attempt', $_GET) ?></th>      
                                <th><?= TableHelper::sort('ner', 'ner', $_GET) ?></th>    
                                <th><?= TableHelper::sort('asr', 'asr', $_GET) ?></th> 
                                <th><?= TableHelper::sort('acd', 'acd', $_GET) ?></th>           
                                <th><?= TableHelper::sort('SIP_0', 'SIP_0', $_GET) ?></th>	
                                <th><?= TableHelper::sort('SIP_1', 'SIP_1', $_GET) ?></th>	
                                <th><?= TableHelper::sort('SIP_2', 'SIP_2', $_GET) ?></th>	
                                <th><?= TableHelper::sort('SIP_400', 'SIP_400', $_GET) ?></th>	
                                <th><?= TableHelper::sort('SIP_403', 'SIP_403', $_GET) ?></th>	
                                <th><?= TableHelper::sort('SIP_404', 'SIP_404', $_GET) ?></th>	
                                <th><?= TableHelper::sort('SIP_408', 'SIP_408', $_GET) ?></th>	
                                <th><?= TableHelper::sort('SIP_410', 'SIP_410', $_GET) ?></th>	
                                <th><?= TableHelper::sort('SIP_480', 'SIP_480', $_GET) ?></th>	
                                <th><?= TableHelper::sort('SIP_481', 'SIP_481', $_GET) ?></th>	
                                <th><?= TableHelper::sort('SIP_482', 'SIP_482', $_GET) ?></th>	
                                <th><?= TableHelper::sort('SIP_483', 'SIP_483', $_GET) ?></th>
                                <th><?= TableHelper::sort('SIP_484', 'SIP_484', $_GET) ?></th>	
                                <th><?= TableHelper::sort('SIP_486', 'SIP_486', $_GET) ?></th>	
                                <th><?= TableHelper::sort('SIP_487', 'SIP_487', $_GET) ?></th>	
                                <th><?= TableHelper::sort('SIP_500', 'SIP_500', $_GET) ?></th>	
                                <th><?= TableHelper::sort('SIP_501', 'SIP_501', $_GET) ?></th>	
                                <th><?= TableHelper::sort('SIP_502', 'SIP_502', $_GET) ?></th>	
                                <th><?= TableHelper::sort('SIP_503', 'SIP_503', $_GET) ?></th>	
                                <th><?= TableHelper::sort('SIP_504', 'SIP_504', $_GET) ?></th>	
                                <th><?= TableHelper::sort('SIP_603', 'SIP_603', $_GET) ?></th>	
                                <th><?= TableHelper::sort('SIP_604', 'SIP_604', $_GET) ?></th>	
                                <th><?= TableHelper::sort('SIP_606', 'SIP_606', $_GET) ?></th>
                            </tr>			
			</thead>

                        <tbody>  
                            <?php foreach($destinations as $dest): ?>
                                <tr>
                                    <td><?= $dest['direction']?></td>
                                    <td><?= $dest['start_date']?></td>
                                    <td><?= $dest['network_name']?></td>
                                    <td><?= $dest['carrier_name']?></td>
                                    <td><?= $dest['trunk_id']?></td>
                                    <td><?= $dest['attempt']?></td>
                                    <td><?= $dest['ner']?></td>
                                    <td><?= $dest['asr']?></td>
                                    <td><?= $dest['acd']?></td>
                                    <td><?= $dest['SIP_0']?></td>
                                    <td><?= $dest['SIP_1']?></td>		
                                    <td><?= $dest['SIP_2']?></td>
                                    <td><?= $dest['SIP_400']?></td>
                                    <td><?= $dest['SIP_403']?></td>		
                                    <td><?= $dest['SIP_404']?></td>	
                                    <td><?= $dest['SIP_408']?></td>
                                    <td><?= $dest['SIP_410']?></td>
                                    <td><?= $dest['SIP_480']?></td>
                                    <td><?= $dest['SIP_481']?></td>
                                    <td><?= $dest['SIP_482']?></td>		
                                    <td><?= $dest['SIP_483']?></td>
                                    <td><?= $dest['SIP_484']?></td>
                                    <td><?= $dest['SIP_486']?></td>		
                                    <td><?= $dest['SIP_487']?></td>	
                                    <td><?= $dest['SIP_500']?></td>
                                    <td><?= $dest['SIP_501']?></td>
                                    <td><?= $dest['SIP_502']?></td>
                                    <td><?= $dest['SIP_503']?></td>
                                    <td><?= $dest['SIP_504']?></td>		
                                    <td><?= $dest['SIP_603']?></td>
                                    <td><?= $dest['SIP_604']?></td>
                                    <td><?= $dest['SIP_606']?></td>			
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

        <script src="/docs/5.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

        <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="dashboard.js"></script>
    
    </body>

</html>
