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
    $query = "SELECT *,
                (SELECT v_tradename FROM vendors WHERE vendors.v_id=(SELECT p_vendor_id FROM po_main WHERE inventory.p_po_id=po_main.p_id) ) AS p_vendor,
                (SELECT p_code FROM products WHERE products.p_id=inventory.p_product_id) AS p_product_code,
                (SELECT p_name FROM products WHERE products.p_id=inventory.p_product_id) AS p_product_name,
                (SELECT i_name FROM inv_category WHERE inv_category.i_id=(SELECT p_category_id FROM products WHERE products.p_id=inventory.p_product_id)) AS p_category,
                (SELECT i_name FROM inv_category WHERE inv_category.i_id=((SELECT i_parent_id FROM inv_category WHERE inv_category.i_id=(SELECT p_category_id FROM products WHERE products.p_id=inventory.p_product_id)))) AS p_category_parent,
                (SELECT b_name FROM branches WHERE branches.b_id=inventory.p_branch_id) AS p_branch,
                IF(p_status = 'D','Damaged','Lost') AS p_status
                FROM inventory WHERE p_id=".$_GET['id'];
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while($row = mysql_fetch_assoc($recordset)){
        $data[] = $row;
        $p_product_id = $row['p_product_id'];
    } 

    $query = "SELECT * FROM products WHERE p_id=".$p_product_id;
    $recordset_property = mysql_query($query) or die('Query failed: ' . mysql_error());
    $data_property = '[';
    while ($row_property = mysql_fetch_array($recordset_property, MYSQL_ASSOC)) {
        if($row_property['p_property_1'] != "") {
            $data_property .= '{"property_name":"'.$row_property['p_property_1'].'","property_value":"'.$data[0]['p_property_1'].'",},';
        }
        if($row_property['p_property_2'] != "") {
            $data_property .= '{"property_name":"'.$row_property['p_property_2'].'","property_value":"'.$data[0]['p_property_2'].'",},';
        }
        if($row_property['p_property_3'] != "") {
            $data_property .= '{"property_name":"'.$row_property['p_property_3'].'","property_value":"'.$data[0]['p_property_3'].'",},';
        }
        if($row_property['p_property_4'] != "") {
            $data_property .= '{"property_name":"'.$row_property['p_property_4'].'","property_value":"'.$data[0]['p_property_4'].'",},';
        }
        if($row_property['p_property_5'] != "") {
            $data_property .= '{"property_name":"'.$row_property['p_property_5'].'","property_value":"'.$data[0]['p_property_5'].'",},';
        }
        if($row_property['p_property_6'] != "") {
            $data_property .= '{"property_name":"'.$row_property['p_property_6'].'","property_value":"'.$data[0]['p_property_6'].'",},';
        }
        if($row_property['p_property_7'] != "") {
            $data_property .= '{"property_name":"'.$row_property['p_property_7'].'","property_value":"'.$data[0]['p_property_7'].'",},';
        }
    }
    $data_property .= ']';  
