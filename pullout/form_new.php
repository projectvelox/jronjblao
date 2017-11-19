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

    $data = '[';
    $query = "SELECT *,
                    (SELECT p_code FROM products WHERE products.p_id=inventory.p_product_id) AS p_code,
                    (SELECT p_name FROM products WHERE products.p_id=inventory.p_product_id) AS p_name,
                    (SELECT i_name FROM inv_category WHERE inv_category.i_id=(SELECT p_category_id FROM products WHERE products.p_id=inventory.p_product_id)) AS p_category
                FROM inventory
                WHERE 
                    (SELECT i_parent_id FROM inv_category WHERE inv_category.i_id=(SELECT p_category_id FROM products WHERE products.p_id=inventory.p_product_id)) <> 3
                AND p_branch_id=".$_SESSION['branch_id']." AND p_status='S' ORDER BY p_id ASC";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {

        $customer = "";
        $sales_no = "";
        $query_sales = "SELECT * FROM sales_sub WHERE s_product_id=".$row['p_id']." LIMIT 1";
        $recordset_sales = mysql_query($query_sales) or die('Query failed: ' . mysql_error());
        while ($row_sales = mysql_fetch_array($recordset_sales, MYSQL_ASSOC)) {
            $sales_id = $row_sales['s_sales_id'];
        }    
        $query_customer = "SELECT  *,
                        (SELECT CONCAT(c_lastname,', ',c_firstname) FROM customers WHERE customers.c_id=sales_main.s_customer_id) AS s_customer
                        FROM sales_main WHERE s_id=".$sales_id;
        $recordset_customer = mysql_query($query_customer) or die('Query failed: ' . mysql_error());
        while ($row_customer = mysql_fetch_array($recordset_customer, MYSQL_ASSOC)) {
            $sales_no = "S".$row_customer['s_id'];
            $customer = 'C'.$row_customer['s_customer_id'].' / '.$row_customer['s_customer'];
        }

        $data .= '{
            "p_id":"'.$row['p_id'].'",
            "p_no":"'.get_stockcode($row["p_id"]).'-'.$row['p_id'].'",
            "p_code":"'.$row['p_code'].'",
            "p_name":"'.$row['p_name'].'",
            "product":"'.$row['p_code'].' - '.$row['p_name'].'",
            "p_category":"'.$row['p_category'].'",
            "p_sold_to":"'.$customer.'",
            "sales_no":"'.$sales_no.'",

        },';
    }
    $data .= ']'; 

?>
<div class="form-container bg-lightgray" height="531" width="835" uuid="">
    <form id="form_newpulloutlog"> 
        <div id="dbgrid-stocks" class="dbgrid-stocks"></div>
    </form>
</div>
<div class="form-footer">
    <button closewindow="off" class="form-button pullout_createe"><i class="fa fa-plus" aria-hidden="true"></i> Create</button>
    <button closewindow="off" class="form-button close-editform-pullout"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
</div>
<script>
    $("#dbgrid-stocks").jsGrid({
        height: "420px",
        width: "800px",
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
                itemTemplate: function(value,e) { return $("<div>").append('<div><input type="radio" name="selected-stock-record" value="'+e.p_id+'"></div>'); },
            },  
            {name:"p_no", type:"text", width:"80px", title:"STOCKNO", align:"left"},
            {name:"product", type:"text", width:"230px", title:"PRODUCT", align:"left"},
            {name:"p_sold_to", type:"text", width:"230px", title:"SOLD TO", align:"left"},
            {name:"sales_no", type:"text", width:"100px", title:"SALES_NO", align:"left"},
        ]
    });

    $('input[name="p_date"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
</script>