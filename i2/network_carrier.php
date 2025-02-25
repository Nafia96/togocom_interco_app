<?php

define("LIGNES_PAR_PAGE", 10);

session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "SELECT * FROM network_carrier";
$queryCount = "SELECT COUNT(network) as count FROM network_carrier";
$params = [];
$sortable = ["direction", "start_date", "network", "carrier", "attempt", "completed", "answered", "ner", "asr", "acd_sec"];
$dirList = ["Incoming", "Outgoing"];

// #0 Affichage sans parametre
if(empty($_GET['direction']) && empty($_GET['q']) && empty($_GET['q1']) && empty($_GET['q2'])){
	$query .= " WHERE start_date = CURRENT_DATE-1";
    $queryCount .= " WHERE start_date = CURRENT_DATE-1";
}

// #1 Recherche par direction, start_date, network, carrier
if(in_array($_GET['direction'], $dirList) && !empty($_GET['q']) && !empty($_GET['q1']) && !empty($_GET['q2']) ){
	$query .= " WHERE direction LIKE :direction AND start_date LIKE :start_date AND network LIKE :network AND carrier LIKE :carrier";
    $queryCount .= " WHERE direction LIKE :direction AND start_date LIKE :start_date AND network LIKE :network AND carrier LIKE :carrier";
    $params['direction'] = '%' . $_GET['direction'] . '%';
    $params['start_date'] = '%' . $_GET['q'] . '%'; 
    $params['network'] = '%' . $_GET['q1'] . '%'; 
    $params['carrier'] = '%' . $_GET['q2'] . '%'; 
}

// #2 Recherche par direction
if(in_array($_GET['direction'], $dirList) && empty($_GET['q']) && empty($_GET['q1']) && empty($_GET['q2'])){
	$query .= " WHERE direction LIKE :direction";
    $queryCount .= " WHERE direction LIKE :direction";
	$params['direction'] = '%' . $_GET['direction'] . '%';
}

// #3 Recherche par start_date
if(!in_array($_GET['direction'], $dirList) && !empty($_GET['q']) && empty($_GET['q1']) && empty($_GET['q2'])){
    $query .= " WHERE start_date LIKE :start_date";
    $queryCount .= " WHERE start_date LIKE :start_date";
    $params['start_date'] = '%' . $_GET['q'] . '%';
}

// #4 Recherche par network
if(!in_array($_GET['direction'], $dirList) && empty($_GET['q']) && !empty($_GET['q1']) && empty($_GET['q2'])){
	$query .= " WHERE network LIKE :network";
    $queryCount .= " WHERE network LIKE :network";
	$params['network'] = '%' . $_GET['q1'] . '%';
}

// #5 Recherche par carrier
if(!in_array($_GET['direction'], $dirList) && empty($_GET['q']) && empty($_GET['q1']) && !empty($_GET['q2'])){
	$query .= " WHERE carrier LIKE :carrier";
    $queryCount .= " WHERE carrier LIKE :carrier";
	$params['carrier'] = '%' . $_GET['q2'] . '%';
}

// #6 Recherche par direction, start_date
if(in_array($_GET['direction'], $dirList) && !empty($_GET['q']) && empty($_GET['q1']) && empty($_GET['q2'])){
	$query .= " WHERE direction LIKE :direction AND start_date LIKE :start_date";
    $queryCount .= " WHERE direction LIKE :direction AND start_date LIKE :start_date";
	$params['direction'] = '%' . $_GET['direction'] . '%';
    $params['start_date'] = '%' . $_GET['q'] . '%';
}

// #7 Recherche par direction, network
if(in_array($_GET['direction'], $dirList) && empty($_GET['q']) && !empty($_GET['q1']) && empty($_GET['q2'])){
	$query .= " WHERE direction LIKE :direction AND network LIKE :network";
    $queryCount .= " WHERE direction LIKE :direction AND network LIKE :network";
	$params['direction'] = '%' . $_GET['direction'] . '%';
    $params['network'] = '%' . $_GET['q1'] . '%';
}

// #8 Recherche par direction, carrier
if(in_array($_GET['direction'], $dirList) && empty($_GET['q']) && empty($_GET['q1']) && !empty($_GET['q2'])){
	$query .= " WHERE direction LIKE :direction AND carrier LIKE :carrier";
    $queryCount .= " WHERE direction LIKE :direction AND carrier LIKE :carrier";
	$params['direction'] = '%' . $_GET['direction'] . '%';
    $params['carrier'] = '%' . $_GET['q2'] . '%';
}

// #9 Recherche par start_date, network
if(!in_array($_GET['direction'], $dirList) && !empty($_GET['q']) && !empty($_GET['q1']) && empty($_GET['q2'])){
	$query .= " WHERE start_date LIKE :start_date AND network LIKE :network";
    $queryCount .= " WHERE start_date LIKE :start_date AND network LIKE :network";
	$params['start_date'] = '%' . $_GET['q'] . '%';
    $params['network'] = '%' . $_GET['q1'] . '%';
}

// #10 Recherche par start_date, carrier
if(!in_array($_GET['direction'], $dirList) && !empty($_GET['q']) && empty($_GET['q1']) && !empty($_GET['q2'])){
	$query .= " WHERE start_date LIKE :start_date AND carrier LIKE :carrier";
    $queryCount .= " WHERE start_date LIKE :start_date AND carrier LIKE :carrier";
	$params['start_date'] = '%' . $_GET['q'] . '%';
    $params['carrier'] = '%' . $_GET['q2'] . '%';
}

