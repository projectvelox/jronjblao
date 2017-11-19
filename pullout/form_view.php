<style>
    .jsgrid-grid-header, .jsgrid-grid-body {
        overflow-y: scroll !important;
    }
</style>
<?php
    include "../includes/config.php";
    include "../includes/functions.php";
    include "../includes/functions.form.php";
    include "../includes/login_functions.php";
    include "../includes/session.php";

    $data = array();
    $query_main = "SELECT 
                        *,
                        (SELECT s_customer_id FROM sales_main WHERE sales_main.s_customer_id=pullout_main.p_sales_id) AS customer_id,
                        (SELECT CONCAT(c_lastname,', ',c_firstname) FROM customers WHERE customers.c_id=(SELECT s_customer_id FROM sales_main WHERE sales_main.s_customer_id=pullout_main.p_sales_id)) AS customer_name,
                        (SELECT c_id FROM customers WHERE customers.c_id=(SELECT s_customer_id FROM sales_main WHERE sales_main.s_customer_id=pullout_main.p_sales_id)) AS c_id,
                        (SELECT s_branch_id FROM sales_main WHERE sales_main.s_customer_id=pullout_main.p_sales_id) AS branch_id,
                        (SELECT b_name FROM branches WHERE branches.b_id=(SELECT s_branch_id FROM sales_main WHERE sales_main.s_customer_id=pullout_main.p_sales_id)) AS branch_name,
                        (SELECT p_code FROM products WHERE products.p_id=(SELECT p_product_id FROM inventory WHERE inventory.p_id=pullout_main.p_product_id)) AS p_code,
                        (SELECT p_name FROM products WHERE products.p_id=(SELECT p_product_id FROM inventory WHERE inventory.p_id=pullout_main.p_product_id)) AS p_name,
                        (SELECT a_name FROM areas WHERE a_id=(SELECT c_area FROM customers WHERE customers.c_id=(SELECT s_customer_id FROM sales_main WHERE sales_main.s_customer_id=pullout_main.p_sales_id))) AS area
                    FROM pullout_main WHERE p_id=".$_GET['id'];

    $recordset_main = mysql_query($query_main) or die('Query failed: ' . mysql_error());
    while ($row_main = mysql_fetch_array($recordset_main, MYSQL_ASSOC)) {
        $data[] = $row_main;
        $product = $row_main['p_code']." - ".$row_main['p_name'];
        if($row_main['p_status']=="D") { $status = "Draft"; }
        else if($row_main['p_status']=="A") { $status = "Approved"; }
        else if($row_main['p_status']=="P") { $status = "Received"; }
        else if($row_main['p_status']=="G") { $status = "Redeemed"; }
        else if($row_main['p_status']=="R") { $status = "Repossed"; }
    }

    $data_parts = '[';
    $query_sub = "SELECT * FROM pullout_sub WHERE p_parent_id=".$_GET['id']." ORDER BY p_parts_name ASC";
    $recordset_sub = mysql_query($query_sub) or die('Query failed: ' . mysql_error());
    while ($row_sub = mysql_fetch_array($recordset_sub, MYSQL_ASSOC)) {
        if($row_sub['p_status']=="D") { $p_parts_status = "Damaged"; } else { $p_parts_status = "Missing"; }
        $data_parts .= '{
            "p_sub_id":"'.$row_sub['p_sub_id'].'",
            "p_parts_name":"'.$row_sub['p_parts_name'].'",
            "p_parts_status":"'.$p_parts_status.'",
        },';
    }
    $data_parts .= ']';
