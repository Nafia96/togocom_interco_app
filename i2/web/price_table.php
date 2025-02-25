<?php

define("LIGNES_PAR_PAGE", 10);

session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "SELECT * FROM price";
$queryCount = "SELECT COUNT(code) as count FROM price";
$params = [];
$sortable = ["code", "carrier", "route_id", "details", "tarif_in", "tarif_out"];

// #0 Affichage sans parametre
if(empty($_GET['q1']) && empty($_GET['q2']) && empty($_GET['q3'])){
	$query .= " WHERE 1";
    $queryCount .= " WHERE 1";
}

// #1 Recherche par code, carrier, route_id
if(!empty($_GET['q1']) && !empty($_GET['q2']) && !empty($_GET['q3'])){
	$query .= " WHERE code LIKE :code and carrier LIKE :carrier and route_id like :route_id";
    $queryCount .= " WHERE code LIKE :code and carrier LIKE :carrier and route_id like :route_id";
    $params['code'] = $_GET['q1'] . '%';
    $params['carrier'] = '%' . $_GET['q2'] . '%';
    $params['route_id'] = $_GET['q3'] . '%';
}

// #2 Recherche par code
if(!empty($_GET['q1']) && empty($_GET['q2']) && empty($_GET['q3'])){
	$query .= " WHERE code LIKE :code";
    $queryCount .= " WHERE code LIKE :code";
    $params['code'] = '%' . $_GET['q1'] . '%';
}

// #3 Recherche par carrier
if(empty($_GET['q1']) && !empty($_GET['q2']) && empty($_GET['q3'])){
	$query .= " WHERE carrier LIKE :carrier";
    $queryCount .= " WHERE carrier LIKE :carrier";
    $params['carrier'] = '%' . $_GET['q2'] . '%';
}

// #4 Recherche par route_id
if(empty($_GET['q1']) && empty($_GET['q2']) && !empty($_GET['q3'])){
	$query .= " WHERE route_id LIKE :route_id";
    $queryCount .= " WHERE route_id LIKE :route_id";
    $params['route_id'] = $_GET['q3'] . '%';
}

// #5 Recherche par code, carrier
if(!empty($_GET['q1']) && !empty($_GET['q2']) && empty($_GET['q3'])){
	$query .= " WHERE code LIKE :code and carrier LIKE :carrier";
    $queryCount .= " WHERE code LIKE :code and carrier LIKE :carrier";
    $params['code'] = $_GET['q1'] . '%';
    $params['carrier'] = '%' . $_GET['q2'] . '%';
}

// #6 Recherche par code, route_id
if(!empty($_GET['q1']) && empty($_GET['q2']) && !empty($_GET['q3'])){
	$query .= " WHERE code LIKE :code and route_id LIKE :route_id";
    $queryCount .= " WHERE code LIKE :code and route_id LIKE :route_id";
    $params['code'] = $_GET['q1'] . '%';
    $params['route_id'] = $_GET['q3'] . '%';
}

// #7 Recherche par carrier, route_id
if(empty($_GET['q1']) && !empty($_GET['q2']) && !empty($_GET['q3'])){
	$query .= " WHERE carrier LIKE :carrier and route_id LIKE :route_id";
    $queryCount .= " WHERE carrier LIKE :carrier and route_id LIKE :route_id";
    $params['carrier'] = '%' . $_GET['q2'] . '%';
    $params['route_id'] = $_GET['q3'] . '%';
}
// organisation

if(!empty($_GET['sort']) && in_array($_GET['sort'], $sortable)){
    $sens = $_GET['sens'] ?? 'asc';
    if(!in_array($sens, ['asc', 'asc'])){
        $sens = 'asc';
    }
    $query .= " ORDER BY " . $_GET['sort'] . " $sens";
}else if(empty($_GET['sort'])){
    $query .= " ORDER BY carrier";
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

$_SESSION['q3'] = $_GET['q3'];
$_SESSION['q1'] = $_GET['q1'];
$_SESSION['q2'] = $_GET['q2'];

?>

<?php
    //require("menus.php");
    require("resource.php");
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2 class="h2"> PRICE TABLE </h2>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="float-right">
                <a href="export_network_table.php" class="btn btn-success"><i class="dwn"></i> Export to CSV</a>
            </div>
        </div>
    </div>             
<br/>

<?php if($date_error): ?>
    <div class="date_error">
        <?= $date_error ?>
    </div>
<?php endif ?>

<table> 
<tr>
                        <td> <div class="form-group">
                            <input type="text" class="form-control" name="q1" placeholder="PRICE CODE" value="<?= htmlentities($_GET['q1'] ?? null)?>">		
                        </div></td>
                    
                       <td> <div class="form-group">
                            <input type="text" class="form-control" name="q2" placeholder="COUNTRY CODE" value="<?= htmlentities($_GET['q2'] ?? null)?>">		
                        </div></td>
                        <td> <div class="form-group">
                            <input type="text" class="form-control" name="q3" placeholder="TRUNK ID" value="<?= htmlentities($_GET['q3'] ?? null)?>">		
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
                                <th><?= TableHelper::sort('code', 'code', $_GET) ?></th>
                                <th><?= TableHelper::sort('carrier', 'carrier', $_GET) ?></th>
                                <th><?= TableHelper::sort('trunk_id', 'trunk_id', $_GET) ?></th>   
                                <th><?= TableHelper::sort('details', 'details', $_GET) ?></th>
                                <th><?= TableHelper::sort('tarif_in', 'tarif_in', $_GET) ?></th>
                                <th><?= TableHelper::sort('tarif_out', 'tarif_out', $_GET) ?></th>                       
                            </tr>		
			            </thead>

                        <tbody>  
                            <?php foreach($destinations as $dest): ?>
                                <tr>
                                    <td><?= $dest['code']?></td>
                                    <td><?= $dest['carrier']?></td>
                                    <td><?= $dest['trunk_id']?></td>	
                                    <td><?= $dest['details']?></td>
                                    <td><?= $dest['tarif_in']?></td>
                                    <td><?= $dest['tarif_out']?></td>		
                                </tr>		
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
