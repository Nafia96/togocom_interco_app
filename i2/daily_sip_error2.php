<?php

define("LIGNES_PAR_PAGE", 11);

session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "SELECT * FROM daily_sip_error";
$queryCount = "SELECT COUNT(start_date) as count FROM daily_sip_error";
$params = [];

//Recherche par direction, date
if(!empty($_GET['q1']) and !empty($_GET['q2'])){
	$query .= " WHERE direction LIKE :direction AND start_date LIKE :start_date";
    $queryCount .= " WHERE direction LIKE :direction AND start_date LIKE :start_date";
    $params['direction'] = '%' . $_GET['q1'] . '%';
	$params['start_date'] = '%' . $_GET['q2'] . '%';
}

//Recherche par direction
if(!empty($_GET['q1']) and empty($_GET['q2'])){
	$query .= " WHERE direction LIKE :direction";
    $queryCount .= " WHERE direction LIKE :direction";
	$params['direction'] = '%' . $_GET['q1'] . '%';
}

//Recherche par date
if(empty($_GET['q1']) and !empty($_GET['q2'])){
	$query .= " WHERE start_date LIKE :start_date";
    $queryCount .= " WHERE start_date LIKE :start_date";
	$params['start_date'] = '%' . $_GET['q2'] . '%';
}
/*
//Recherche sans paramètre
$query .= " WHERE start_date = (CURRENT_DATE -2)";
$queryCount .= " WHERE start_date = (CURRENT_DATE -2)";
$params = [];*/

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

<table> 
<tr>
                        <td> <div class="form-group">
                            <input type="text" class="form-control" name="q1" placeholder="Recherche par direction" value="<?= htmlentities($_GET['q1'] ?? null)?>">		
                        </div></td>
                       <td> <div class="form-group">
                            <input type="text" class="form-control" name="q2" placeholder="Recherche par date" value="<?= htmlentities($_GET['q2'] ?? null)?>">		
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
                                <th>attempt</th>
                                <th>SIP_0</th>
                                <th>SIP_1</th>
                                <th>SIP_2</th>
                                <th>SIP_400</th>
                                <th>SIP_403</th>	
                                <th>SIP_404</th>
                                <th>SIP_408</th>
                                <th>SIP_410</th>	
                                <th>SIP_480</th>
                                <th>SIP_481</th>
                                <th>SIP_482</th>
                                <th>SIP_483</th>	
                                <th>SIP_484</th>
                                <th>SIP_486</th>
                                <th>SIP_487</th>	
                                <th>SIP_500</th>
                                <th>SIP_501</th>
                                <th>SIP_502</th>
                                <th>SIP_503</th>	
                                <th>SIP_504</th>		
                                <th>SIP_603</th>
                                <th>SIP_604</th>	
                                <th>SIP_606</th>					
                            </tr>			
			</thead>

                        <tbody>  
                            <?php foreach($destinations as $dest): ?>
                                <tr>
                                    <td><?= $dest['direction']?></td>
                                    <td><?= $dest['start_date']?></td>
                                    <td><?= $dest['attempt']?></td>
                                    <td><?= $dest['SIP_0']?></td>
                                    <td><?= $dest['SIP_1']?></td>		
                                    <td><?= $dest['SIP_2']?></td>
                                    <td><?= $dest['SIP_400']?></td>
                                    <td><?= $dest['SIP_403']?></td>		
                                    <td><?= $dest['SIP_404']?></td>	
                                    <td><?= $dest['SIP_408']?></td>
                                    <td><?= $dest['SIP_410']?></td>
                                    <td><?= $dest['SIP_480']?></td>
                                    <td><?= $dest['SIP_481']?></td>
                                    <td><?= $dest['SIP_482']?></td>		
                                    <td><?= $dest['SIP_483']?></td>
                                    <td><?= $dest['SIP_484']?></td>
                                    <td><?= $dest['SIP_486']?></td>		
                                    <td><?= $dest['SIP_487']?></td>	
                                    <td><?= $dest['SIP_500']?></td>
                                    <td><?= $dest['SIP_501']?></td>
                                    <td><?= $dest['SIP_502']?></td>
                                    <td><?= $dest['SIP_503']?></td>
                                    <td><?= $dest['SIP_504']?></td>		
                                    <td><?= $dest['SIP_603']?></td>
                                    <td><?= $dest['SIP_604']?></td>
                                    <td><?= $dest['SIP_606']?></td>				
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