?>
<div class="form-container bg-lightgray" height="536" width="800" uuid="">
    <form id="form_editpulloutlog"> 
        <input name="p_id" type="hidden" value="<?=$data[0]["p_id"];?>" class="field-input-block-thin">
        <div class="col-lg-2">
            <span class="label-block">Pull-Out Date</span>
            <input disabled name="p_date" type="text" value="<?=formatdate($data[0]["p_date"]);?>" class="field_readonly_block">
        </div>
        <div class="col-lg-2">
            <span class="label-block">Status</span>
            <input disabled name="" type="text" value="<?=$status;?>" class="field_readonly_block">
        </div>
        <div class="col-lg-2">
            <span class="label-block">Redeem Date</span>
            <input disabled name="p_redeem_date" type="text" value="<?=formatdate($data[0]["p_redeem_date"]);?>" class="field_readonly_block">
        </div>
        <div class="col-lg-2">
            <span class="label-block">Redeem Fee</span>
            <input disabled name="p_redeem_fee" type="text" value="<?=formatdate($data[0]["p_redeem_fee"]);?>" class="field_readonly_block">
        </div>
        <div class="col-lg-2">
            <span class="label-block">Repo Date</span>
            <input disabled name="p_repo_date" type="text" value="<?=formatdate($data[0]["p_repo_date"]);?>" class="field_readonly_block">
        </div>
        <div class="col-lg-2">
            <span class="label-block">Sales No.</span>
            <input disabled name="" type="text" value="<?=$data[0]["p_sales_id"];?>" class="field_readonly_block">
        </div>
        <div class="col-lg-2">
            <span class="label-block">Stock No.</span>
            <input disabled name="" type="text" value="<?=get_stockcode($data[0]["p_product_id"]).'-'.$data[0]["p_product_id"];?>" class="field_readonly_block">
        </div>
        <div class="col-lg-5">
            <span class="label-block">Product</span>
            <input disabled name="" type="text" value="<?=$product;?>" class="field_readonly_block">
        </div>
        <div class="col-lg-3">
            <span class="label-block">Sales Branch</span>
            <input disabled name="" type="text" value="<?=$data[0]["branch_name"];?>" class="field_readonly_block">
        </div>
        <div class="col-lg-2">
            <span class="label-block">Customer No.</span>
            <input disabled name="" type="text" value="C<?=$data[0]["c_id"];?>" class="field_readonly_block">
        </div>
        <div class="col-lg-6">
            <span class="label-block">Customer</span>
            <input disabled name="" type="text" value="<?=$data[0]["customer_name"];?>" class="field_readonly_block">
        </div>
        <div class="col-lg-6">
            <span class="label-block">Area</span>
            <input disabled name="" type="text" value="<?=$data[0]["area"];?>" class="field_readonly_block">
        </div>
        <div class="col-lg-3">
            <span class="label-block">Attested By</span>
            <select disabled name="p_attestedby_id" id="p_attestedby_id" class="field_readonly_block">
            <?php
                echo "<option value=''></option>";
                $query = "SELECT * FROM employees WHERE e_is_deleted='N' ORDER BY e_lastname ASC, e_firstname ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    if($data[0]["p_attestedby_id"]==$row['e_id']) {
                        echo "<option value='".$row['e_id']."' selected>".$row['e_lastname'].",".$row['e_firstname']."</option>";
                    } else {
                        echo "<option value='".$row['e_id']."'>".$row['e_lastname'].",".$row['e_firstname']."</option>";
                    }
                }
            ?>
            </select>
        </div>   
        <div class="col-lg-3">
            <span class="label-block">Inventory By</span>
            <select disabled name="p_inventoryby_id" id="p_inventoryby_id" class="field_readonly_block">
            <?php
                echo "<option value=''></option>";
                $query = "SELECT * FROM employees WHERE e_is_deleted='N' ORDER BY e_lastname ASC, e_firstname ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    if($data[0]["p_inventoryby_id"]==$row['e_id']) {
                        echo "<option value='".$row['e_id']."' selected>".$row['e_lastname'].",".$row['e_firstname']."</option>";
                    } else {
                        echo "<option value='".$row['e_id']."'>".$row['e_lastname'].",".$row['e_firstname']."</option>";
                    }
                }
            ?>
            </select>
        </div>  
        <div class="col-lg-3">
            <span class="label-block">Checked By</span>
            <select disabled name="p_checkedby_id" id="p_checkedby_id" class="field_readonly_block">
            <?php
                echo "<option value=''></option>";
                $query = "SELECT * FROM employees WHERE e_is_deleted='N' ORDER BY e_lastname ASC, e_firstname ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    if($data[0]["p_checkedby_id"]==$row['e_id']) {
                        echo "<option value='".$row['e_id']."' selected>".$row['e_lastname'].",".$row['e_firstname']."</option>";
                    } else {
                        echo "<option value='".$row['e_id']."'>".$row['e_lastname'].",".$row['e_firstname']."</option>";
                    }
                }
            ?>
            </select>
        </div>  
        <div class="col-lg-3">
            <span class="label-block">Recieved By</span>
            <select disabled name="p_recievedby_id" id="p_recievedby_id" class="field_readonly_block">
            <?php
                echo "<option value=''></option>";
                $query = "SELECT * FROM employees WHERE e_is_deleted='N' ORDER BY e_lastname ASC, e_firstname ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    if($data[0]["p_recievedby_id"]==$row['e_id']) {
                        echo "<option value='".$row['e_id']."' selected>".$row['e_lastname'].",".$row['e_firstname']."</option>";
                    } else {
                        echo "<option value='".$row['e_id']."'>".$row['e_lastname'].",".$row['e_firstname']."</option>";
                    }
                }
            ?>
            </select>
        </div> 
        <div class="col-lg-12">
            <div id="dbgrid-tranferitems" class="datagrid-sub"></div>
        </div>         
        <div class="col-lg-12">
            <span class="label-block">Notes</span>
            <input disabled name="p_notes" type="text" value="<?=$data[0]["p_notes"];?>" class="field_readonly_block">
        </div> 
    </form>
</div>
<div class="form-footer">
    <button class="form-button"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
    <button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
</div>
<script>
    $('input[name="p_date"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
    $("#dbgrid-tranferitems").jsGrid({
        height: "160px",
        width: "756px",
        filtering: false,
        editing: false,
        sorting: true,
        data: <?php echo $data_parts; ?>,
        fields: [           
            {name:"p_parts_name", type:"text", width:"300px", title:"PARTS NAME", align:"left"},
            {name:"p_parts_status", type:"text", width:"200px", title:"STATUS", align:"left"},
        ]
    });
    $('input[name="t_request_date"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
</script>