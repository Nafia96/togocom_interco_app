<?php
   require("menu.php");
?>
        <div class="container-fluid">
            <div class="row">
                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                    <div class="position-sticky pt-3 sidebar-sticky">
                    <h6>KPI INDICATOR</h6>
			            <ul class="nav flex-column">
                            <?= nav_link('/daily_overview.php', 'Daily Overview'); ?> 
                            <?= nav_link('/top30_destination.php', 'Top30 Destination'); ?>
                            <?= nav_link('/carrier_customer_range29.php', 'Carrier Overview'); ?>
                            <?= nav_link('/network_overview.php', 'Network Overview'); ?>
                            <?= nav_link('/country_overview.php', 'Country Overview'); ?>
                            <?= nav_link('/network_carrier_overview.php', 'Network Carrier'); ?>         
	                    </ul> 
	                        <br/>
                        <h6>
                            TOTAL RANGE
                        </h6>
                        <ul class="nav flex-column mb-2">
                            <?= nav_link('stat_carrier_group.php', 'Carrier Grouping'); ?> 
                            <?= nav_link('/stat_network_group.php', 'Network Grouping'); ?> 
                            <?= nav_link('/stat_network_carrier_group.php', 'Network Carrier'); ?>          
                            <?= nav_link('/stat_monthly_report.php', 'Monthly Report'); ?>          
                        </ul>    
                            <br/>
                        <h6>
                            TROUBLESHOOTING
                        </h6>

                        <ul class="nav flex-column mb-2">
                            <?= nav_link('/trunk_sip_error.php', 'Trunk SIP Error'); ?>  
                            <?= nav_link('/daily_sip_error.php', 'Daily SIP Error'); ?>  
                            <?= nav_link('/carrier_sip_error.php', ' Carrier SIP Error'); ?>  
                            <?= nav_link('/network_sip_error.php', 'Network SIP Error'); ?>  
                        </ul>
                    </div>
                </nav>
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
		    <form action="" class="mb-4">
