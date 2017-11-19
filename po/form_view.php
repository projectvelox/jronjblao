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
    $query_main = "SELECT *,
                (SELECT CONCAT(e_lastname,', ',e_firstname) FROM employees WHERE employees.e_id=po_main.p_requestedby_id) AS p_requestedby_name,
                (SELECT CONCAT(e_lastname,', ',e_firstname) FROM employees WHERE employees.e_id=po_main.p_verifiedby_id) AS p_verifiedby_name,
                (SELECT CONCAT(e_lastname,', ',e_firstname) FROM employees WHERE employees.e_id=po_main.p_approvedby_id) AS p_approvedby_name,
                (SELECT v_tradename FROM vendors WHERE vendors.V_id=po_main.p_vendor_id) AS p_vendor_name,
                (SELECT b_name FROM branches WHERE branches.b_id=po_main.p_branch_id) AS p_branch,
                (SELECT c_name FROM shippingmethod WHERE shippingmethod.c_id=po_main.p_shippingmethod_id) AS p_shipping,
                (SELECT c_name FROM paymenttype WHERE paymenttype.c_id=po_main.p_paymentmethod_id) AS p_paymentmethod
                FROM po_main WHERE p_id=".$_GET['id'];
    $recordset_main = mysql_query($query_main) or die('Query failed: ' . mysql_error());
    while($row_main = mysql_fetch_assoc($recordset_main)){
        $total_amount = $total_amount + (($row_main['p_charge_tax']+$row_main['p_charge_shipping']+$row_main['p_charge_others'])-$row_main['p_discount']);
        $data[] = $row_main;
    } 

    $data_poitems = '[';
    $query_sub = "SELECT *,
                (SELECT p_code FROM products WHERE products.p_id=po_sub.p_product_id) AS p_code,
                (SELECT p_name FROM products WHERE products.p_id=po_sub.p_product_id) AS p_name
              FROM po_sub WHERE p_id=".$_GET['id'];    
    $recordset_sub = mysql_query($query_sub) or die('Query failed: ' . mysql_error());
    while ($row_sub = mysql_fetch_array($recordset_sub, MYSQL_ASSOC)) {
        $total_amount = $total_amount + ( $row_sub['p_itemprice'] * $row_sub['p_qty'] );
        $p_total = $row_sub['p_itemprice'] * $row_sub['p_qty'];
        $data_poitems .= '{
            "p_sub_id":"'.$row_sub['p_sub_id'].'",
            "p_product_id":"'.$row_sub['p_product_id'].'",
            "p_qty":"'.number_format($row_sub['p_qty'],0,".",",").'",
            "p_itemprice":"'.number_format($row_sub['p_itemprice'],2,".",",").'",
            "p_total":"'.number_format($p_total,2,".",",").'",
            "p_code":"'.$row_sub['p_code'].'",
            "p_name":"'.$row_sub['p_name'].'",
            "p_is_recieved":"'.$row_sub['p_is_recieved'].'",
        },';
    }  
    $data_poitems .= ']';


