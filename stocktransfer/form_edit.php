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

    $status = "";
    $data = array();
    $query_main = "SELECT * FROM stocktransfer_main WHERE t_id=".$_GET['id'];
    $recordset_main = mysql_query($query_main) or die('Query failed: ' . mysql_error());
    while($row_main = mysql_fetch_assoc($recordset_main)){
        $data[] = $row_main;
        if($row_main['t_verify_date'] != "") {
            $status = "Verified";
        } else {
            $status = "Pending";
        }
    } 

    $query = "SELECT *, 
                (SELECT p_code FROM products WHERE products.p_id=(SELECT p_product_id FROM inventory WHERE inventory.p_id=stocktransfer_sub.p_id)) AS p_code,
                (SELECT p_name FROM products WHERE products.p_id=(SELECT p_product_id FROM inventory WHERE inventory.p_id=stocktransfer_sub.p_id)) AS p_name                FROM stocktransfer_sub WHERE t_id=".$_GET['id'];
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    $data_tranferitems = '[';
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $data_tranferitems .= '{
            "t_sub_id":"'.$row['t_sub_id'].'",
            "t_id":"'.$row['t_id'].'",
            "s_no":"'.get_stockcode($row["p_id"]).'-'.$row['p_id'].'",
            "p_id":"'.$row['p_id'].'",
            "p_qty":"'.$row['p_qty'].'",
            "p_code":"'.$row['p_code'].'",
            "p_name":"'.$row['p_name'].'",
        },';
    }
    $data_tranferitems .= ']';  
?>
<div class="form-container bg-lightgray" height="536" width="800" uuid="">
    <form id="form_newtransferrequest"> 
        <input name="t_id" type="hidden" value="<?=$data[0]["t_id"];?>" class="field-input-block-thin">
        <div class="col-lg-4">
            <span class="label-block">Request Date</span>
            <input name="t_request_date" type="text" value="<?=formatdate($data[0]["t_request_date"]);?>" class="field-input-block-thin">
        </div>
        <div class="col-lg-4">
            <span class="label-block">Requested By</span>
            <select disabled name="t_requestby_id" id="t_requestby_id" class="field_readonly_block">
            <?php
                echo "<option value=''></option>";
                $query = "SELECT * FROM employees WHERE e_is_deleted='N' ORDER BY e_lastname ASC, e_firstname ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    if($data[0]["t_requestby_id"]==$row['e_id']) {
                        echo "<option value='".$row['e_id']."' selected>".$row['e_lastname'].",".$row['e_firstname']."</option>";
                    } else {
                        echo "<option value='".$row['e_id']."'>".$row['e_lastname'].",".$row['e_firstname']."</option>";
                    }
                }
            ?>
            </select>
        </div>   
        <div class="col-lg-4">
            <span class="label-block">Status</span>
            <input disabled name="t_status" type="text" value="<?=$status;?>" class="field_readonly_block">
        </div>
        <div class="col-lg-6">
            <span class="label-block">Origination</span>
            <select disabled name="t_branch_origin_id" id="t_branch_origin_id" class="field_readonly_block">
            <?php
                echo "<option value='' selected></option>";
                $query = "SELECT * FROM branches ORDER BY b_name ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    if($data[0]["t_branch_origin_id"]==$row['b_id']){
                        echo "<option value='".$row['b_id']."' selected>".$row['b_name']."</option>";   
                    } else {
                        echo "<option value='".$row['b_id']."'>".$row['b_name']."</option>";   
                    }
                }
            ?>
            </select>
        </div> 
        <div class="col-lg-6">
            <span class="label-block">Destination</span>
            <select name="t_branch_destination_id" id="t_branch_destination_id" class="field-input-block-thin">
            <?php
                echo "<option value='' selected></option>";
                $query = "SELECT * FROM branches ORDER BY b_name ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                   if($data[0]["t_branch_destination_id"]==$row['b_id']){
                        echo "<option value='".$row['b_id']."' selected>".$row['b_name']."</option>";   
                    } else {
                        echo "<option value='".$row['b_id']."'>".$row['b_name']."</option>";   
                    }                
                }
            ?>
            </select>
        </div>
        <div class="col-lg-12">
            <span class="label-block">Notes</span>
            <textarea name="t_notes" rows="4" cols="50" class="textarea-remarks-input"><?=$data[0]["t_notes"];?></textarea>
        </div> 
        <div class="col-lg-12">
            <div id="dbgrid-tranferitems" class="datagrid-sub"></div>
        </div> 

    </form>
</div>
<div class="form-footer">
    <button closewindow="off" class="form-button transfer_add_item"><i class="fa fa-plus" aria-hidden="true"></i> Add Item</button>
    <button closewindow="off" class="form-button transfer_delete_item"><i class="fa fa-trash" aria-hidden="true"></i> Delete Item</button>&nbsp;&nbsp;
    <button closewindow="off" class="form-button transfer_update"><i class="fa fa-check" aria-hidden="true"></i> Update</button>
    <button class="form-button close-stocktransfer-editform"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
</div>
<script>
    $("#dbgrid-tranferitems").jsGrid({
        height: "200px",
        width: "756px",
        filtering: false,
        editing: false,
        sorting: true,
        data: <?php echo $data_tranferitems; ?>,
        fields: [
            {   
                title:"", align:"left",width:"20px", align:"center", sorting:false,
                itemTemplate: function(value,e) { return $("<div>").append('<div><input type="radio" name="stock_selected" value="'+e.t_sub_id+'"></div>'); },
            },            
            {name:"s_no", type:"text", width:"50px", title:"STOCKNO", align:"left"},
            {name:"p_code", type:"text", width:"100px", title:"CODE", align:"left"},
            {name:"p_name", type:"text", width:"200px", title:"PRODUCT", align:"left"},
            {name:"p_qty", type:"text", width:"50px", title:"QTY", align:"center"},
        ]
    });
    $('input[name="t_request_date"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
</script>