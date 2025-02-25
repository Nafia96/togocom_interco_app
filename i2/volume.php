<?php
   require("menu.php");
?>

<div class="container-fluid">
            <div class="row">
                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                    <div class="position-sticky pt-3 sidebar-sticky">
                    <h6>TRAFFIC DURATION</h6>
			            <ul class="nav flex-column">
                            <?= nav_link('/daily_trend.php', 'Daily Trend'); ?> 
                            <?= nav_link('/top30_trend.php', 'Top30 Trend'); ?>
                            <?= nav_link('/carrier_trend.php', 'Carrier Trend'); ?>
                            <?= nav_link('/network_trend.php', 'Network Trend'); ?>
                            <?= nav_link('/country_trend.php', 'Country Trend'); ?>
                            <?= nav_link('/network_carrier_trend.php', 'Net Carrier Trend'); ?>         
	                    </ul> 
	                        <br/>
                    <h6>TRAFFIC SUM</h6>
                        <ul class="nav flex-column mb-2">
                            <?= nav_link('/vol_carrier_grouping.php', 'Carrier Grouping'); ?> 
                            <?= nav_link('/vol_network_grouping.php', 'Network Grouping'); ?> 
                            <?= nav_link('/vol_country_grouping.php', 'Country Grouping'); ?>
                            <?= nav_link('/vol_network_carrier_group.php', 'Net Carrier Group'); ?>          
                        </ul>    
                            <br/>
                    <h6>TRAFFIC  REVENUE</h6>
                        <ul class="nav flex-column mb-2">
                            <?= nav_link('/daily_summary.php', 'Daily Summury'); ?>  
                            <?= nav_link('/daily_carrier.php', 'Daily Carrier'); ?>  
                            <?= nav_link('/monthly_summary.php', ' Monthly Summary'); ?>  
                            <?= nav_link('/monthly_carrier.php', 'Monthly Carrier'); ?>  
                            <?= nav_link('/monthly_details.php', 'Monthly Details'); ?>  
                        </ul>
                    </div>
                </nav>
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
		    <form action="" class="mb-4">
 
