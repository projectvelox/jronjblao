<link type="text/css" rel="stylesheet" href="../css/jsgrid.min.css" />
<link type="text/css" rel="stylesheet" href="../css/jsgrid-theme.min.css" />
<script type="text/javascript" src="../js/jsgrid.min.js"></script>
<?php 
    include "../includes/config.php";
    include "../includes/session.php";

    $query_main = "SELECT 
                        *,
                        (SELECT b_name FROM branches WHERE branches.b_id=sales_main.s_branch_id) AS s_branch,
                        (SELECT CONCAT(c_lastname,', ',c_firstname) FROM customers WHERE customers.c_id=sales_main.s_customer_id) AS s_customer,
                        (SELECT s_product_id FROM sales_sub WHERE sales_sub.s_sales_id=sales_main.s_id) AS s_product_id,
                        (SELECT CONCAT(p_code,' / ',p_name) FROM products WHERE products.p_id=(SELECT p_product_id FROM inventory WHERE inventory.p_id=(SELECT s_product_id FROM sales_sub WHERE sales_sub.s_sales_id=sales_main.s_id)) ) AS p_product_code,
                        (SELECT SUM(s_sold_price*s_qty) FROM sales_sub WHERE sales_sub.s_sales_id=sales_main.s_id) AS s_sold_price    
                        FROM sales_main
                        WHERE s_category='NONSP' 
                            AND s_branch_id=".$_SESSION['branch_id']." ORDER BY s_confirm ASC, s_sales_date ASC";
    $recordset_main = mysql_query($query_main) or die('Query failed: ' . mysql_error());
    $data = '[';
    while ($row_main = mysql_fetch_array($recordset_main, MYSQL_ASSOC)) {
        if($row_main['s_sales_type']=='C') { $sales_type = "Cash"; } else { $sales_type = "Installment"; }
        $s_total = ($row_main['s_sold_price']+$row_main['s_othercharges']+$row_main['s_tax'])-$row_main['s_discount'];
        $product = $row_main['p_product_code'];
        $data .= '{
            "s_id":"'.$row_main['s_id'].'",
            "s_no":"'.'S'.$row_main['s_id'].'",
            "s_sales_date":"'.date('m/d/Y',strtotime($row_main['s_sales_date'])).'",
            "sales_type":"'.$sales_type.'",
            "s_sales_type":"'.$row_main['s_sales_type'].'",
            "s_orno":"'.$row_main['s_orno'].'",
            "s_branch":"'.$row_main['s_branch'].'",
            "s_customer":"'.$row_main['s_customer'].'",
            "s_customer_id":"'.$row_main['s_customer_id'].'",
            "s_total":"'.number_format($s_total,2,'.',',').'",
            "p_product_code":"'.$product.'",
            "s_confirm":"'.$row_main['s_confirm'].'",
            "s_product_id":"'.$row_main['s_product_id'].'",
            "s_po_no":"'.get_stockcode($row_main["s_product_id"]).'-'.$row_main['s_product_id'].'",
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
                itemTemplate: function(value,e) { return $("<div>").append('<div><input type="radio" name="selected-record" value="'+e.s_id+'"></div>'); },
            },   
            {   
                title:"SALES_NO", align:"left",width:"80px", align:"left", sorting:false,
                itemTemplate: function(value,e) { 
                    if(e.s_confirm=="Y"){
                        return $("<div>").append('<span data-salestype="'+e.s_sales_type+'" data-id="'+e.s_id+'" class="grid-link sales-view-nonsp">'+e.s_no+'</span>'); 
                    } else {
                        return $("<div>").append('<span data-salestype="'+e.s_sales_type+'" data-id="'+e.s_id+'" class="grid-link sales-view-nonsp">'+e.s_no+'</span>'); 
                    }
                },
            },  
            {   
                title:"CUSTOMER", align:"left",width:"200px", align:"left", sorting:false,
                itemTemplate: function(value,e) { 
                    return $("<div>").append('<span data-id="'+e.s_customer_id+'" class="grid-link view_customer">'+e.s_customer+'</span>'); 
                },
            }, 
            {   
                title:"STOCK", align:"left",width:"80px", align:"center", sorting:false,
                itemTemplate: function(value,e) { 
                    return $("<div>").append('<span data-id="'+e.s_product_id+'" class="grid-link stock-view">'+e.s_po_no+'</span>'); 
                },
            }, 
            {name:"p_product_code", type:"text", width:"240px", title:"PRODUCT", align:"left"},
            {name:"s_branch", type:"text", width:"250px", title:"BRANCH", align:"left"},
        ]
    });
</script>

