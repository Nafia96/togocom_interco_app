<?php

define("LIGNES_PAR_PAGE", 10);

session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "SELECT direction, network, carrier, sum(answered) calls, sum(duration_min) duration FROM network_carrier";
$queryCount = "SELECT count(a.direction) count FROM (SELECT direction, network, carrier, sum(answered) FROM network_carrier";
$params = [];
$sortable = ["direction", "network", "carrier", "calls", "duration"];
$dirList = ["Incoming", "Outgoing"];

// dates controle
if((empty($_GET['q1']) && !empty($_GET['q1'])) || (!empty($_GET['q3']) && empty($_GET['q4']))){
    $date_error = "Paramètrage de dates invalide !";
}

// #0 Affichage sans parametre
if(!in_array($_GET['direction'], $dirList) && empty($_GET['q1']) && empty($_GET['q2']) && empty($_GET['q3']) && empty($_GET['q4'])){
	$query .= " WHERE substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
    $queryCount .= " WHERE substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
}

// #1 Recherche par direction, dates, network, carrier
if(in_array($_GET['direction'], $dirList) && !empty($_GET['q1']) && !empty($_GET['q2']) && !empty($_GET['q3']) && !empty($_GET['q4'])){
	$query .= " WHERE direction LIKE :direction AND start_date BETWEEN :begin_date AND :end_date AND network LIKE :network AND carrier LIKE :carrier";
    $queryCount .= " WHERE direction LIKE :direction AND start_date BETWEEN :begin_date AND :end_date AND network LIKE :network AND carrier LIKE :carrier";
    $params['direction'] = '%' . $_GET['direction'] . '%'; 
    $params['network'] = '%' . $_GET['q1'] . '%'; 
    $params['carrier'] = '%' . $_GET['q2'] . '%';
    $params['begin_date'] = $_GET['q3'];
    $params['end_date'] = $_GET['q4']; 
}

// #2 Recherche par direction
if(in_array($_GET['direction'], $dirList) && empty($_GET['q1']) && empty($_GET['q2']) && empty($_GET['q3']) && empty($_GET['q4'])){
	$query .= " WHERE direction LIKE :direction AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
    $queryCount .= " WHERE direction LIKE :direction AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
	$params['direction'] = '%' . $_GET['direction'] . '%';
}

// #3 Recherche par dates
if(!in_array($_GET['direction'], $dirList) && empty($_GET['q1']) && empty($_GET['q2']) && !empty($_GET['q3']) && !empty($_GET['q4'])){
    $query .= " WHERE start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE start_date BETWEEN :begin_date AND :end_date";
    $params['begin_date'] = $_GET['q3'];
    $params['end_date'] = $_GET['q4']; 
}

// #4 Recherche par network
if(!in_array($_GET['direction'], $dirList) && !empty($_GET['q1']) && empty($_GET['q2']) && empty($_GET['q3']) && empty($_GET['q4'])){
	$query .= " WHERE network LIKE :network AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
    $queryCount .= " WHERE network LIKE :network AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
	$params['network'] = '%' . $_GET['q1'] . '%';
}

// #5 Recherche par carrier
if(!in_array($_GET['direction'], $dirList) && empty($_GET['q1']) && !empty($_GET['q2']) && empty($_GET['q3']) && empty($_GET['q4'])){
	$query .= " WHERE carrier LIKE :carrier AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
    $queryCount .= " WHERE carrier LIKE :carrier AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
	$params['carrier'] = '%' . $_GET['q2'] . '%';
}

// #6 Recherche par direction, start_date
if(in_array($_GET['direction'], $dirList) && empty($_GET['q1']) && empty($_GET['q2']) && !empty($_GET['q3']) && !empty($_GET['q4'])){
	$query .= " WHERE direction LIKE :direction AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE direction LIKE :direction AND start_date BETWEEN :begin_date AND :end_date";
	$params['direction'] = '%' . $_GET['direction'] . '%';
    $params['begin_date'] = $_GET['q3'];
    $params['end_date'] = $_GET['q4']; 
}

