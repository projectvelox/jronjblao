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
                        (SELECT SUM(s_sold_price*s_qty) FROM sales_sub WHERE sales_sub.s_sales_id=sales_main.s_id) AS s_sold_price    
                        FROM sales_main
                        WHERE s_category='SP' 
                            AND s_branch_id=".$_SESSION['branch_id']." ORDER BY s_confirm ASC, s_sales_date ASC";
    $recordset_main = mysql_query($query_main) or die('Query failed: ' . mysql_error());
    $data = '[';
    while ($row_main = mysql_fetch_array($recordset_main, MYSQL_ASSOC)) {
        if($row_main['s_sales_type']=='C') { $sales_type = "Cash"; } else { $sales_type = "Installment"; }
        $s_total = ($row_main['s_sold_price']+$row_main['s_othercharges']+$row_main['s_tax'])-$row_main['s_discount'];
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
            "s_confirm":"'.$row_main['s_confirm'].'",
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
                title:"POST", align:"left",width:"70px", align:"center", sorting:false,
                itemTemplate: function(value,e) { 
                    if(e.s_confirm=="Y"){
                        return $("<div>").append('<i class="fa fa-check" aria-hidden="true"></i>'); 
                    } else {
                        return $("<div>").append('<button data-id="'+e.s_id+'" closewindow="off" class="form-button-recieved post-sales-sp">POST</button>'); 
                    }
                },
            },  
            {   
                title:"SALES_NO", align:"left",width:"80px", align:"center", sorting:false,
                itemTemplate: function(value,e) { 
                    if(e.s_confirm=="Y"){
                        return $("<div>").append('<span data-salestype="'+e.s_sales_type+'" data-id="'+e.s_id+'" class="grid-link sales-view-sp">'+e.s_no+'</span>'); 
                    } else {
                        return $("<div>").append('<span data-salestype="'+e.s_sales_type+'" data-id="'+e.s_id+'" class="grid-link sales-edit-sp">'+e.s_no+'</span>'); 
                    }
                },
            },  
            {name:"s_sales_date", type:"text", width:"100px", title:"DATE", align:"center"},
            {   
                title:"CUSTOMER", align:"left",width:"250px", align:"left", sorting:false,
                itemTemplate: function(value,e) { 
                    return $("<div>").append('<span data-id="'+e.s_customer_id+'" class="grid-link view_customer">'+e.s_customer+'</span>'); 
                },
            }, 
            {name:"s_total", type:"text", width:"110px", title:"TOTAL", align:"right"},
            {name:"sales_type", type:"text", width:"100px", title:"PAYMENT", align:"left"},
            {name:"s_branch", type:"text", width:"200px", title:"BRANCH", align:"left"},
            {name:"s_orno", type:"text", width:"250px", title:"ORNO", align:"left"},
        ]
    });
</script>