?>
<div class="form-container bg-lightgray" height="635" width="800" uuid="">
    <div class="col-lg-2">
        <span class="label-block">PO No.</span>
        <input disabled name="" type="text" value="P<?=$data[0]["p_id"];?>" class="field_readonly_block">
    </div>
    <div class="col-lg-2">
        <span class="label-block">Request Date</span>
        <input disabled name="" type="text" value="<?=formatdate($data[0]["p_requestdate"]);?>" class="field_readonly_block">
    </div>
    <div class="col-lg-2">
        <span class="label-block">Order Date</span>
        <input disabled name="" type="text" value="<?=formatdate($data[0]["p_orderdate"]);?>" class="field_readonly_block">
    </div>      
    <div class="col-lg-2">
        <span class="label-block">Recieved Date</span>
        <input disabled name="" type="text" value="<?=formatdate($data[0]["recieved_date"]);?>" class="field_readonly_block">
    </div>  
    <div class="col-lg-2">
        <span class="label-block">Invoice</span>
        <input disabled name="" type="text" value="<?=$data[0]["p_invoice"];?>" class="field_readonly_block">
    </div> 
    <div class="col-lg-2">
        <span class="label-block">Status</span>
        <input disabled name="" type="text" value="<?=po_status($data[0]["p_status"]);?>" class="field_readonly_block">
    </div> 
    <div class="col-lg-4">
        <span class="label-block">Branch</span>
        <input disabled name="" type="text" value="<?=$data[0]["p_branch"];?>" class="field_readonly_block">
    </div> 
    <div class="col-lg-4">
        <span class="label-block">Vendor</span>
        <input disabled name="" type="text" value="<?=$data[0]["p_vendor_name"];?>" class="field_readonly_block">
    </div> 
    <div class="col-lg-2">
        <span class="label-block">Shipping</span>
        <input disabled name="" type="text" value="<?=$data[0]["p_shipping"];?>" class="field_readonly_block">
    </div> 
    <div class="col-lg-2">
        <span class="label-block">Payment</span>
        <input disabled name="" type="text" value="<?=$data[0]["p_paymentmethod"];?>" class="field_readonly_block">
    </div> 
    <div class="col-lg-4">
        <span class="label-block">Requested By</span>
        <input disabled name="" type="text" value="<?=$data[0]["p_requestedby_name"];?>" class="field_readonly_block">
    </div> 
    <div class="col-lg-4">
        <span class="label-block">Verified By</span>
        <input disabled name="" type="text" value="<?=$data[0]["p_verifiedby_name"];?>" class="field_readonly_block">
    </div> 
    <div class="col-lg-4">
        <span class="label-block">Approved By</span>
        <input disabled name="" type="text" value="<?=$data[0]["p_approvedby_name"];?>" class="field_readonly_block">
    </div> 

    <div class="col-lg-12">
        <div id="dbgrid-poitems" class="datagrid-sub"></div>
    </div> 

    <div class="col-lg-8">
        <span class="label-block">Notes</span>
        <textarea disabled name="" rows="4" cols="50" class="textarea-po-readonly"><?=$data[0]["p_notes"];?></textarea>
    </div> 
    <div class="col-lg-4">
        <div class="col-lg-5"><span class="label-currency">Tax</span></div>
        <div class="col-lg-7">
            <input disabled name="" type="text" value="<?=number_format($data[0]["p_charge_tax"],2,".",",");?>" class="field_readonly_block_currency">
        </div>
        <div class="col-lg-5"><span class="label-currency">Shipping</span></div>
        <div class="col-lg-7">
            <input disabled name="" type="text" value="<?=number_format($data[0]["p_charge_shipping"],2,".",",");?>" class="field_readonly_block_currency">
        </div>
        <div class="col-lg-5"><span class="label-currency">Other Charges</span></div>
        <div class="col-lg-7">
            <input disabled name="" type="text" value="<?=number_format($data[0]["p_charge_others"],2,".",",");?>" class="field_readonly_block_currency">
        </div>
        <div class="col-lg-5"><span class="label-currency">Discount</span></div>
        <div class="col-lg-7">
            <input disabled name="" type="text" value="<?=number_format($data[0]["p_discount"],2,".",",");?>" class="field_readonly_block_currency">
        </div>
        <div class="col-lg-5"><span class="label-currency">Total</span></div>
        <div class="col-lg-7">
            <input disabled name="" type="text" value="<?=number_format($total_amount,2,".",",");?>" class="field_readonly_block_currency">
        </div>
    </div>
</div>
<div class="form-footer">
    <button closewindow="off" class="form-button po_print"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
    <button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
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
            {name:"p_code", type:"text", width:"80px", title:"CODE", align:"left"},
            {name:"p_name", type:"text", width:"200px", title:"PRODUCT", align:"left"},
            {name:"p_qty", type:"text", width:"80px", title:"QTY", align:"right"},
            {name:"p_itemprice", type:"text", width:"80px", title:"UNIT PRICE", align:"right"},
            {name:"p_total", type:"text", width:"80px", title:"TOTAL", align:"right"},
            {   
                title:"RECEIVED", align:"left",width:"70px", align:"center", sorting:false,
                itemTemplate: function(value,e) { 
                    if(e.p_is_recieved=="Y"){
                        return $("<div>").append('<i class="fa fa-check" aria-hidden="true"></i>'); 
                    } else {
                        return $("<div>").append('<button data-id="'+e.p_sub_id+'" closewindow="off" class="form-button-recieved">Recieve</button>'); 
                    }
                },
            },  

        ]
    });
</script>