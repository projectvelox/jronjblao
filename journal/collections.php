<link type="text/css" rel="stylesheet" href="../css/jsgrid.min.css" />
<link type="text/css" rel="stylesheet" href="../css/jsgrid-theme.min.css" />
<script type="text/javascript" src="../js/jsgrid.min.js"></script>
<?php 
    include "../includes/config.php";
    $param = "";
    $query_main = "SELECT *,
                    (SELECT b_name FROM branches WHERE branches.b_id=journal_main.j_branch_id) AS branch_name,
                    (SELECT CONCAT(e_lastname,e_firstname) FROM employees WHERE journal_main.j_postedby_id=employees.e_id) AS postedby_name
                    FROM journal_main
                        WHERE j_origination_category='CL'
                    ORDER BY j_status DESC, j_transaction_date ASC, j_id ASC";
    $recordset_main = mysql_query($query_main) or die('Query failed: ' . mysql_error());
    $data = '[';
    while ($row_main = mysql_fetch_array($recordset_main, MYSQL_ASSOC)) {
        if($row_main['j_origination_category']=="S")  { 
            $origination = "Sales"; 
            $param_query = "SELECT s_sales_type FROM sales_main WHERE s_id=".$row_main['j_origination_id'];
            $recordset_param = mysql_query($param_query) or die('Query failed: ' . mysql_error());
            while ($row_param = mysql_fetch_array($recordset_param, MYSQL_ASSOC)) {
                $param = $row_param['s_sales_type'];
            }
        } else if($row_main['j_origination_category']=="PL")  { 
            $origination = "Pull Out"; 
            $param = "";
        } else if($row_main['j_origination_category']=="PO")  { 
            $origination = "Purchase Order"; 
        } else if($row_main['j_origination_category']=="CL")  { 
            $origination = "Collection"; 
            $param = "";
        } 

        $total_debit = 0;
        $total_credit = 0;
        $query_sub = "SELECT 
                            SUM(IF(j_entry='D',j_amount,0)) total_debit,
                            SUM(IF(j_entry='C',j_amount,0)) total_credit
                        FROM journal_sub WHERE j_parent_id=".$row_main['j_id'];
        $recordset_sub = mysql_query($query_sub) or die('Query failed: ' . mysql_error());
        while ($row_sub = mysql_fetch_array($recordset_sub, MYSQL_ASSOC)) {
            $total_debit = $row_sub['total_debit'];
            $total_credit = $row_sub['total_credit'];
        }    
        $data .= '{
            "j_id":"'.$row_main['j_id'].'",
            "j_no":"'.'J'.$row_main['j_id'].'",
            "j_transaction_date":"'.date('m/d/Y',strtotime($row_main['j_transaction_date'])).'",
            "j_branch_id":"'.$row_main['j_branch_id'].'",
            "j_branch_id":"'.$row_main['j_branch_id'].'",
            "branch_name":"'.$row_main['branch_name'].'",
            "postedby_name":"'.$row_main['postedby_name'].'",
            "j_particulars":"'.$row_main['j_particulars'].'",
            "total_debit":"'.number_format($total_debit,2,'.',',').'",
            "total_credit":"'.number_format($total_credit,2,'.',',').'",
            "origination":"'.$origination.'",
            "origination_code":"'.$row_main['j_origination_category'].'",
            "j_origination_id":"'.$row_main['j_origination_id'].'",          
            "param":"'.$param.'",          
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
                itemTemplate: function(value,e) { return $("<div>").append('<div><input type="radio" name="selected-record" value="'+e.j_id+'"></div>'); },
            },  
            {   
                title:"JOURNAL", align:"left",width:"70px", align:"left", sorting:true,
                itemTemplate: function(value,e) { return $("<div>").append('<div><span data-id="'+e.j_id+'" class="grid-link journal_view">'+e.j_no+'</span></div>'); },                
            }, 
            {name:"j_transaction_date", type:"text", width:"70px", title:"DATE", align:"center"},
            {name:"j_particulars", type:"text", width:"200px", title:"PARTICULARS", align:"left"},
            {name:"total_debit", type:"text", width:"80px", title:"DEBIT", align:"right"},
            {name:"total_credit", type:"text", width:"80px", title:"CREDIT", align:"right"},
            {   
                title:"ORIGINATION", align:"left",width:"100px", align:"left", sorting:true,
                itemTemplate: function(value,e) { return $("<div>").append('<div><span data-param="'+e.param+'" data-origination="'+e.origination_code+'" data-id="'+e.j_origination_id+'" class="grid-link origination_view">'+e.origination+'</span></div>'); },                
            },             
            {name:"branch_name", type:"text", width:"150px", title:"BRANCH", align:"left"},
        ]
    });
</script>

