<?php

define("LIGNES_PAR_PAGE", 10);

session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "SELECT * FROM carrier_tab";
$queryCount = "SELECT COUNT(trunk_id) as count FROM carrier_tab";
$params = [];
$sortable = ["trunk_id", "carrier_name", "carrier_id"];
$directionList = ["Incoming", "Outgoing"];

// #0 Affichage sans parametre
if(empty($_GET['q1']) && empty($_GET['q2']) && empty($_GET['q3'])){
	$query .= " WHERE 1";
    $queryCount .= " WHERE 1";
}

// #1 Recherche par trunk_id, carrier_name, carrier_id
if(!empty($_GET['q1']) && !empty($_GET['q2']) && !empty($_GET['q3'])){
	$query .= " WHERE trunk_id LIKE :trunk_id and carrier_name LIKE :carrier_name and carrier_id like :carrier_id";
    $queryCount .= " WHERE trunk_id LIKE :trunk_id and carrier_name LIKE :carrier_name and carrier_id like :carrier_id";
    $params['trunk_id'] = $_GET['q1'] . '%';
    $params['carrier_name'] = '%' . $_GET['q2'] . '%';
    $params['carrier_id'] = $_GET['q3'] . '%';
}

// #2 Recherche par trunk_id
if(!empty($_GET['q1']) && empty($_GET['q2']) && empty($_GET['q3'])){
	$query .= " WHERE trunk_id LIKE :trunk_id";
    $queryCount .= " WHERE trunk_id LIKE :trunk_id";
    $params['trunk_id'] = $_GET['q1'] . '%';
}

// #3 Recherche par carrier_name
if(empty($_GET['q1']) && !empty($_GET['q2']) && empty($_GET['q3'])){
	$query .= " WHERE carrier_name LIKE :carrier_name";
    $queryCount .= " WHERE carrier_name LIKE :carrier_name";
    $params['carrier_name'] = '%' . $_GET['q2'] . '%';
}

// #4 Recherche par carrier_id
if(empty($_GET['q1']) && empty($_GET['q2']) && !empty($_GET['q3'])){
	$query .= " WHERE carrier_id LIKE :carrier_id";
    $queryCount .= " WHERE carrier_id LIKE :carrier_id";
    $params['carrier_id'] = $_GET['q3'] . '%';
}

// #5 Recherche par trunk_id, carrier_name
if(!empty($_GET['q1']) && !empty($_GET['q2']) && empty($_GET['q3'])){
	$query .= " WHERE trunk_id LIKE :trunk_id and carrier_name LIKE :carrier_name";
    $queryCount .= " WHERE trunk_id LIKE :trunk_id and carrier_name LIKE :carrier_name";
    $params['trunk_id'] = $_GET['q1'] . '%';
    $params['carrier_name'] = '%' . $_GET['q2'] . '%';
}

// #6 Recherche par trunk_id, carrier_id
if(!empty($_GET['q1']) && empty($_GET['q2']) && !empty($_GET['q3'])){
	$query .= " WHERE trunk_id LIKE :trunk_id and carrier_id LIKE :carrier_id";
    $queryCount .= " WHERE trunk_id LIKE :trunk_id and carrier_id LIKE :carrier_id";
    $params['trunk_id'] = $_GET['q1'] . '%';
    $params['carrier_id'] = $_GET['q3'] . '%';
}

// #7 Recherche par carrier_name, carrier_id
if(empty($_GET['q1']) && !empty($_GET['q2']) && !empty($_GET['q3'])){
	$query .= " WHERE carrier_name LIKE :carrier_name and carrier_id LIKE :carrier_id";
    $queryCount .= " WHERE carrier_name LIKE :carrier_name and carrier_id LIKE :carrier_id";
    $params['carrier_name'] = '%' . $_GET['q2'] . '%';
    $params['carrier_id'] = $_GET['q3'] . '%';
}

// organisation

if(!empty($_GET['sort']) && in_array($_GET['sort'], $sortable)){
    $sens = $_GET['sens'] ?? 'asc';
    if(!in_array($sens, ['asc', 'desc'])){
        $sens = 'asc';
    }
    $query .= " ORDER BY " . $_GET['sort'] . " $sens";
}else if(empty($_GET['sort'])){
    $query .= " ORDER BY carrier_name";
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
        <h2 class="h2"> CARRIER TABLE </h2>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="float-right">
                <a href="export_carrier_table.php" class="btn btn-success"><i class="dwn"></i> Export to CSV</a>
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
                            <input type="text" class="form-control" name="q1" placeholder="Trunk ID" value="<?= htmlentities($_GET['q1'] ?? null)?>">		
                        </div></td>
                    
                       <td> <div class="form-group">
                            <input type="text" class="form-control" name="q2" placeholder="Carrier Name" value="<?= htmlentities($_GET['q2'] ?? null)?>">		
                        </div></td>
                        <td> <div class="form-group">
                            <input type="text" class="form-control" name="q3" placeholder="Carrier ID " value="<?= htmlentities($_GET['q3'] ?? null)?>">		
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
                                <th><?= TableHelper::sort('trunk_id', 'trunk_id', $_GET) ?></th>
                                <th><?= TableHelper::sort('carrier_name', 'carrier_name', $_GET) ?></th>
                                <th><?= TableHelper::sort('carrier_id', 'carrier_id', $_GET) ?></th>                         
                            </tr>		
			</thead>

                        <tbody>  
                            <?php foreach($destinations as $dest): ?>
                                <tr>
                                    <td><?= $dest['trunk_id']?></td>
                                    <td><?= $dest['carrier_name']?></td>
                                    <td><?= $dest['carrier_id']?></td>			
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
