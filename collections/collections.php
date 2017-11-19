<link type="text/css" rel="stylesheet" href="../css/jsgrid.min.css" />
<link type="text/css" rel="stylesheet" href="../css/jsgrid-theme.min.css" />
<script type="text/javascript" src="../js/jsgrid.min.js"></script>
<?php 
    include "../includes/config.php";
    include "../includes/session.php";

    $query_main = "SELECT 
                        *,
                        (SELECT CONCAT(c_lastname,', ',c_firstname) FROM customers WHERE customers.c_id=(SELECT s_customer_id FROM sales_main WHERE sales_main.s_id=payments.p_sales_id)) AS s_customer_name,
                        (SELECT s_customer_id FROM sales_main WHERE sales_main.s_id=payments.p_sales_id) AS s_customer_id,
                        (SELECT s_sales_type FROM sales_main WHERE sales_main.s_id=payments.p_sales_id) AS s_sales_type
                        FROM payments 
                        WHERE p_branch_id=".$_SESSION['branch_id']." ORDER BY p_isposted, p_date ASC, p_id ASC";
    $recordset_main = mysql_query($query_main) or die('Query failed: ' . mysql_error());
    $data = '[';
    while ($row_main = mysql_fetch_array($recordset_main, MYSQL_ASSOC)) {
        $data .= '{
            "p_id":"'.$row_main['p_id'].'",
            "p_no":"'.'PC'.$row_main['p_id'].'",
            "p_date":"'.date('m/d/Y',strtotime($row_main['p_date'])).'",
            "p_collector_id":"'.$row_main['p_collector_id'].'",
            "p_amount_number":"'.$row_main['p_amount'].'",
            "p_amount":"'.number_format($row_main['p_amount'],2,'.',',').'",
            "p_or":"'.$row_main['p_or'].'",
            "p_isposted":"'.$row_main['p_isposted'].'",
            "s_customer_id":"'.$row_main['s_customer_id'].'",
            "s_customer_name":"'.$row_main['s_customer_name'].'",
            "p_rebate":"'.number_format($row_main['p_rebate'],2,'.',',').'",
            "p_branch_id":"'.$row_main['p_branch_id'].'",
            "p_sales_id":"'.$row_main['p_sales_id'].'",
            "p_sales_no":"S'.$row_main['p_sales_id'].'",
            "p_sales_param":"'.$row_main['s_sales_type'].'",
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
                    if(e.p_isposted=="Y"){
                        return $("<div>").append('<i class="fa fa-check" aria-hidden="true"></i>'); 
                    } else {
                        return $("<div>").append('<button data-amount="'+e.p_amount_number+'" data-id="'+e.p_id+'" closewindow="off" class="form-button-recieved post-collection">POST</button>'); 
                    }
                },
            },  
            {   
                title:"COLNO", align:"left",width:"80px", align:"left", sorting:false,
                itemTemplate: function(value,e) { 
                    if(e.p_isposted=="Y"){
                        return $("<div>").append('<span data-id="'+e.p_id+'" class="grid-link collection-view">'+e.p_no+'</span>'); 
                    } else {
                        return $("<div>").append('<span data-id="'+e.p_id+'" class="grid-link collection-edit">'+e.p_no+'</span>'); 
                    }
                },
            },  
            {   
                title:"SALES_NO", align:"left",width:"80px", align:"left", sorting:false,
                itemTemplate: function(value,e) { 
                    return $("<div>").append('<span data-param="'+e.p_sales_param+'" data-id="'+e.p_sales_id+'" class="grid-link view-sales">'+e.p_sales_no+'</span>'); 
                },
            },
            {name:"p_date", type:"text", width:"80px", title:"DATE", align:"center"},
            {name:"p_or", type:"text", width:"100px", title:"ORNO", align:"left"},
            {   
                title:"CUSTOMER", align:"left",width:"250px", align:"left", sorting:false,
                itemTemplate: function(value,e) { 
                    return $("<div>").append('<span data-id="'+e.s_customer_id+'" class="grid-link view_customer">'+e.s_customer_name+'</span>'); 
                },
            }, 
            {name:"p_amount", type:"text", width:"100px", title:"AMOUNT", align:"right"},
        ]
    });
</script>

