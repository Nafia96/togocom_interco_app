<?php

define("LIGNES_PAR_PAGE", 10);

session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "SELECT * FROM monthly_report";
$queryCount = "SELECT COUNT(carrier) as count FROM monthly_report";
$params = [];
$sortable = ["direction", "start_month", "carrier", "answered","duration_min"];

//Recherche par direction, date, carrier
if(!empty($_GET['q1']) and !empty($_GET['q2']) and !empty($_GET['q3'])){
	$query .= " WHERE direction LIKE :direction AND start_month LIKE :start_month AND carrier LIKE :carrier";
    $queryCount .= " WHERE direction LIKE :direction AND start_month LIKE :start_month AND carrier LIKE :carrier";
    $params['direction'] = '%' . $_GET['q1'] . '%';
	$params['start_month'] = '%' . $_GET['q2'] . '%';
    $params['carrier'] = '%' . $_GET['q3'] . '%'; 
}

//Recherche par direction
if(!empty($_GET['q1']) and empty($_GET['q2']) and empty($_GET['q3'])){
	$query .= " WHERE direction LIKE :direction";
    $queryCount .= " WHERE direction LIKE :direction";
	$params['direction'] = '%' . $_GET['q1'] . '%';
}

//Recherche par date
if(empty($_GET['q1']) and !empty($_GET['q2']) and empty($_GET['q3'])){
	$query .= " WHERE start_month LIKE :start_month";
    $queryCount .= " WHERE start_month LIKE :start_month";
	$params['start_month'] = '%' . $_GET['q2'] . '%';
}

//Recherche par carrier
if(empty($_GET['q1']) and empty($_GET['q2']) and !empty($_GET['q3'])){
    $query .= " WHERE carrier LIKE :carrier";
    $queryCount .= " WHERE carrier LIKE :carrier";
    $params['carrier'] = '%' . $_GET['q3'] . '%';
}

//Recherche par direction, date
if(!empty($_GET['q1']) and !empty($_GET['q2']) and empty($_GET['q3'])){
	$query .= " WHERE direction LIKE :direction AND start_month LIKE :start_month";
    $queryCount .= " WHERE direction LIKE :direction AND start_month LIKE :start_month";
	$params['direction'] = '%' . $_GET['q1'] . '%';
    $params['start_month'] = '%' . $_GET['q2'] . '%';
}

//Recherche par direction, carrier
if(!empty($_GET['q1']) and empty($_GET['q2']) and !empty($_GET['q3'])){
	$query .= " WHERE direction LIKE :direction AND carrier LIKE :carrier";
    $queryCount .= " WHERE direction LIKE :direction AND carrier LIKE :carrier";
	$params['direction'] = '%' . $_GET['q1'] . '%';
    $params['carrier'] = '%' . $_GET['q3'] . '%';
}

//Recherche par date, carrier
if(empty($_GET['q1']) and !empty($_GET['q2']) and !empty($_GET['q3'])){
	$query .= " WHERE start_month LIKE :start_month AND carrier LIKE :carrier";
    $queryCount .= " WHERE start_month LIKE :start_month AND carrier LIKE :carrier";
	$params['start_month'] = '%' . $_GET['q2'] . '%';
    $params['carrier'] = '%' . $_GET['q3'] . '%';
}

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

?>
<?php
    require("bill_header.php");
?>

<h2>Monthly Overview</h2>
<br/>

<table> 
<tr>
                        <td> <div class="form-group">
                            <input type="text" class="form-control" name="q1" placeholder="Recherche par direction" value="<?= htmlentities($_GET['q1'] ?? null)?>">		
                        </div></td>
                       <td> <div class="form-group">
                            <input type="text" class="form-control" name="q2" placeholder="Recherche par mois" value="<?= htmlentities($_GET['q2'] ?? null)?>">		
                        </div></td>
                        <td><div class="form-group">
                            <input type="text" class="form-control" name="q3" placeholder="Recherche par carrier" value="<?= htmlentities($_GET['q3'] ?? null)?>">		
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
                                <th><?= TableHelper::sort('start_month', 'start_month', $_GET) ?></th>
                                <th><?= TableHelper::sort('carrier', 'carrier', $_GET) ?></th>	
                                <th><?= TableHelper::sort('answered', 'nb_calls', $_GET) ?></th>                       
                                <th><?= TableHelper::sort('duration_min', 'duration_min', $_GET) ?></th>
                            </tr>			
			</thead>

                        <tbody>  
                            <?php foreach($destinations as $dest): ?>
                                <tr>
                                    <td><?= $dest['direction']?></td>
                                    <td><?= $dest['start_month']?></td>
                                    <td><?= $dest['carrier']?></td>
                                    <td><?= $dest['answered']?></td>		
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
