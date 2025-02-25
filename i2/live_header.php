<?php
   require("menu.php");
?>

        <div class="container-fluid">
            <div class="row">
                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                    <div class="position-sticky pt-3 sidebar-sticky">
                        <h6>LIVE STREAM</h6>
                            <ul class="nav flex-column">
                                <?= nav_link('/cdr_tracing.php', 'CDR Tracing'); ?> 
                                <?= nav_link('/live_trend.php', 'Live Trend'); ?>
                                <?= nav_link('/live_carrier.php', 'Live Carrier'); ?>
                                <?= nav_link('/live_network.php', 'Live Network'); ?>
                                <?= nav_link('/live_network_carrier.php', 'Live Net Carrier'); ?>
                                <?= nav_link('/hur_report.php', 'HUR Report'); ?>         
                            </ul> 
                                <br/>
                        <h6>PREVIEW STREAM</h6>
                            <ul class="nav flex-column mb-2">
                                <?= nav_link('hourly_overview.php', 'Hourly Overview'); ?> 
                                <?= nav_link('/hourly_carrier.php', 'Hourly Carrier'); ?> 
                                <?= nav_link('/hourly_network.php', 'Hourly Network'); ?>      
                            </ul>    
                    </div>
                </nav>
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
		    <form action="" class="mb-4">
 
