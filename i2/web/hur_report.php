<?php

define("LIGNES_PAR_PAGE", 10);

session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "SELECT * FROM hur_report";
$queryCount = "SELECT COUNT(start_date) as count FROM hur_report";
$params = [];
/*
//Recherche par date, network
if(!empty($_GET['q1']) and !empty($_GET['q2'])){
	$query .= " WHERE start_date LIKE :start_date AND network LIKE :network";
    $queryCount .= " WHERE start_date LIKE :start_date AND network LIKE :network";
    $params['start_date'] = '%' . $_GET['q1'] . '%';
	$params['network'] = '%' . $_GET['q2'] . '%';
}*/

//Recherche par date
if(!empty($_GET['q'])){
	$query .= " WHERE start_date LIKE :start_date";
    $queryCount .= " WHERE start_date LIKE :start_date";
	$params['start_date'] = '%' . $_GET['q'] . '%';
}
/*
//Recherche par network
if(empty($_GET['q1']) and !empty($_GET['q2'])){
	$query .= " WHERE network LIKE :network";
    $queryCount .= " WHERE network LIKE :network";
	$params['network'] = '%' . $_GET['q2'] . '%';
}*/

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
    require("live_header.php");
?>

<h2>HUR Report</h2>
<br/>

<table> 
<tr>
                        <td> <div class="form-group">
                            <input type="date" class="form-control" name="q1" placeholder="Recherche par date" value="<?= htmlentities($_GET['q'] ?? null)?>">		
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
                                <th>a_number</th>	
                                <th>b_number</th>
                                <th>start_date</th>                               
                                <th>return_code</th>
                                <th>call_duration</th>
                                <th>in_trunk_id</th>	
                                <th>out_trunk_id</th>		
                            </tr>			
			</thead>

                        <tbody>  
                            <?php foreach($destinations as $dest): ?>
                                <tr>
                                    <td><?= $dest['direction']?></td>
                                    <td><?= $dest['a_number']?></td>
                                    <td><?= $dest['b_number']?></td>
                                    <td><?= $dest['start_date']?></td>                                    		
                                    <td><?= $dest['return_code']?></td>
                                    <td><?= $dest['call_duration']?></td>
                                    <td><?= $dest['in_trunk_id']?></td>		
                                    <td><?= $dest['out_trunk_id']?></td>		
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
