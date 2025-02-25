<?php
   require("menu.php");
?>

<div class="container-fluid">
            <div class="row">
                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                    <div class="position-sticky pt-3 sidebar-sticky">
                    <h6>DECLARED CHARGE</h6>
			            <ul class="nav flex-column">
                            <?= nav_link('/ch_daily_summary.php', 'Daily Charge Summury'); ?>  
                            <?= nav_link('/ch_daily_carrier.php', 'Daily Charge Carrier'); ?>  
                            <?= nav_link('/ch_monthly_summary.php', 'Monthly Charge Summary'); ?>  
                            <?= nav_link('/ch_monthly_carrier.php', 'Monthly Charge Carrier'); ?>  
                            <?= nav_link('/ch_monthly_details.php', 'Monthly Charge Details'); ?>  
	                    </ul> 
	                        <br/>
                    <h6>DECLARED REVENUE</h6>
                        <ul class="nav flex-column mb-2">
                            <?= nav_link('/rv_daily_summary.php', 'Daily Revenue Summury'); ?>  
                            <?= nav_link('/rv_daily_carrier.php', 'Daily Revenue Carrier'); ?>  
                            <?= nav_link('/rv_monthly_summary.php', 'Monthly Revenue Summary'); ?>  
                            <?= nav_link('/rv_monthly_carrier.php', 'Monthly Revenue Carrier'); ?>  
                            <?= nav_link('/rv_monthly_details.php', 'Monthly Revenue Details'); ?> 
                        </ul>    
                            <br/>
                    <h6>VALIDATED CHARGE</h6>
                        <ul class="nav flex-column mb-2">  
                            <!-- </?= nav_link('/va_monthly_charge.php', 'Monthly Charge Validated'); ?> -->
                            <!-- </?= nav_link('/va_carrier_charge.php', 'Carrier Charge Validated'); ?> -->
                            <!-- </?= nav_link('/va_charge_details.php', 'Charge Details Validated'); ?> -->
                            <?= nav_link('#', 'Monthly Charge Validated'); ?>
                            <?= nav_link('#', 'Carrier Charge Validated'); ?> 
                            <?= nav_link('#', 'Charge Details Validated'); ?> 
                        </ul>
                        <br/>
                    <h6>VALIDATED REVENUE</h6>
                        <ul class="nav flex-column mb-2"> 
                            <!-- </?= nav_link('/va_monthly_revenue.php', 'Monthly Revenue Validated'); ?> -->
                            <!-- </?= nav_link('/va_carrier_revenue.php', 'Carrier Revenue Validated'); ?> -->
                            <!-- </?= nav_link('/va_revenue_details.php', 'Revenue Details Validated'); ?> -->
                            <?= nav_link('#', 'Monthly Revenue Validated'); ?>  
                            <?= nav_link('#', 'Carrier Revenue Validated'); ?>  
                            <?= nav_link('#', 'Revenue Details Validated'); ?>  
                        </ul>
                    </div>
                </nav>
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
		    <form action="" class="mb-4">
 
