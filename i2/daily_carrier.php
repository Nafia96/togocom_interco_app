<?php

define("LIGNES_PAR_PAGE", 10);

session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "SELECT start_date, carrier, sum(duration) duration_min, round(sum(revenue_cfa)) revenue_cfa FROM `revenu`";
$queryCount = "SELECT COUNT(a.start_date) count FROM (SELECT start_date, carrier, sum(revenue_cfa) FROM `revenu`";
$params = [];
$sortable = ["start_date", "carrier", "revenue_cfa"];

// dates controle
if((empty($_GET['q1']) && !empty($_GET['q2'])) || (!empty($_GET['q1']) && empty($_GET['q2']))){
    $date_error = "Paramètrage de dates invalide !";
}

// #0 Affichage sans parametre
if(empty($_GET['q']) &&  empty($_GET['q1']) && empty($_GET['q2'])){
	$query .= " WHERE start_date = current_date-1";
    $queryCount .= " WHERE start_date = current_date-1";
}

// #1 Recherche par start_date, carrier
if(!empty($_GET['q']) &&  !empty($_GET['q1']) && !empty($_GET['q2'])){
	$query .= " WHERE start_date BETWEEN :begin_date AND :end_date AND carrier LIKE :carrier";
    $queryCount .= " WHERE start_date BETWEEN :begin_date AND :end_date AND carrier LIKE :carrier";
	$params['carrier'] = '%' . $_GET['q'] . '%';
    $params['begin_date'] = $_GET['q1'];
    $params['end_date'] = $_GET['q2'];
}

// #2 Recherche par start_date
if(empty($_GET['q']) &&  !empty($_GET['q1']) && !empty($_GET['q2'])){
	$query .= " WHERE start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE start_date BETWEEN :begin_date AND :end_date";
    $params['begin_date'] = $_GET['q1'];
    $params['end_date'] = $_GET['q2'];
}

// #3 Recherche par carrier
if(!empty($_GET['q']) &&  empty($_GET['q1']) && empty($_GET['q2'])){
	$query .= " WHERE carrier LIKE :carrier AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
    $queryCount .= " WHERE carrier LIKE :carrier AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
	$params['carrier'] = '%' . $_GET['q'] . '%';
}

$query .= " GROUP BY start_date, carrier";
$queryCount .= " GROUP BY start_date, carrier)a";

// organisation

if(!empty($_GET['sort']) && in_array($_GET['sort'], $sortable)){
    $sens = $_GET['sens'] ?? 'asc';
    if(!in_array($sens, ['asc', 'desc'])){
        $sens = 'asc';
    }
    $query .= " ORDER BY " . $_GET['sort'] . " $sens";
}else if(empty($_GET['sort'])){
    $query .= " ORDER BY start_date desc, carrier";
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

$_SESSION['q'] = $_GET['q'];
$_SESSION['q1'] = $_GET['q1'];
$_SESSION['q2'] = $_GET['q2'];

?>

<?php
    require("bill_header.php");
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2 class="h2"> Daily Carrier </h2>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="float-right">
                <a href="export_daily_carrier.php" class="btn btn-success"><i class="dwn"></i> Export to CSV</a>
            </div>
        </div>
    </div>              
<br/>

<table> 
<tr>
                        <td> <div class="form-group">
                            <input type="text" class="form-control" name="q" placeholder="Carrier Name" value="<?= htmlentities($_GET['q'] ?? null)?>">		
                        </div></td> 

                        <td> <div class="form-group">
                            <input type="date" class="form-control" name="q1" placeholder="YYYY-MM-DD" value="<?= htmlentities($_GET['q1'] ?? null)?>">		
                        </div></td> 
                        <td> <div class="form-group">
                            <input type="date" class="form-control" name="q2" placeholder="YYYY-MM-DD" value="<?= htmlentities($_GET['q2'] ?? null)?>">		
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
                                <th><?= TableHelper::sort('start_date', 'start_date', $_GET) ?></th>
                                <th><?= TableHelper::sort('carrier', 'carrier', $_GET) ?></th>
                                <th><?= TableHelper::sort('duration_min', 'duration_min', $_GET) ?></th>
                                <th><?= TableHelper::sort('revenue_cfa', 'revenue_cfa', $_GET) ?></th>
                                
                            </tr>		
			</thead>

                        <tbody>  
                            <?php foreach($destinations as $dest): ?>
                                <tr>
                                    <td><?= $dest['start_date']?></td>
                                    <td><?= $dest['carrier']?></td>
                                    <td><?= $dest['duration_min']?></td>
                                    <td><?= $dest['revenue_cfa']?></td>					
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
