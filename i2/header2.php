<?php

$traffic_qos=["/daily_overview.php","/top50_dest_overview.php","/carrier_overview29.php","/network_overview.php","/country_overview.php","/network_carrier_overview.php"];
$total_range=["/stat_carrier_group.php","/stat_network_group.php","/stat_network_carrier_group.php","/stat_monthly_report.php"];
$troubleshooting=["/daily_sip_error.php","/carrier_trunkid_sip_error.php","/network_sip_error.php","/network_carrier_sip_error.php"];

   require("menu.php");
?>
        <div class="container-fluid">
            <div class="row">
                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                    <div class="position-sticky pt-3 sidebar-sticky">

                        <div class="accordion" id="accordionContact">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button type="button" class="<?php if(!in_array($_SERVER['SCRIPT_NAME'],$traffic_qos)) echo "accordion-button collapsed"; else echo "accordion-button";?>" data-bs-toggle="collapse" data-bs-target="#accordion1">Traffic QoS</button>
                                </h2>
                                <section class="accordion-body collapse <?php if(in_array($_SERVER['SCRIPT_NAME'],$traffic_qos)) echo " show";?>" id="accordion1" data-bs-parent="#accordionContact">
                                    <ul class="nav flex-column mb-2">
                                        <?= nav_link('/daily_overview.php', 'Daily Overview'); ?> 
                                        <?= nav_link('/top50_dest_overview.php', 'Top50 Dest Overview'); ?>
                                        <?= nav_link('/carrier_overview29.php', 'Carrier Overview'); ?>
                                        <?= nav_link('/network_overview.php', 'Network Overview'); ?>
                                        <?= nav_link('/country_overview.php', 'Country Overview'); ?>
                                        <?= nav_link('/network_carrier_overview.php', 'Network Carrier Overview'); ?>         
                                    </ul> 
                                </section>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button type="button" class="<?php if(!in_array($_SERVER['SCRIPT_NAME'],$total_range)) echo "accordion-button collapsed"; else echo "accordion-button";?>" data-bs-toggle="collapse" data-bs-target="#accordion2">Total Range</button>
                                </h2>
                                <section class="accordion-body collapse <?php if(in_array($_SERVER['SCRIPT_NAME'],$total_range)) echo " show";?>" id="accordion2" data-bs-parent="#accordionContact">
                                    <ul class="nav flex-column mb-2">
                                        <?= nav_link('stat_carrier_group.php', 'Carrier Grouping'); ?> 
                                        <?= nav_link('/stat_network_group.php', 'Network Grouping'); ?> 
                                        <?= nav_link('/stat_network_carrier_group.php', 'Network Carrier'); ?>          
                                        <?= nav_link('/stat_monthly_report.php', 'Monthly Report'); ?>         
                                    </ul> 
                                </section>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button type="button" class="<?php if(!in_array($_SERVER['SCRIPT_NAME'],$troubleshooting)) echo "accordion-button collapsed"; else echo "accordion-button";?>" data-bs-toggle="collapse" data-bs-target="#accordion3">TroubleShooting</button>
                                </h2>
                                <section class="accordion-body collapse <?php if(in_array($_SERVER['SCRIPT_NAME'],$troubleshooting)) echo " show";?>" id="accordion3" data-bs-parent="#accordionContact">
                                    <ul class="nav flex-column mb-2">
                                        <?= nav_link('/daily_sip_error.php', 'Daily SIP Error'); ?>  
                                        <?= nav_link('/carrier_trunkid_sip_error.php', ' Carrier Trunk-ID SIP Error'); ?>  
                                        <?= nav_link('/network_sip_error.php', 'Network SIP Error'); ?>  
                                        <?= nav_link('/network_carrier_sip_error.php', 'Network Carrier Trunk ID'); ?>       
                                    </ul> 
                                </section>
                            </div>
                        </div>
                    </div>

    
                    <!-- <h6>KPI INDICATOR</h6>
			            <ul class="nav flex-column">
                            <//?= nav_link('/daily_overview.php', 'Daily Overview'); ?> 
                            <//?= nav_link('/top50_dest_overview.php', 'Top50 Dest Overview'); ?>
                            <//?= nav_link('/carrier_overview29.php', 'Carrier Overview'); ?>
                            <//?= nav_link('/network_overview.php', 'Network Overview'); ?>
                            <//?= nav_link('/country_overview.php', 'Country Overview'); ?>
                            <//?= nav_link('/network_carrier_overview.php', 'Network Carrier Overview'); ?>         
	                    </ul> 
	                        <br/>
                        <h6>
			    TOTAL RANGE
                        </h6>
                        <ul class="nav flex-column mb-2">
                            <//?= nav_link('stat_carrier_group.php', 'Carrier Grouping'); ?> 
                            <//?= nav_link('/stat_network_group.php', 'Network Grouping'); ?> 
                            <//?= nav_link('/stat_network_carrier_group.php', 'Network Carrier'); ?>          
                            <//?= nav_link('/stat_monthly_report.php', 'Monthly Report'); ?>          
                        </ul>    
                            <br/>
                        <h6>
                            TROUBLESHOOTING
                        </h6>

                        <ul class="nav flex-column mb-2">
                            <//?= nav_link('/daily_sip_error.php', 'Daily SIP Error'); ?>  
                            <//?= nav_link('/carrier_trunkid_sip_error.php', ' Carrier Trunk-ID SIP Error'); ?>  
                            <//?= nav_link('/network_sip_error.php', 'Network SIP Error'); ?>  
                            <//?= nav_link('/network_carrier_sip_error.php', 'Network Carrier Trunk ID'); ?>  
                        </ul> -->
                </nav>
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
		    <form action="" class="mb-4">
