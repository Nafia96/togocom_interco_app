<?php

define("LIGNES_PAR_PAGE", 10);

session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');
if(!$_SESSION['pass']){
	header('Location: auth.php');
}

//$carrier_set = $_GET['carrier'];
$query = "SELECT direction, carrier, sum(answered) calls, sum(duration_min) duration_min FROM carrier_traffic";
$queryCount = "SELECT count(a.direction) count from (select direction, carrier, sum(answered) FROM carrier_traffic";
$params = [];
$sortable = ["direction", "carrier", "calls", "duration"];
$direction_list = ["Incoming", "Outgoing"];
//$carrier_list = ["BTS", "BICS"];
//$queryCarrier = "SELECT DISTINCT carrier_name FROM carrier_tab ORDER BY carrier_name";
//$queryCarrier_set = "SELECT DISTINCT carrier_name FROM carrier_tab WHERE carrier_name=:carrier_name";

// #0 Affichage sans parametre
if(!in_array($_GET['direction'], $direction_list) && empty($_GET['q']) && empty($_GET['q1']) && empty($_GET['q2'])){
	$query .= " WHERE substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
    $queryCount .= " WHERE substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
}

// #1 Recherche par direction, dates, carrier
if(in_array($_GET['direction'], $direction_list) && !empty($_GET['q']) && !empty($_GET['q1']) && !empty($_GET['q2'])){
	$query .= " WHERE direction LIKE :direction AND carrier LIKE :carrier AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE direction LIKE :direction AND carrier LIKE :carrier AND start_date BETWEEN :begin_date AND :end_date";
    $params['direction'] = '%' . $_GET['direction'] . '%';
    $params['carrier'] = '%' . $_GET['q'] . '%';
    $params['begin_date'] = $_GET['q1'];
    $params['end_date'] = $_GET['q2'];
}

// #2 Recherche par direction
if(in_array($_GET['direction'], $direction_list) && empty($_GET['q']) && empty($_GET['q1']) && empty($_GET['q2'])){
	$query .= " WHERE direction LIKE :direction AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
    $queryCount .= " WHERE direction LIKE :direction AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
	$params['direction'] = '%' . $_GET['direction'] . '%';
}

// #3 Recherche par start_date
if(!in_array($_GET['direction'], $direction_list) && empty($_GET['q']) && !empty($_GET['q1']) && !empty($_GET['q2'])){
	$query .= " WHERE start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE start_date BETWEEN :begin_date AND :end_date";
    $params['begin_date'] = $_GET['q1'];
    $params['end_date'] = $_GET['q2'];
}

// #4 Recherche par carrier
if(!in_array($_GET['direction'], $direction_list) && !empty($_GET['q']) && empty($_GET['q1']) && empty($_GET['q2'])){
  $query .= " WHERE carrier LIKE :carrier AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2)) ";
    $queryCount .= " WHERE carrier LIKE :carrier AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
    $params['carrier'] = '%' . $_GET['q'] . '%';
}

// #5 Recherche par direction, date
if(in_array($_GET['direction'], $direction_list)  && empty($_GET['q']) && !empty($_GET['q1']) && !empty($_GET['q2'])){
	$query .= " WHERE direction LIKE :direction AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE direction LIKE :direction AND start_date BETWEEN :begin_date AND :end_date";
	$params['direction'] = '%' . $_GET['direction'] . '%';
    $params['begin_date'] = $_GET['q1'];
    $params['end_date'] = $_GET['q2'];
}

// #6 Recherche par direction, carrier
if(in_array($_GET['direction'], $direction_list) && !empty($_GET['q']) && empty($_GET['q1']) && empty($_GET['q2'])){
	$query .= " WHERE direction LIKE :direction AND carrier LIKE :carrier AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
    $queryCount .= " WHERE direction LIKE :direction AND carrier LIKE :carrier AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
	$params['direction'] = '%' . $_GET['direction'] . '%';
    $params['carrier'] = '%' . $_GET['q'] . '%';
}

