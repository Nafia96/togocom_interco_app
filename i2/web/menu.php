<?php
function nav_item(string $lien, string $titre): string{
    $classe = 'nav-link';
    if($_SERVER['SCRIPT_NAME'] === $lien){
        $classe .= ' active';
    } return <<<HTML
    <li class="nav-item">
         <a class="$classe" href="$lien">$titre</a>
    </li>
HTML;
}

function nav_link(string $lien, string $titre): string{
    $classe = 'nav-link';
    if($_SERVER['SCRIPT_NAME'] === $lien){
        $classe .= ' active';
    } return <<<HTML
    <li class="nav-item">
         <a class="$classe" href="$lien"> 
            <span data-feather="shopping-cart" class="align-text-bottom"></span> 
            $titre</a>
    </li>
HTML;
}

?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
        <meta name="generator" content="Hugo 0.104.2">
        <title>aduba_report</title>

        <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/dashboard/"> 
        <link href="/docs/5.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

        <!-- Favicons -->
        <link rel="apple-touch-icon" href="/docs/5.2/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
        <link rel="icon" href="/docs/5.2/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
        <link rel="icon" href="/docs/5.2/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
        <link rel="manifest" href="/docs/5.2/assets/img/favicons/manifest.json">
        <link rel="mask-icon" href="/docs/5.2/assets/img/favicons/safari-pinned-tab.svg" color="#712cf9">
        <link rel="icon" href="/docs/5.2/assets/img/favicons/favicon.ico">
        <meta name="theme-color" content="#712cf9">

        <!-- CSS only -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

        <!-- JavaScript Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }

            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                font-size: 3.5rem;
                }
            }

            .b-example-divider {
                height: 3rem;
                background-color: rgba(0, 0, 0, .1);
                border: solid rgba(0, 0, 0, .15);
                border-width: 1px 0;
                box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
            }

            .b-example-vr {
                flex-shrink: 0;
                width: 1.5rem;
                height: 100vh;
            }

            .bi {
                vertical-align: -.125em;
                fill: currentColor;
            }

            .nav-scroller {
                position: relative;
                z-index: 2;
                height: 2.75rem;
                overflow-y: hidden;
            }

            .nav-scroller .nav {
                display: flex;
                flex-wrap: nowrap;
                padding-bottom: 1rem;
                margin-top: -1px;
                overflow-x: auto;
                text-align: center;
                white-space: nowrap;
                -webkit-overflow-scrolling: touch;
	    }
	    
	    .tableau{
		height: 470px;
		border: 1px solid white;
		overflow: auto;
	    }	

             h2{
		    color: red;
	    }	

        .form-group{
            margin-right: 10px;
	}

	.navbar-brand{
	    color: yellow;
	}
	
	h6{
	    color: green;
	}
       </style> 
        <!-- Custom styles for this template -->
        <link href="dashboard.css" rel="stylesheet">
    </head>
  
    <body>   
        <header class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">@duba</a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <?= nav_item('/daily_overview.php', 'Statistic'); ?>
                        <?= nav_item('/daily_trend.php', 'Volume'); ?>
                        <?= nav_item('/cdr_tracing.php', 'Live Record'); ?>
                        <?= nav_item('/ch_daily_summary.php', 'Finance'); ?>
			            <?= nav_item('/carrier_table.php', 'Resource'); ?>
                    </ul>   
                </div>
                
                <div class="navbar-nav">
                    <div class="nav-item text-nowrap">
                        <a class="nav-link px-1" href="logout.php">Sign Out</a>
                    </div>
                </div> 
            </div>
        </header>           
  