// #7 Recherche par direction, network
if(in_array($_GET['direction'], $dirList) && !empty($_GET['q1']) && empty($_GET['q2']) && empty($_GET['q3']) && empty($_GET['q4'])){
	$query .= " WHERE direction LIKE :direction AND network LIKE :network AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
    $queryCount .= " WHERE direction LIKE :direction AND network LIKE :network AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
	$params['direction'] = '%' . $_GET['direction'] . '%';
    $params['network'] = '%' . $_GET['q1'] . '%';
}

// #8 Recherche par direction, carrier
if(in_array($_GET['direction'], $dirList) && empty($_GET['q1']) && !empty($_GET['q2']) && empty($_GET['q3']) && empty($_GET['q4'])){
	$query .= " WHERE direction LIKE :direction AND carrier LIKE :carrier AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
    $queryCount .= " WHERE direction LIKE :direction AND carrier LIKE :carrier AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
	$params['direction'] = '%' . $_GET['direction'] . '%';
    $params['carrier'] = '%' . $_GET['q2'] . '%';
}

// #9 Recherche par start_date, network
if(!in_array($_GET['direction'], $dirList) && !empty($_GET['q1']) && empty($_GET['q2']) && !empty($_GET['q3']) && !empty($_GET['q4'])){
	$query .= " WHERE network LIKE :network AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE network LIKE :network AND start_date BETWEEN :begin_date AND :end_date";
    $params['network'] = '%' . $_GET['q1'] . '%';
    $params['begin_date'] = $_GET['q3'];
    $params['end_date'] = $_GET['q4']; 
}

// #10 Recherche par start_date, carrier
if(!in_array($_GET['direction'], $dirList) && empty($_GET['q1']) && !empty($_GET['q2']) && !empty($_GET['q3']) && !empty($_GET['q4'])){
	$query .= " WHERE carrier LIKE :carrier AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE carrier LIKE :carrier AND start_date BETWEEN :begin_date AND :end_date";
	$params['carrier'] = '%' . $_GET['q2'] . '%';
    $params['begin_date'] = $_GET['q3'];
    $params['end_date'] = $_GET['q4']; 
}

// #11 Recherche par network, carrier
if(!in_array($_GET['direction'], $dirList) && !empty($_GET['q1']) && !empty($_GET['q2']) && empty($_GET['q3']) && empty($_GET['q4'])){
	$query .= " WHERE network LIKE :network AND carrier LIKE :carrier AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
    $queryCount .= " WHERE network LIKE :network AND carrier LIKE :carrier AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
	$params['network'] = '%' . $_GET['q1'] . '%';
    $params['carrier'] = '%' . $_GET['q2'] . '%';
}

// #12 Recherche par direction, start_date, network
if(in_array($_GET['direction'], $dirList) && !empty($_GET['q1']) && empty($_GET['q2']) && !empty($_GET['q3']) && !empty($_GET['q4'])){
	$query .= " WHERE direction LIKE :direction AND network LIKE :network AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE direction LIKE :direction AND network LIKE :network AND start_date BETWEEN :begin_date AND :end_date";
	$params['direction'] = '%' . $_GET['direction'] . '%';
    $params['network'] = '%' . $_GET['q1'] . '%';
    $params['begin_date'] = $_GET['q3'];
    $params['end_date'] = $_GET['q4']; 
}

// #13 Recherche par direction, start_date, carrier
if(in_array($_GET['direction'], $dirList) && empty($_GET['q1']) && !empty($_GET['q2']) && !empty($_GET['q3']) && !empty($_GET['q4'])){
	$query .= " WHERE direction LIKE :direction AND carrier LIKE :carrier AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE direction LIKE :direction AND carrier LIKE :carrier AND start_date BETWEEN :begin_date AND :end_date";
	$params['direction'] = '%' . $_GET['direction'] . '%';
    $params['carrier'] = '%' . $_GET['q2'] . '%';
    $params['begin_date'] = $_GET['q3'];
    $params['end_date'] = $_GET['q4']; 
}

