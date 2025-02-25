<?php

define("LIGNES_PAR_PAGE", 10);

session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "SELECT start_date, sum(duration) duration, round(sum(revenue_cfa)) revenue_cfa FROM `revenu`";
$queryCount = "SELECT COUNT(a.start_date) as count from (select start_date, sum(revenue_cfa) FROM `revenu` group by start_date)a";
$params = [];
$sortable = ["start_date", "duration", "revenue_cfa"];

//Recherche par start_date, date
if(!empty($_GET['q1']) and !empty($_GET['q2'])) {
	$query .= " WHERE start_date BETWEEN :st_date AND :end_date";
    $queryCount .= " WHERE start_date BETWEEN :st_date AND :end_date";
    $params['st_date'] = $_GET['q1'];
    $params['end_date'] = $_GET['q2'];
}

$query .= " GROUP BY start_date";

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

// exportation de données
if(isset($_POST["csv_export"])) {
    header("Content-Type: text/csv; charset=utf-8");
    header("Content-Disposition: attachement; filename=data.csv");
    $output = fopen( "php://output", "w");
    fputcsv($output, array('start_date', 'duration', 'revenue_cfa'));
    while( $row = mysqli_fetch_assoc($destinations)){
        fputcsv($output, $row);
    }
        fclose($output);
    }
?>

<?php
    require("bill_header.php");
?>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2 class="h2">Daily Summary</h2>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="float-right">
                <a href="export.php" class="btn btn-success"><i class="dwn"></i> Export</a>
            </div>
        </div>
    </div>
        
<br/>

<table> 
<tr>
                        <td> <div class="form-group">
                            <input type="date" class="form-control" name="q1" placeholder="Starting date" value="<?= htmlentities($_GET['q1'] ?? null)?>">		
                        </div></td>   
                        <td> <div class="form-group">
                            <input type="date" class="form-control" name="q2" placeholder="end date" value="<?= htmlentities($_GET['q2'] ?? null)?>">		
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
                                <th><?= TableHelper::sort('duration', 'duration_min', $_GET) ?></th>
                                <th><?= TableHelper::sort('revenue_cfa', 'revenue_cfa', $_GET) ?></th>
                                
                            </tr>		
			</thead>

                        <tbody>  
                            <?php foreach($destinations as $dest): ?>
                                <tr>
                                    <td><?= $dest['start_date']?></td>
                                    <td><?= $dest['duration']?></td>
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
