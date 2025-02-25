<?php

define("LIGNES_PAR_PAGE", 10);

session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}


$query = "SELECT direction, country, sum(answered) calls, sum(duration_min) duration FROM country_traffic";

$queryCount = "SELECT count(a.direction) count from (select direction, country, sum(answered) FROM country_traffic";
$params = [];
$sortable = ["direction", "country", "calls", "duration"];
$direction_list = ["Incoming", "Outgoing"];
//$group_list = ["start_date", "start_month"];

//$querycountry = "SELECT DISTINCT country_name FROM country_tab ORDER BY country_name";

// #0 Affichage sans parametre
if(!in_array($_GET['direction'], $direction_list) && empty($_GET['q']) && empty($_GET['q1']) && empty($_GET['q2'])){
	$query .= " WHERE substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
    $queryCount .= " WHERE substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
}

// #1 Recherche par direction, dates, country
if(in_array($_GET['direction'], $direction_list) && !empty($_GET['q']) && !empty($_GET['q1']) && !empty($_GET['q2'])){
	$query .= " WHERE direction LIKE :direction AND country LIKE :country AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE direction LIKE :direction AND country LIKE :country AND start_date BETWEEN :begin_date AND :end_date";
    $params['direction'] = '%' . $_GET['direction'] . '%';
    $params['country'] = '%' . $_GET['q'] . '%';
    $params['begin_date'] = $_GET['q1'];
    $params['end_date'] = $_GET['q2'];
}

// #2 Recherche par direction
if(in_array($_GET['direction'], $direction_list) && empty($_GET['q']) && empty($_GET['q1']) && empty($_GET['q2'])){
	$query .= " WHERE direction LIKE :direction AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
    $queryCount .= " WHERE direction LIKE :direction AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
	$params['direction'] = '%' . $_GET['direction'] . '%';
}

// #3 Recherche par start_dates
if(!in_array($_GET['direction'], $direction_list) && empty($_GET['q']) && !empty($_GET['q1']) && !empty($_GET['q2'])){
	$query .= " WHERE start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE start_date BETWEEN :begin_date AND :end_date";
    $params['begin_date'] = $_GET['q1'];
    $params['end_date'] = $_GET['q2'];
}

// #4 Recherche par country
if(!in_array($_GET['direction'], $direction_list) && !empty($_GET['q']) && empty($_GET['q1']) && empty($_GET['q2'])){
  $query .= " WHERE country LIKE :country AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2)) ";
    $queryCount .= " WHERE country LIKE :country AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
    $params['country'] = '%' . $_GET['q'] . '%';
}

// #5 Recherche par direction, start_dates
if(in_array($_GET['direction'], $direction_list)  && empty($_GET['q']) && !empty($_GET['q1']) && !empty($_GET['q2'])){
	$query .= " WHERE direction LIKE :direction AND start_date BETWEEN :begin_date AND :end_date";
    $queryCount .= " WHERE direction LIKE :direction AND start_date BETWEEN :begin_date AND :end_date";
	$params['direction'] = '%' . $_GET['direction'] . '%';
    $params['begin_date'] = $_GET['q1'];
    $params['end_date'] = $_GET['q2'];
}

// #6 Recherche par direction, country
if(in_array($_GET['direction'], $direction_list) && !empty($_GET['q']) && empty($_GET['q1']) && empty($_GET['q2'])){
	$query .= " WHERE direction LIKE :direction AND country LIKE :country AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
    $queryCount .= " WHERE direction LIKE :direction AND country LIKE :country AND substr(start_date,1,7) = concat(substr(current_date-1,1,4), '-', substr(current_date-1,5,2))";
	$params['direction'] = '%' . $_GET['direction'] . '%';
    $params['country'] = '%' . $_GET['q'] . '%';
}

// #7 Recherche par date, country
if(!in_array($_GET['direction'], $direction_list) && !empty($_GET['q']) && !empty($_GET['q1']) && !empty($_GET['q2'])){
	$query .= " WHERE start_date BETWEEN :begin_date AND :end_date AND country LIKE :country";
    $queryCount .= " WHERE start_date BETWEEN :begin_date AND :end_date AND country LIKE :country";
    $params['begin_date'] = $_GET['q1'];
    $params['end_date'] = $_GET['q2'];
    $params['country'] = '%' . $_GET['q'] . '%';
}
    $query .= " group by direction, country";
    $queryCount .= " group by direction, country)a";

// organisation
if(!empty($_GET['sort']) && in_array($_GET['sort'], $sortable)){
    $sens = $_GET['sens'] ?? 'asc';
    if(!in_array($sens, ['asc', 'desc'])){
        $sens = 'asc';
    }
    $query .= " ORDER BY " . $_GET['sort'] . " $sens";
}else if(empty($_GET['sort'])){
    $query .= " ORDER BY direction, country";
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

//$statement = $bdd->query($querycountry);
//$countrys = $statement->fetchAll();


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
$_SESSION['direction'] = $_GET['direction'];

?>

<?php
    require("bill_header.php");
?>
 <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2 class="h2"> Country Grouping </h2>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="float-right">
                <a href="export_country_grouping.php" class="btn btn-success"><i class="dwn"></i> Export to CSV</a>
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
                            </div>
                        </td>

                        <td> <div class="form-group">
                            <input type="text" class="form-control" name="q" placeholder="country" value="<?= htmlentities($_GET['q'] ?? null)?>">		
                        </div></td>

                        <!--<td> <div class="form-group">
                            <select class="form-select form-select" name="country_name">
					                    <option selected>-- country --</option> 				                
                                        </?php foreach($countrys as $country): ?>
                                            <option value="</?= $country['country_name']?>">
                                                </?= $country['country_name']?>
                                            </option>		
			                            </?php endforeach ?>	
                            </select>
                            </div>
                        </td> -->

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
                                <th><?= TableHelper::sort('country', 'country', $_GET) ?></th>
                                <th><?= TableHelper::sort('calls', 'calls', $_GET) ?></th>
                                <th><?= TableHelper::sort('duration', 'duration', $_GET) ?></th>
                            </tr>		
			</thead>

                        <tbody>  
                            <?php foreach($destinations as $dest): ?>
                                <tr>
                                    <td><?= $dest['direction']?></td>
                                    <td><?= $dest['country']?></td>
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
