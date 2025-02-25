<?php

define("LIGNES_PAR_PAGE", 10);

session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "SELECT direction, substr(start_date,1,7) start_month, sum(attempt) attempt, concat(round(sum(completed)/sum(attempt)*100),'%') ner, concat(round(sum(answered)/sum(attempt)*100),'%') asr, round(sum(duration_min)/sum(answered)*60) acd_sec, sum(duration_min) volume_min FROM `carrier_traffic`";
$queryCount = "SELECT count(a.direction) count from (SELECT direction, substr(start_date,1,7) start_month FROM carrier_traffic";
$params = [];
$sortable = ["start_month", "attempt", "ner", "asr", "acd_sec"];
$direction_list = ["Incoming", "Outgoing"];


//Recherche sans parametre
if(!in_array($_GET['direction'], $direction_list) && empty($_GET['q'])){
	$query .= " WHERE 1";
    $queryCount .= " WHERE 1";
}

//Recherche par direction, start_month
if(in_array($_GET['direction'], $direction_list) && !empty($_GET['q'])){
	$query .= " WHERE direction LIKE :direction AND substr(start_date,1,7) LIKE :start_month";
    $queryCount .= " WHERE direction LIKE :direction AND substr(start_date,1,7) LIKE :start_month";
    $params['direction'] = '%' . $_GET['direction'] . '%';
    $params['start_month'] = '%' . $_GET['q'] . '%';
}

//Recherche par direction
if(in_array($_GET['direction'], $direction_list) && empty($_GET['q'])){
	$query .= " WHERE direction LIKE :direction";
    $queryCount .= " WHERE direction LIKE :direction";
    $params['direction'] = '%' . $_GET['direction'] . '%';
}

//Recherche par start_month
if(!in_array($_GET['direction'], $direction_list) && !empty($_GET['q'])){
	$query .= " WHERE substr(start_date,1,7) LIKE :start_month";
    $queryCount .= " WHERE substr(start_date,1,7) LIKE :start_month";
    $params['start_month'] = '%' . $_GET['q'] . '%';
}

$query .= " GROUP BY direction, start_month";
$queryCount .= " GROUP BY direction, start_month)a";

// organisation
if(!empty($_GET['sort']) && in_array($_GET['sort'], $sortable)){
    $sens = $_GET['sens'] ?? 'asc';
    if(!in_array($sens, ['asc', 'desc'])){
        $sens = 'asc';
    }
    $query .= " ORDER BY " . $_GET['sort'] . " $sens";
}else if(empty($_GET['sort'])){
    $query .= " ORDER BY direction, start_month desc";
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
$_SESSION['q'] = $_GET['q'];

?>

<?php
    require("header2.php");
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2 class="h2"> Monthly Report </h2>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="float-right">
                <a href="export_stat_monthly_report.php" class="btn btn-success"><i class="dwn"></i> Export to CSV</a>
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
                            <input type="month" class="form-control" name="q" placeholder="YYYY-MM" value="<?= htmlentities($_GET['q'] ?? null)?>">		
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
                                <th><?= TableHelper::sort('attempt', 'attempt', $_GET) ?></th>
                                <th><?= TableHelper::sort('ner', 'ner', $_GET) ?></th>
                                <th><?= TableHelper::sort('asr', 'asr', $_GET) ?></th>
                                <th><?= TableHelper::sort('acd_sec', 'acd_sec', $_GET) ?></th>
                                
                            </tr>		
			</thead>

                        <tbody>  
                            <?php foreach($destinations as $dest): ?>
                                <tr>
                                    <td><?= $dest['direction']?></td>
				    <td><?= $dest['start_month']?></td>
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
