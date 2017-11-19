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
?>
<div class="form-container bg-lightgray" height="265" width="800" uuid="">
    <form id="form_po"> 
        <div class="col-lg-2">
            <span class="label-block">PO No.</span>
            <input disabled name="" type="text" value="(auto)" class="field_readonly_block">
        </div>
        <div class="col-lg-2">
            <span class="label-block">Request Date</span>
            <input name="p_requestdate" type="text" value="" class="field-input-block-thin">
        </div>
        <div class="col-lg-2">
            <span class="label-block">Order Date</span>
            <input name="p_orderdate" type="text" value="" class="field-input-block-thin">
        </div>      
        <div class="col-lg-2">
            <span class="label-block">Recieved Date</span>
            <input disabled name="" type="text" value="" class="field_readonly_block">
        </div>  
        <div class="col-lg-2">
            <span class="label-block">Invoice</span>
            <input name="p_invoice" type="text" value="" class="field-input-block-thin">
        </div> 
        <div class="col-lg-2">
            <span class="label-block">Status</span>
            <input disabled name="p_status" type="text" value="Draft" class="field_readonly_block">
        </div> 
        <div class="col-lg-4">
            <span class="label-block">Branch</span>
            <select name="p_branch_id" id="p_branch_id" class="field-input-block-thin">
            <?php
                echo "<option value='' selected></option>";
                $query = "SELECT * FROM branches ORDER BY b_name ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    echo "<option value='".$row['b_id']."'>".$row['b_name']."</option>";   
                }
            ?>
            </select>
        </div> 
        <div class="col-lg-4">
            <span class="label-block">Vendor</span>
            <select name="p_vendor_id" id="p_vendor_id" class="field-input-block-thin">
            <?php
                echo "<option value='' selected></option>";
                $query = "SELECT v_id,v_tradename FROM vendors ORDER BY v_tradename ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    echo "<option value='".$row['v_id']."'>".$row['v_tradename']."</option>";   
                }
            ?>
            </select>
        </div> 
        <div class="col-lg-2">
            <span class="label-block">Shipping</span>   
            <select name="p_shippingmethod_id" id="p_shippingmethod_id" class="field-input-block-thin">
            <?php
                echo "<option value='' selected></option>";
                $query = "SELECT * FROM shippingmethod ORDER BY c_name ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    echo "<option value='".$row['c_id']."'>".$row['c_name']."</option>";   
                }
            ?>
            </select>
        </div> 
        <div class="col-lg-2">
            <span class="label-block">Payment</span>
            <select name="p_paymentmethod_id" id="p_paymentmethod_id" class="field-input-block-thin">
            <?php
                echo "<option value='' selected></option>";
                $query = "SELECT * FROM paymenttype ORDER BY c_name ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    echo "<option value='".$row['c_id']."'>".$row['c_name']."</option>";   
                }
            ?>
            </select>      
        </div> 
        <div class="col-lg-4">
            <span class="label-block">Requested By</span>
            <select name="p_requestedby_id" id="p_requestedby_id" class="field-input-block-thin">
            <?php
                echo "<option value=''></option>";
                $query = "SELECT * FROM employees WHERE e_is_deleted='N' ORDER BY e_lastname ASC, e_firstname ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    if($_SESSION['user_id']==$row['e_id']) {
                        echo "<option value='".$row['e_id']."' selected>".$row['e_lastname'].",".$row['e_firstname']."</option>";
                    } else {
                        echo "<option value='".$row['e_id']."'>".$row['e_lastname'].",".$row['e_firstname']."</option>";
                    }
                }
            ?>
            </select>
        </div> 
        <div class="col-lg-4">
            <span class="label-block">Verified By</span>
            <select disabled name="p_verifiedby_id" id="p_verifiedby_id" class="field_readonly_block">
            <?php
                echo "<option value='' selected></option>";
                $query = "SELECT * FROM employees WHERE e_is_deleted='N' ORDER BY e_lastname ASC, e_firstname ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    echo "<option value='".$row['e_id']."'>".$row['e_lastname'].",".$row['e_firstname']."</option>";
                }
            ?>
            </select>
        </div> 
        <div class="col-lg-4">
            <span class="label-block">Approved By</span>
            <select disabled name="p_approvedby_id" id="p_approvedby_id" class="field_readonly_block">
            <?php
                echo "<option value='' selected></option>";
                $query = "SELECT * FROM employees WHERE e_is_deleted='N' ORDER BY e_lastname ASC, e_firstname ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    echo "<option value='".$row['e_id']."'>".$row['e_lastname'].",".$row['e_firstname']."</option>";
                }
            ?>
            </select>
        </div> 
    </form>
</div>
<div class="form-footer">
    <button closewindow="off" class="form-button po_create_save"><i class="fa fa-plus" aria-hidden="true"></i> Create</button>
    <button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
</div>
<script>
    $('input[name="p_requestdate"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
    $('input[name="p_orderdate"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
</script>