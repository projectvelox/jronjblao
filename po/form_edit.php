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

    $total_amount = 0;
    $data = array();
    $query_main = "SELECT * FROM po_main WHERE p_id=".$_GET['id'];
    $recordset_main = mysql_query($query_main) or die('Query failed: ' . mysql_error());
    while($row_main = mysql_fetch_assoc($recordset_main)){
        $data[] = $row_main;
        $total_amount = $total_amount + (($row_main['p_charge_tax']+$row_main['p_charge_shipping']+$row_main['p_charge_others'])-$row_main['p_discount']);
    } 
    $data_poitems = '[';
    $query_sub = "SELECT *,
                (SELECT p_code FROM products WHERE products.p_id=po_sub.p_product_id) AS p_code,
                (SELECT p_name FROM products WHERE products.p_id=po_sub.p_product_id) AS p_name
              FROM po_sub WHERE p_id=".$_GET['id'];    
    $recordset_sub = mysql_query($query_sub) or die('Query failed: ' . mysql_error());
    $totalcost_products = 0;
    while ($row_sub = mysql_fetch_array($recordset_sub, MYSQL_ASSOC)) {
        $total_amount = $total_amount + ( $row_sub['p_itemprice'] * $row_sub['p_qty'] );
        $p_total = $row_sub['p_itemprice'] * $row_sub['p_qty'];
        $totalcost_products = $totalcost_products + $p_total;
        $data_poitems .= '{
            "p_sub_id":"'.$row_sub['p_sub_id'].'",
            "p_product_id":"'.$row_sub['p_product_id'].'",
            "p_qty":"'.number_format($row_sub['p_qty'],0,".",",").'",
            "p_itemprice":"'.number_format($row_sub['p_itemprice'],2,".",",").'",
            "p_total":"'.number_format($p_total,2,".",",").'",
            "p_code":"'.$row_sub['p_code'].'",
            "p_name":"'.$row_sub['p_name'].'",
        },';
    }  
    $data_poitems .= ']';
