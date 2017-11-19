<link type="text/css" rel="stylesheet" href="../css/jsgrid.min.css" />
<link type="text/css" rel="stylesheet" href="../css/jsgrid-theme.min.css" />
<script type="text/javascript" src="../js/jsgrid.min.js"></script>
<?php 
    include "../includes/config.php";
    
    $query_main = "SELECT 
                        *,
                        (SELECT s_customer_id FROM sales_main WHERE sales_main.s_customer_id=pullout_main.p_sales_id) AS customer_id,
                        (SELECT CONCAT(c_lastname,', ',c_firstname) FROM customers WHERE customers.c_id=(SELECT s_customer_id FROM sales_main WHERE sales_main.s_customer_id=pullout_main.p_sales_id)) AS customer_name,
                        (SELECT s_branch_id FROM sales_main WHERE sales_main.s_customer_id=pullout_main.p_sales_id) AS branch_id,
                        (SELECT b_name FROM branches WHERE branches.b_id=(SELECT s_branch_id FROM sales_main WHERE sales_main.s_customer_id=pullout_main.p_sales_id)) AS branch_name,
                        (SELECT p_code FROM products WHERE products.p_id=(SELECT p_product_id FROM inventory WHERE inventory.p_id=pullout_main.p_product_id)) AS p_code,
                        (SELECT p_name FROM products WHERE products.p_id=(SELECT p_product_id FROM inventory WHERE inventory.p_id=pullout_main.p_product_id)) AS p_name
                    FROM pullout_main WHERE p_status='R' ORDER BY p_date ASC";
    $recordset_main = mysql_query($query_main) or die('Query failed: ' . mysql_error());
    $data = '[';
    while ($row_main = mysql_fetch_array($recordset_main, MYSQL_ASSOC)) {

        $sales_id = "";
        $s_sales_type = "";
        $s_no = "";
        $query_sales = "SELECT * FROM sales_sub WHERE s_product_id=".$row_main['p_id']." LIMIT 1";
        $recordset_sales = mysql_query($query_sales) or die('Query failed: ' . mysql_error());
        while ($row_sales = mysql_fetch_array($recordset_sales, MYSQL_ASSOC)) {
            $sales_id = $row_sales['s_sales_id'];
            $s_no = "S".$row_sales['s_sales_id'];
        }  
        $query_salesmain = "SELECT  *,
                        (SELECT CONCAT(c_lastname,', ',c_firstname) FROM customers WHERE customers.c_id=sales_main.s_customer_id) AS s_customer
                        FROM sales_main WHERE s_id=".$sales_id;
        $recordset_salesmain = mysql_query($query_salesmain) or die('Query failed: ' . mysql_error());
        while ($row_salesmain = mysql_fetch_array($recordset_salesmain, MYSQL_ASSOC)) {
            $s_sales_type = $row_salesmain['s_sales_type'];
        }

        $data .= '{
            "p_id":"'.$row_main['p_id'].'",
            "p_no":"'.'PL-'.$row_main['p_id'].'",
            "p_date":"'.date('m/d/Y',strtotime($row_main['p_date'])).'",
            "customer_name":"'.$row_main['customer_name'].'",
            "branch_id":"'.$row_main['branch_id'].'",
            "branch_name":"'.$row_main['branch_name'].'",
            "product":"'.$row_main['p_code'].' - '.$row_main['p_name'].'",
            "p_product_id":"'.get_stockcode($row_main["p_product_id"]).'-'.$row_main['p_product_id'].'",
        },';
    }
    $data .= ']';  
?>
<div id="dbgrid"></div>
<script>
    $("#dbgrid").jsGrid({
        height: "100%",
        width: "100%",
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
                itemTemplate: function(value,e) { return $("<div>").append('<div><input type="radio" name="selected-record" value="'+e.p_id+'"></div>'); },
            },  
            {   
                title:"PLNO", align:"left",width:"70px", align:"left", sorting:true,
                itemTemplate: function(value,e) { return $("<div>").append('<div><span data-id="'+e.p_id+'" class="grid-link pullout-view">'+e.p_no+'</span></div>'); },                
            }, 
            {name:"p_date", type:"text", width:"100px", title:"DATE", align:"center"},
            {   
                title:"STOCKNO", align:"left",width:"400px", align:"left", sorting:false,
                itemTemplate: function(value,e) { 
                    return $("<div>").append('<span data-salestype="'+e.s_sales_type+'" data-id="'+e.sales_id+'" class="grid-link sales-view-nonsp">'+e.p_product_id+'</span>'); 
                },
            }, 
        ]
    });
</script>

