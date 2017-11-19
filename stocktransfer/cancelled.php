<link type="text/css" rel="stylesheet" href="../css/jsgrid.min.css" />
<link type="text/css" rel="stylesheet" href="../css/jsgrid-theme.min.css" />
<script type="text/javascript" src="../js/jsgrid.min.js"></script>
<?php 
    include "../includes/config.php";
    
    $query_main = "SELECT *,
                        (SELECT b_name FROM branches WHERE branches.b_id=stocktransfer_main.t_branch_origin_id) AS t_branch_origin,
                        (SELECT b_name FROM branches WHERE branches.b_id=stocktransfer_main.t_branch_destination_id) AS t_branch_destination,
                        (SELECT CONCAT(e_lastname,', ',e_firstname) FROM employees WHERE employees.e_id=stocktransfer_main.t_cancelby_id) AS t_cancelby
                     FROM stocktransfer_main 
                     WHERE 
                        t_cancel_date IS NOT NULL   
                     ORDER BY t_cancel_date ASC";
    $recordset_main = mysql_query($query_main) or die('Query failed: ' . mysql_error());
    $data = '[';
    while ($row_main = mysql_fetch_array($recordset_main, MYSQL_ASSOC)) {
        $data .= '{
            "t_id":"'.$row_main['t_id'].'",
            "t_no":"'.'TL-'.$row_main['t_id'].'",
            "t_cancel_date":"'.date('m/d/Y',strtotime($row_main['t_cancel_date'])).'",
            "t_branch_origin":"'.$row_main['t_branch_origin'].'",
            "t_branch_destination":"'.$row_main['t_branch_destination'].'",
            "t_cancelby":"'.$row_main['t_cancelby'].'",
            "t_notes":"'.$row_main['t_notes'].'",
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
                itemTemplate: function(value,e) { return $("<div>").append('<div><input type="radio" name="selected-record" value="'+e.t_id+'"></div>'); },
            },  
            {   
                title:"LOG", align:"left",width:"70px", align:"left", sorting:true,
                itemTemplate: function(value,e) { return $("<div>").append('<div><span data-id="'+e.t_id+'" class="grid-link transfer-view">'+e.t_no+'</span></div>'); },                
            }, 
            {name:"t_cancel_date", type:"text", width:"100px", title:"CANCELLED", align:"center"},
            {name:"t_branch_origin", type:"text", width:"150px", title:"ORIGINATION", align:"left"},
            {name:"t_branch_destination", type:"text", width:"150px", title:"DESTINATION", align:"left"},
            {name:"t_cancelby", type:"text", width:"150px", title:"CANCELLED BY", align:"left"},
            {name:"t_notes", type:"text", width:"300px", title:"REMARKS", align:"left"},
        ]
    });
</script>