?>
<div class="form-container bg-lightgray" height="643" width="800" uuid="">
    <form id="form_po"> 
        <input name="p_id" type="hidden" value="<?=$data[0]["p_id"];?>" class="field-input-block-thin">
        <div class="col-lg-2">
            <span class="label-block">PO No.</span>
            <input disabled name="" type="text" value="P<?=$data[0]["p_id"];?>" class="field_readonly_block">
        </div>
        <div class="col-lg-2">
            <span class="label-block">Request Date</span>
            <input name="p_requestdate" type="text" value="<?=formatdate($data[0]["p_requestdate"]);?>" class="field-input-block-thin">
        </div>
        <div class="col-lg-2">
            <span class="label-block">Order Date</span>
            <input name="p_orderdate" type="text" value="<?=formatdate($data[0]["p_orderdate"]);?>" class="field-input-block-thin">
        </div>      
        <div class="col-lg-2">
            <span class="label-block">Recieved Date</span>
            <input disabled name="" type="text" value="<?=formatdate($data[0]["p_recieveddate"]);?>" class="field_readonly_block">
        </div>  
        <div class="col-lg-2">
            <span class="label-block">Invoice</span>
            <input name="p_invoice" type="text" value="<?=$data[0]["p_invoice"];?>" class="field-input-block-thin">
        </div> 
        <div class="col-lg-2">
            <span class="label-block">Status</span>
            <input disabled name="p_status" type="text" value="<?=po_status($data[0]["p_status"]);?>" class="field_readonly_block">
        </div> 
        <div class="col-lg-4">
            <span class="label-block">Branch</span>
            <select name="p_branch_id" id="p_branch_id" class="field-input-block-thin">
            <?php
                echo "<option value=''></option>";
                $query = "SELECT * FROM branches ORDER BY b_name ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    if($data[0]['p_branch_id']==$row['b_id']) {
                        echo "<option value='".$row['b_id']."' selected>".$row['b_name']."</option>";
                    } else {
                        echo "<option value='".$row['b_id']."'>".$row['b_name']."</option>";   
                    }
                }
            ?>
            </select>
        </div> 
        <div class="col-lg-4">
            <span class="label-block">Vendor</span>
            <select name="p_vendor_id" id="p_vendor_id" class="field-input-block-thin">
            <?php
                echo "<option value=''></option>";
                $query = "SELECT v_id,v_tradename FROM vendors ORDER BY v_tradename ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    if($data[0]['p_vendor_id']==$row['v_id']) {
                        echo "<option value='".$row['v_id']."' selected>".$row['v_tradename']."</option>";
                    } else {
                        echo "<option value='".$row['v_id']."'>".$row['v_tradename']."</option>";   
                    }
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
                    if($data[0]['p_shippingmethod_id']==$row['c_id']) {
                        echo "<option value='".$row['c_id']."' selected>".$row['c_name']."</option>";
                    } else {
                        echo "<option value='".$row['c_id']."'>".$row['c_name']."</option>";   
                    }
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
                    if($data[0]['p_paymentmethod_id']==$row['c_id']) {
                        echo "<option value='".$row['c_id']."' selected>".$row['c_name']."</option>";
                    } else {
                        echo "<option value='".$row['c_id']."'>".$row['c_name']."</option>";   
                    }
                }
            ?>
            </select>      
        </div> 
        <div class="col-lg-4">
            <span class="label-block">Requested By</span>
            <select disabled name="p_requestedby_id" id="p_requestedby_id" class="field_readonly_block">
            <?php
                echo "<option value=''></option>";
                $query = "SELECT * FROM employees WHERE e_is_deleted='N' ORDER BY e_lastname ASC, e_firstname ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    if($data[0]['p_requestedby_id']==$row['e_id']) {
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
                    if($data[0]['p_verifiedby_id']==$row['e_id']) {
                        echo "<option value='".$row['e_id']."' selected>".$row['e_lastname'].",".$row['e_firstname']."</option>";
                    } else {
                        echo "<option value='".$row['e_id']."'>".$row['e_lastname'].",".$row['e_firstname']."</option>";
                    }
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
                    if($data[0]['p_approvedby_id']==$row['e_id']) {
                        echo "<option value='".$row['e_id']."' selected>".$row['e_lastname'].",".$row['e_firstname']."</option>";
                    } else {
                        echo "<option value='".$row['e_id']."'>".$row['e_lastname'].",".$row['e_firstname']."</option>";
                    }
                }
            ?>
            </select>
        </div> 
        <div class="col-lg-12">
            <div id="dbgrid-poitems" class="datagrid-sub"></div>
        </div> 
        <div class="col-lg-8">
            <span class="label-block">Notes</span>
            <textarea name="p_notes" rows="4" cols="50" class="textarea-po-input"><?=$data[0]["p_notes"];?></textarea>
        </div> 
        <div class="col-lg-4">
            <div class="col-lg-5"><span class="label-currency">Tax</span></div>
            <div class="col-lg-7">
                <input name="p_charge_tax" type="text" value="<?=number_format($data[0]["p_charge_tax"],2,".",",");?>" class="field-input-block-thin align-right p_charge_tax">
            </div>
            <div class="col-lg-5"><span class="label-currency">Shipping</span></div>
            <div class="col-lg-7">
                <input name="p_charge_shipping" type="text" value="<?=number_format($data[0]["p_charge_shipping"],2,".",",");?>" class="field-input-block-thin align-right p_charge_shipping">
            </div>
            <div class="col-lg-5"><span class="label-currency">Other Charges</span></div>
            <div class="col-lg-7">
                <input name="p_charge_others" type="text" value="<?=number_format($data[0]["p_charge_others"],2,".",",");?>" class="field-input-block-thin align-right p_charge_others">
            </div>
            <div class="col-lg-5"><span class="label-currency">Discount</span></div>
            <div class="col-lg-7">
                <input name="p_discount" type="text" value="<?=number_format($data[0]["p_discount"],2,".",",");?>" class="field-input-block-thin align-right p_discount">
            </div>
            <div class="col-lg-5"><span class="label-currency">Total</span></div>
            <div class="col-lg-7">
                <input name="totalcost_products" type="hidden" value="<?=$totalcost_products;?>" class="">
                <input disabled name="totalamount" type="text" value="<?=number_format($total_amount,2,".",",");?>" class="field_readonly_block_currency">
            </div>
        </div>
    </form>
</div>
<div class="form-footer">
    <button closewindow="off" class="form-button po_add_item"><i class="fa fa-plus" aria-hidden="true"></i> Add Item</button>
    <button closewindow="off" class="form-button po_edit_item"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Item</button>
    <button closewindow="off" class="form-button po_delete_item"><i class="fa fa-trash" aria-hidden="true"></i> Delete Item</button>&nbsp;&nbsp;
    <button closewindow="off" class="form-button po_save"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
    <button closewindow="off" class="form-button po_delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
    <button closewindow="off" class="form-button close-poform"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
</div>

<script>
    $("#dbgrid-poitems").jsGrid({
        height: "200px",
        width: "756px",
        filtering: false,
        editing: false,
        sorting: true,
        data: <?php echo $data_poitems; ?>,
        fields: [
            {   
                title:"", align:"left",width:30, align:"center", sorting:false,
                itemTemplate: function(value,e) { return $("<div>").append('<div><input type="radio" name="record_selected" value="'+e.p_sub_id+'"></div>'); },
            },            
            {name:"p_code", type:"text", width:"100px", title:"CODE", align:"left"},
            {name:"p_name", type:"text", width:"200px", title:"PRODUCT", align:"left"},
            {name:"p_qty", type:"text", width:"100px", title:"QTY", align:"right"},
            {name:"p_itemprice", type:"text", width:"100px", title:"UNIT PRICE", align:"right"},
            {name:"p_total", type:"text", width:"100px", title:"TOTAL", align:"right"},
        ]
    });
    $('input[name="p_requestdate"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
    $('input[name="p_orderdate"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
</script>