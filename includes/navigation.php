<?php
    $host = "localhost";
?>
<div class="main_container container_16 clearfix">
    <div id="nav_top" class="clearfix round_top">
        <ul class="clearfix">
            <li><a href="http://<?=$host;?>/wais/dashboard/"><img src="../images/widget.png"/>Dashboard</a></li>  
            <li><a href="#"><img src="../images/widget.png"/>Transactions</a>
                <ul class="dropdown dropdown_left">
                    <li><a href="http://<?=$host;?>/wais/sales/">Sales</a></li>
                    <li><a href="http://<?=$host;?>/wais/collections/">Collections</a></li>
                    <li><a href="http://<?=$host;?>/wais/pullout/">Pull Out Log</a></li>
      			</ul>
            </li>  
            <li><a href="http://<?=$host;?>/wais/contracts/"><img src="../images/issuance.png"/>Contracts</a></li>          
            <li><a href="#"><img src="../images/employee.png"/>Accounts</a>
                <ul class="dropdown">
                    <li><a href="http://<?=$host;?>/wais/customer/">Customer</a></li>
                    <li><a href="http://<?=$host;?>/wais/employee/">Employees</a></li>
                    <li><a href="http://<?=$host;?>/wais/vendor/">Vendor</a></li>
                </ul>
            </li>
            <li><a href="#"><img src="../images/inventory.png"/>Inventory</a>
                <ul class="dropdown">
                    <li><a href="http://<?=$host;?>/wais/products/">Products</a></li>
                    <li><a href="http://<?=$host;?>/wais/po/">Purchase Orders</a></li>
                    <li><a href="http://<?=$host;?>/wais/stocks/">Stocks</a></li>
                    <li><a href="http://<?=$host;?>/wais/stocktransfer/">Stock Transfer</a></li>          
    			</ul>
            </li>	
            <li><a href="#"><img src="../images/financials.gif"/>Accounting</a>           
                <ul class="dropdown">      
                     <li><a href="http://<?=$host;?>/wais/journal/">Journal Entries</a></li>          
    			</ul>                        
            </li>        
            <li><a href="http://<?=$host;?>/wais/reports/"><img src="../images/printer_icon.png"/>Reports</a>           
                <ul class="dropdown">       
    			</ul>                        
            </li>    				
            <li><a href="#"><img src="../images/gear.png"/>System</a>
                <ul class="dropdown dropdown_right">
                    <li><a href="http://<?=$host;?>/wais/definitions/">Definitions</a></li>          
                    <li><a href="http://<?=$host;?>/wais/usermanagement/">User Management</a></li>          
                </ul>
            </li>
            <li><a href="#"><img src="../images/helpbutton.png"/>Help</a>
                <ul class="dropdown dropdown_right">
                    <li><a href="http://<?=$host;?>/wais/usermanual.pdf">User Manual</a></li>          
                    <li><a href="http://<?=$host;?>/wais/wais.pdf">About WAIS</a></li>          
               </ul>
            </li>  	
            <div class="branch-tag"><i class="fa fa-industry" aria-hidden="true"></i> <?php echo get_userbranch($_SESSION['user_id']); ?></div>
        </ul>
    </div>
</div>