// #11 Recherche par network, carrier
if(!in_array($_GET['direction'], $dirList) && empty($_GET['q']) && !empty($_GET['q1']) && !empty($_GET['q2'])){
	$query .= " WHERE network LIKE :network AND carrier LIKE :carrier";
    $queryCount .= " WHERE network LIKE :network AND carrier LIKE :carrier";
	$params['network'] = '%' . $_GET['q1'] . '%';
    $params['carrier'] = '%' . $_GET['q2'] . '%';
}

// #12 Recherche par direction, start_date, network
if(in_array($_GET['direction'], $dirList) && !empty($_GET['q']) && !empty($_GET['q1']) && empty($_GET['q2'])){
	$query .= " WHERE direction LIKE :direction AND start_date LIKE :start_date AND network LIKE :network";
    $queryCount .= " WHERE direction LIKE :direction AND start_date LIKE :start_date AND network LIKE :network";
	$params['direction'] = '%' . $_GET['direction'] . '%';
    $params['start_date'] = '%' . $_GET['q'] . '%';
    $params['network'] = '%' . $_GET['q1'] . '%';
}

// #13 Recherche par direction, start_date, carrier
if(in_array($_GET['direction'], $dirList) && !empty($_GET['q']) && empty($_GET['q1']) && !empty($_GET['q2'])){
	$query .= " WHERE direction LIKE :direction AND start_date LIKE :start_date AND carrier LIKE :carrier";
    $queryCount .= " WHERE direction LIKE :direction AND start_date LIKE :start_date AND carrier LIKE :carrier";
	$params['direction'] = '%' . $_GET['direction'] . '%';
    $params['start_date'] = '%' . $_GET['q'] . '%';
    $params['carrier'] = '%' . $_GET['q2'] . '%';
}

// #14 Recherche par direction, network, carrier
if(in_array($_GET['direction'], $dirList) && empty($_GET['q']) && !empty($_GET['q1']) && !empty($_GET['q2'])){
	$query .= " WHERE direction LIKE :direction AND network LIKE :network AND carrier LIKE :carrier";
    $queryCount .= " WHERE direction LIKE :direction AND network LIKE :network AND carrier LIKE :carrier";
	$params['direction'] = '%' . $_GET['direction'] . '%';
    $params['network'] = '%' . $_GET['q1'] . '%';
    $params['carrier'] = '%' . $_GET['q2'] . '%';
}

// #15 Recherche par start_date, network, carrier
if(!in_array($_GET['direction'], $dirList) && !empty($_GET['q']) && !empty($_GET['q1']) && !empty($_GET['q2'])){
	$query .= " WHERE start_date LIKE :start_date AND network LIKE :network AND carrier LIKE :carrier";
    $queryCount .= " WHERE start_date LIKE :start_date AND network LIKE :network AND carrier LIKE :carrier";
	$params['start_date'] = '%' . $_GET['q'] . '%';
    $params['network'] = '%' . $_GET['q1'] . '%';
    $params['carrier'] = '%' . $_GET['q2'] . '%';
}

// organisation
if(!empty($_GET['sort']) && in_array($_GET['sort'], $sortable)){
    $sens = $_GET['sens'] ?? 'asc';
    if(!in_array($sens, ['asc', 'desc'])){
        $sens = 'asc';
    }
    $query .= " ORDER BY " . $_GET['sort'] . " $sens";
}else if(empty($_GET['sort'])){
    $query .= " ORDER BY start_date desc, network, carrier";
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
    require("header2.php");
?>

<h2>Network by Carrier</h2>
<br/>

<table> 
<tr>
                        <td> <div class="form-group">
                            <select class="form-select form-select" name="direction">
					                    <option selected>-- Direction --</option>
					                    <option value="Incoming">Incoming</option>
                                    	<option value="Outgoing">Outgoing</option>
                            </select>	
                        </div></td>
                       <td> <div class="form-group">
                            <input type="date" class="form-control" name="q" placeholder="Start date" value="<?= htmlentities($_GET['q'] ?? null)?>">		
                        </div></td>
                        <td><div class="form-group">
                            <input type="text" class="form-control" name="q1" placeholder="Network" value="<?= htmlentities($_GET['q1'] ?? null)?>">		
                        </div></td>
                        <td><div class="form-group">
                            <input type="text" class="form-control" name="q2" placeholder="Carrier" value="<?= htmlentities($_GET['q2'] ?? null)?>">		
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
                                <th><?= TableHelper::sort('network', 'network', $_GET) ?></th> 
                                <th><?= TableHelper::sort('carrier', 'carrier', $_GET) ?></th>
                                <th><?= TableHelper::sort('attempt', 'attempt', $_GET) ?></th>                                                    
                                <th><?= TableHelper::sort('ner', 'ner', $_GET) ?></th>
                                <th><?= TableHelper::sort('asr', 'asr', $_GET) ?></th>                       
                                <th><?= TableHelper::sort('acd_sec', 'acd_sec', $_GET) ?></th>	  
                            </tr>			
                            </tr>			
			</thead>

                        <tbody>  
                            <?php foreach($destinations as $dest): ?>
                                <tr>
                                    <td><?= $dest['direction']?></td>
                                    <td><?= $dest['start_date']?></td>
                                    <td><?= $dest['network']?></td>
                                    <td><?= $dest['carrier']?></td>	
                                    <td><?= $dest['attempt']?></td>	
                                    <td><?= $dest['ner']?></td>
                                    <td><?= $dest['asr']?></td>
                                    <td><?= $dest['acd_sec']?></td>				
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
