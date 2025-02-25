<?php

define("LIGNES_PAR_PAGE", 10);

session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "SELECT * FROM carrier_overview";
$queryCount = "SELECT COUNT(carrier) as count FROM carrier_overview";
$params = [];
$sortable = ["direction", "start_date", "carrier", "attempt", "ner", "asr", "acd_sec"];

//Recherche par direction, date, carrier
if(!empty($_GET['q1']) and !empty($_GET['q2']) and !empty($_GET['q3'])){
	$query .= " WHERE direction LIKE :direction AND start_date LIKE :start_date AND carrier LIKE :carrier";
    $queryCount .= " WHERE direction LIKE :direction AND start_date LIKE :start_date AND carrier LIKE :carrier";
    $params['direction'] = '%' . $_GET['q1'] . '%';
	$params['start_date'] = '%' . $_GET['q2'] . '%';
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
	$query .= " WHERE start_date LIKE :start_date";
    $queryCount .= " WHERE start_date LIKE :start_date";
	$params['start_date'] = '%' . $_GET['q2'] . '%';
}

//Recherche par carrier
if(empty($_GET['q1']) and empty($_GET['q2']) and !empty($_GET['q3'])){
    $query .= " WHERE carrier LIKE :carrier";
    $queryCount .= " WHERE carrier LIKE :carrier";
    $params['carrier'] = '%' . $_GET['q3'] . '%';
}

//Recherche par direction, date
if(!empty($_GET['q1']) and !empty($_GET['q2']) and empty($_GET['q3'])){
	$query .= " WHERE direction LIKE :direction AND start_date LIKE :start_date";
    $queryCount .= " WHERE direction LIKE :direction AND start_date LIKE :start_date";
	$params['direction'] = '%' . $_GET['q1'] . '%';
    $params['start_date'] = '%' . $_GET['q2'] . '%';
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
	$query .= " WHERE start_date LIKE :start_date AND carrier LIKE :carrier";
    $queryCount .= " WHERE start_date LIKE :start_date AND carrier LIKE :carrier";
	$params['start_date'] = '%' . $_GET['q2'] . '%';
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
    public static function withParam(string $param, $value): string{
        return http_build_query(array_merge($_GET, [$param => $value]));
    }
    public static function withParams(array $params): string {
        return http_build_query(array_merge($_GET, $params));
    }
} 

?>

<?php
require("header2.php");
?>

<h2>Carrier overview</h2>

<table> 
<tr>
                        <td> <div class="form-group">
                            <input type="text" class="form-control" name="q1" placeholder="Recherche par direction" value="<?= htmlentities($_GET['q1'] ?? null)?>">		
                        </div></td>
                       <td> <div class="form-group">
                            <input type="text" class="form-control" name="q2" placeholder="Recherche par date" value="<?= htmlentities($_GET['q2'] ?? null)?>">		
                        </div></td>
                        <td><div class="form-group">
                            <input type="text" class="form-control" name="q3" placeholder="Recherche par reseau" value="<?= htmlentities($_GET['q3'] ?? null)?>">		
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
                                <th>direction</th>
                                <th>start_date</th>
                                <th>carrier</th>	
                                <th>calls</th>                             
				<th>duration_min</th>			
				<th>market_share</th>	
                                
                            </tr>			
			</thead>

                        <tbody>  
                            <?php foreach($destinations as $dest): ?>
                                <tr>
                                    <td><?= $dest['direction']?></td>
                                    <td><?= $dest['start_date']?></td>
                                    <td><?= $dest['carrier']?></td>
                                    <td><?= $dest['answered']?></td>	
                                    <td><?= $dest['duration_min']?></td>
                                    <td><?= $dest['market_share']?></td		
                                    	
                                </tr>
			    <?php endforeach ?>	
			</tbody>
		     </table>
		</div>

                    <?php if ($pages > 1 && $page > 1): ?>
                        <a href="?<?= URLHelper::withParam("p", $page - 1) ?>" class="btn btn-primary">Preview page</a>
                    <?php endif ?>

                    <?php if ($pages > 1 && $page < $pages): ?>
                        <a href="?<?= URLHelper::withParam("p", $page + 1) ?>"  class="btn btn-primary">Next page</a>
                    <?php endif ?>

                </main>
            </div>
        </div>

        <script src="/docs/5.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

        <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="dashboard.js"></script>
    
    </body>

</html>
