<link type="text/css" rel="stylesheet" href="../css/jsgrid.min.css" />
<link type="text/css" rel="stylesheet" href="../css/jsgrid-theme.min.css" />
<script type="text/javascript" src="../js/jsgrid.min.js"></script>
<?php 
    include "../includes/config.php";
    include "../includes/session.php";

    $data = '[';
    $query_main = "SELECT 
                        *,
                        (SELECT CONCAT(e_lastname,', ',e_firstname) FROM employees WHERE employees.e_id=payments.p_collector_id) AS p_collector_name
                        FROM payments 
                        WHERE p_sales_id=".$_GET['id']." ORDER BY p_date ASC, p_id ASC";
    $recordset_main = mysql_query($query_main) or die('Query failed: ' . mysql_error());
    while ($row_main = mysql_fetch_array($recordset_main, MYSQL_ASSOC)) {
        $data .= '{"p_date":"'.date('m/d/Y',strtotime($row_main['p_date'])).'",
                    "p_or":"'.$row_main['p_or'].'",
                    "p_collector_name":"'.$row_main['p_collector_name'].'",
                    "p_isposted":"'.$row_main['p_isposted'].'",
                    "p_amount":"'.number_format($row_main['p_amount'], 2,'.',',').'",
            },';
    }
    $data .= ']';
?>
<div class="form-container bg-lightgray nopadding" height="501" width="700" uuid="">
    <div id="dbgrid-payments"></div>
</div>
<div class="form-footer">
    <button closewindow="off" class="form-button"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
    <button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
</div>
<script>
    $("#dbgrid-payments").jsGrid({
        height: "420px",
        width: "100%",
        filtering: false,
        editing: false,
        sorting: true,
        paging: true,
        autoload: true,
        pageSize: 15,
        pageButtonCount: 15,
        data: <?php echo $data; ?>,
        fields: [
            {   
                title:"POSTED", align:"left",width:"50px", align:"center", sorting:false,
                itemTemplate: function(value,e) { 
                    if(e.p_isposted=="Y"){
                        return $("<div>").append('<i class="fa fa-check" aria-hidden="true"></i>'); 
                    } else {
                        return $("<div>").append(""); 
                    }
                },
            },         
            {name:"p_date", type:"text", width:"80px", title:"DATE", align:"center"},
            {name:"p_or", type:"text", width:"100px", title:"ORNO", align:"left"},
            {name:"p_amount", type:"text", width:"100px", title:"AMOUNT", align:"right"},
            {name:"p_collector_name", type:"text", width:"200px", title:"COLLECTOR", align:"left"},
        ]
    });
</script>