// #14 Recherche par direction, network, carrier
if(in_array($_GET['direction'], $dirList) && !empty($_GET['q1']) && !empty($_GET['q2']) && empty($_GET['q3']) && empty($_GET['q4'])){
	$query .= " WHERE direction LIKE :direction AND network LIKE :network AND carrier LIKE :carrier AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
    $queryCount .= " WHERE direction LIKE :direction AND network LIKE :network AND carrier LIKE :carrier AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
	$params['direction'] = '%' . $_GET['direction'] . '%';
    $params['network'] = '%' . $_GET['q1'] . '%';
    $params['carrier'] = '%' . $_GET['q2'] . '%';
}

// #15 Recherche par start_date, network, carrier
if(!in_array($_GET['direction'], $dirList) && !empty($_GET['q1']) && !empty($_GET['q2']) && !empty($_GET['q3']) && !empty($_GET['q4'])){
	$query .= " WHERE network LIKE :network AND carrier LIKE :carrier AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE network LIKE :network AND carrier LIKE :carrier AND start_date BETWEEN :begin_date AND :end_date";
    $params['network'] = '%' . $_GET['q1'] . '%';
    $params['carrier'] = '%' . $_GET['q2'] . '%';
    $params['begin_date'] = $_GET['q3'];
    $params['end_date'] = $_GET['q4']; 
}

    $query .= " group by direction, network, carrier";
    $queryCount .= " group by direction, network, carrier)a";

// organisation
if(!empty($_GET['sort']) && in_array($_GET['sort'], $sortable)){
    $sens = $_GET['sens'] ?? 'asc';
    if(!in_array($sens, ['asc', 'desc'])){
        $sens = 'asc';
    }
    $query .= " ORDER BY " . $_GET['sort'] . " $sens";
}else if(empty($_GET['sort'])){
    $query .= " ORDER BY direction, network, carrier";
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
    require("bill_header.php");
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2 class="h2"> Network Carrier Grouping </h2>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="float-right">
                <a href="export_network_carrier_grouping.php" class="btn btn-success"><i class="dwn"></i> Export to CSV</a>
            </div>
        </div>
    </div>             
<br/>

<table> 
<tr>
<td>                
                        <div class="form-group">
                            <select class="form-select form-select" name="direction">
                                        <option selected><?= $_GET['direction'] ?? "Both" ?></option>
                                        <option value="Both">Both</option>
					                    <option value="Incoming">Incoming</option>
                                    	<option value="Outgoing">Outgoing</option>
                            </select>		
                        </div></td>
                        <td> <div class="form-group">
                            <input type="text" class="form-control" name="q1" placeholder="Network" value="<?= htmlentities($_GET['q1'] ?? null)?>">		
                        </div></td>
                       <td> <div class="form-group">
                            <input type="text" class="form-control" name="q2" placeholder="Carrier" value="<?= htmlentities($_GET['q2'] ?? null)?>">		
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
                                <th><?= TableHelper::sort('network', 'network', $_GET) ?></th>
                                <th><?= TableHelper::sort('carrier', 'carrier', $_GET) ?></th>
                                <th><?= TableHelper::sort('calls', 'calls', $_GET) ?></th>
                                <th><?= TableHelper::sort('duration', 'duration', $_GET) ?></th>
                            </tr>		
			</thead>

                        <tbody>  
                            <?php foreach($destinations as $dest): ?>
                                <tr>
                                    <td><?= $dest['direction']?></td>
                                    <td><?= $dest['network']?></td>
                                    <td><?= $dest['carrier']?></td>
                                    <td><?= $dest['calls']?></td>
                                    <td><?= $dest['duration']?></td>						
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
