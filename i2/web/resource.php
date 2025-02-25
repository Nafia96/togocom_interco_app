<?php
   require("menu.php");
?>
  
  <div class="container-fluid">
            <div class="row">
                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                    <div class="position-sticky pt-3 sidebar-sticky">
                    <h6>RESOURCE LIST</h6>
			            <ul class="nav flex-column">
                            <?= nav_link('/carrier_table.php', 'CARRIER TABLE'); ?>  
                            <?= nav_link('/network_table.php', 'NETWORK TABLE'); ?>  
                            <?= nav_link('/country_table.php', 'COUNTRY TABLE'); ?>  
                            <?= nav_link('/price_table.php', 'PRICE TABLE'); ?>  
                            <?= nav_link('#', 'USER TABLE'); ?>  
	                    </ul> 
                    </div>
                </nav>
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
		    <form action="" class="mb-4">