// #7 Recherche par date, carrier
if(!in_array($_GET['direction'], $direction_list) && !empty($_GET['q']) && !empty($_GET['q1']) && !empty($_GET['q2'])){
	$query .= " WHERE start_date BETWEEN :begin_date AND :end_date AND carrier LIKE :carrier";
    $queryCount .= " WHERE start_date BETWEEN :begin_date AND :end_date AND carrier LIKE :carrier";
    $params['begin_date'] = $_GET['q1'];
    $params['end_date'] = $_GET['q2'];
    $params['carrier'] = '%' . $_GET['q'] . '%';
}

    $query .= " group by direction, carrier";
    $queryCount .= " group by direction, carrier)a";

// organisation
if(!empty($_GET['sort']) && in_array($_GET['sort'], $sortable)){
    $sens = $_GET['sens'] ?? 'asc';
    if(!in_array($sens, ['asc', 'desc'])){
        $sens = 'asc';
    }
    $query .= " ORDER BY " . $_GET['sort'] . " $sens";
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

//$statement = $bdd->query($queryCarrier);
//$carriers = $statement->fetchAll();

//$count2 = $bdd->prepare("SELECT DISTINCT carrier_name FROM carrier_tab WHERE carrier_name=:carrier_name");
//$count2->bindParam(":carrier_name", $carrier_set);
//$count2->execute();
//$nb_results=$count2->rowCount();

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

$_SESSION['q'] = $_GET['q'];
$_SESSION['q1'] = $_GET['q1'];
$_SESSION['q2'] = $_GET['q2'];
$_SESSION['direction'] = $_GET['direction'];s

?>

<?php
    require("bill_header.php");
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2 class="h2"> Carrier Grouping </h2>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="float-right">
                <a href="export_carrier_grouping.php" class="btn btn-success"><i class="dwn"></i> Export to CSV</a>
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
                            <input type="text" class="form-control" name="q" placeholder="Carrier" value="<?= htmlentities($_GET['q'] ?? null)?>">		
                        </div></td>

                        <!--<td> <div class="form-group">
                            <select class="form-select form-select" name="carrier_name">
					                    <option selected>-- Carrier --</option>				                
                                        </?php foreach($carriers as $carrier): ?>
                                            <option value="</?= $carrier['carrier_name']?>">
                                                </?= $carrier['carrier_name']?>
                                            </option>		
			                            </?php endforeach ?>	
                            </select>
                        </div></td>--> 

                       <td> <div class="form-group">
                            <input type="date" class="form-control" name="q1" placeholder="Begin_date" value="<?= htmlentities($_GET['q1'] ?? null)?>">		
                        </div></td>
                        <td><div class="form-group">
                            <input type="date" class="form-control" name="q2" placeholder="End_date" value="<?= htmlentities($_GET['q2'] ?? null)?>">		
                        </div></td>                      
			<td>
                <div class="generer">
				    <button class="btn btn-primary">Generate</button>
			    </div>
            </td>
</tr>
</table>
		    </form>
		<div class="tableau" >
		    <table class="table table-striped">
                        <thead>
                        <tr>
                                <th><?= TableHelper::sort('direction', 'direction', $_GET) ?></th>
                                <th><?= TableHelper::sort('carrier', 'carrier', $_GET) ?></th>
                                <th><?= TableHelper::sort('calls', 'calls', $_GET) ?></th>
                                <th><?= TableHelper::sort('duration_min', 'duration_min', $_GET) ?></th>
                            </tr>		
			</thead>

                        <tbody>  
                            <?php foreach($destinations as $dest): ?>
                                <tr>
                                    <td><?= $dest['direction']?></td>
                                    <td><?= $dest['carrier']?></td>
                                    <td><?= $dest['calls']?></td>
                                    <td><?= $dest['duration_min']?></td>						
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
