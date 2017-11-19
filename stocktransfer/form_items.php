<?php
    include "../includes/config.php";
    include "../includes/functions.php";
    include "../includes/functions.form.php";
    include "../includes/login_functions.php";
    include "../includes/session.php";

    $data = '[';
    $query = "SELECT *,
                    (SELECT p_code FROM products WHERE products.p_id=inventory.p_product_id) AS p_code,
                    (SELECT p_name FROM products WHERE products.p_id=inventory.p_product_id) AS p_name,
                    (SELECT i_name FROM inv_category WHERE inv_category.i_id=(SELECT p_category_id FROM products WHERE products.p_id=inventory.p_product_id)) AS p_category
                FROM inventory
                WHERE (SELECT i_parent_id FROM inv_category WHERE inv_category.i_id=(SELECT p_category_id FROM products WHERE products.p_id=inventory.p_product_id)) <> 3
                AND p_branch_id=".$_GET['branch_id']." AND p_status IN('W','I') AND p_istransfer='N' ORDER BY p_id ASC";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $data .= '{
            "p_id":"'.$row['p_id'].'",
            "p_no":"'.get_stockcode($row["p_id"]).'-'.$row['p_id'].'",
            "p_code":"'.$row['p_code'].'",
            "p_name":"'.$row['p_name'].'",
            "p_category":"'.$row['p_category'].'",
        },';
    }
    $data .= ']'; 
?>
<div class="form-container" height="500" width="900" uuid="">
    <form id="form-po-additem">   
        <input name="t_id" type="hidden" value="<?=$_GET['t_id'];?>" class="field-input-block">
        <input name="branch_id" type="hidden" value="<?=$_GET['branch_id'];?>" class="field-input-block">
        <div id="dbgrid-stocks" class="dbgrid-stocks"></div>
    </form>     
</div>
<div class="form-footer">
    <button closewindow="off" class="form-button transfer-itemsave"><i class="fa fa-plus" aria-hidden="true"></i> Add</button>
    <button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Cancel</button>       
 </div>
<script>
    $("#dbgrid-stocks").jsGrid({
        height: "385px",
        width: "865px",
        filtering: false,
        editing: false,
        sorting: true,
        paging: true,
        autoload: true,
        pageSize: 19,
        pageButtonCount: 15,
        data: <?php echo $data; ?>,
        fields: [
            {   
                title:"", align:"left",width:30, align:"center", sorting:false,
                itemTemplate: function(value,e) { return $("<div>").append('<div><input type="checkbox" name="selected-stock-record" value="'+e.p_id+'"></div>'); },
            },  
            {name:"p_no", type:"text", width:"100px", title:"STOCKNO", align:"left"},
            {name:"p_code", type:"text", width:"100px", title:"CODE", align:"left"},
            {name:"p_name", type:"text", width:"300px", title:"PRODUCT", align:"left"},
            {name:"p_category", type:"text", width:"200px", title:"CATEGORY", align:"left"},
        ]
    });
</script>