?>
<div class="form-container bg-lightgray" height="620" width="800" uuid="">
    <div class="col-lg-6 thin-row">
        <div class="col-lg-12 thin-row">
            <div class="col-lg-4 text-label">Received Date</div>
            <div class="col-lg-8"><input disabled name="" type="text" value="<?=date('m/d/Y',strtotime($data[0]["p_recieved_date"]));?>" class="field_readonly_block"></div>
        </div>
        <div class="col-lg-12 thin-row">
            <div class="col-lg-4 text-label">Stock No.</div>
            <div class="col-lg-8"><input disabled name="" type="text" value="<?=get_stockcode($data[0]["p_id"]).'-'.$data[0]["p_id"];?>" class="field_readonly_block"></div>
        </div>
        <div class="col-lg-12 thin-row">
            <div class="col-lg-4 text-label">Product</div>
            <div class="col-lg-8"><input disabled name="" type="text" value="<?=$data[0]["p_product_code"].' / '.$data[0]["p_product_name"];?>" class="field_readonly_block"></div>
        </div>
        <div class="col-lg-12 thin-row">
            <div class="col-lg-4 text-label">Category</div>
            <div class="col-lg-8"><input disabled name="" type="text" value="<?=$data[0]["p_category"].' / '.$data[0]["p_category_parent"];?>" class="field_readonly_block"></div>
        </div>
        <div class="col-lg-12 thin-row">
            <div class="col-lg-4 text-label">Branch</div>
            <div class="col-lg-8"><input disabled name="" type="text" value="<?=$data[0]["p_branch"];?>" class="field_readonly_block"></div>
        </div>
        <div class="col-lg-12 thin-row">
            <div class="col-lg-4 text-label">Selling Price</div>
            <div class="col-lg-8"><input disabled name="" type="text" value="<?=number_format($data[0]["p_selling_price"],2,".",",");?>" class="field_readonly_block"></div>
        </div>
    </div>
    <div class="col-lg-6 thin-row">
        <div class="col-lg-12 thin-row">
            <div class="col-lg-4 text-label">Purchase Order</div>
            <div class="col-lg-8"><input disabled name="" type="text" value="P<?=$data[0]["p_po_id"];?>" class="field_readonly_block"></div>
        </div>
        <div class="col-lg-12 thin-row">
            <div class="col-lg-4 text-label">Status</div>
            <div class="col-lg-8"><input disabled name="" type="text" value="<?=$data[0]["p_status"];?>" class="field_readonly_block"></div>
        </div>    
        <div class="col-lg-12 thin-row">
            <div class="col-lg-4 text-label">Supplier</div>
            <div class="col-lg-8"><input disabled name="" type="text" value="<?=$data[0]["p_vendor"];?>" class="field_readonly_block"></div>
        </div>  
        <div class="col-lg-12 thin-row">
            <div class="col-lg-4 text-label">Purchase Price</div>
            <div class="col-lg-8"><input disabled name="" type="text" value="<?=number_format($data[0]["p_purchase_cost"],2,".",",");?>" class="field_readonly_block"></div>
        </div>
        <div class="col-lg-12 thin-row">
            <div class="col-lg-4 text-label">Quantity</div>
            <div class="col-lg-8"><input disabled name="" type="text" value="<?=$data[0]["p_qty"];?>" class="field_readonly_block"></div>
        </div>    
        <div class="col-lg-12 thin-row">
            <div class="col-lg-4 text-label">Reference</div>
            <div class="col-lg-8"><input disabled name="" type="text" value="<?=$data[0]["p_reference"];?>" class="field_readonly_block"></div>
        </div> 
    </div>
    <div class="col-lg-12">
        <div class="col-lg-12">
            <textarea disabled="" name="" rows="4" cols="50" class="textarea-remarks-readonly"><?=$data[0]["p_remarks"];?></textarea>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="col-lg-12">
            <div id="dbgrid_property" class="dbgrid-property"></div>    
        </div>
    </div>
</div>
<div class="form-footer">
    <button closewindow="off" class="form-button stock_print"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
    <button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
</div>

<script>
    $("#dbgrid_property").jsGrid({
        height: "180px",
        width: "747px",
        filtering: false,
        editing: false,
        sorting: true,
        paging: true,
        autoload: true,
        pageSize: 15,
        pageButtonCount: 15,
        data: <?php echo $data_property; ?>,
        fields: [
            {   
                title:"Property", align:"left",width:"30%", align:"left", sorting:false,
                itemTemplate: function(value,e) { return $("<div>").append('<div>'+e.property_name+'</div>'); },
            }, 
            {   
                title:"Value", align:"left",width:"70%", align:"left", sorting:false,
                itemTemplate: function(value,e) { return $("<div>").append('<div>'+e.property_value+'</div>'); },
            },          
        ]
    });
</script>

