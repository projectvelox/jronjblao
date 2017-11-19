
    
<link type="text/css" rel="stylesheet" href="../css/jsgrid.min.css" />
<link type="text/css" rel="stylesheet" href="../css/jsgrid-theme.min.css" />
<script type="text/javascript" src="../js/jsgrid.min.js"></script>
<?php 
    include "../includes/config.php";
    $query_main = "SELECT *,
                (SELECT CONCAT(e_lastname,', ',e_firstname) FROM employees WHERE employees.e_id=po_main.p_requestedby_id) AS p_requestedby_name,
                (SELECT v_tradename FROM vendors WHERE vendors.V_id=po_main.p_vendor_id) AS p_vendor_name,
                (SELECT b_name FROM branches WHERE branches.b_id=po_main.p_branch_id) AS p_branch
                FROM po_main WHERE p_status='V' ORDER BY p_requestdate ASC";
    $recordset_main = mysql_query($query_main) or die('Query failed: ' . mysql_error());
    $data = '[';
    while ($row_main = mysql_fetch_array($recordset_main, MYSQL_ASSOC)) {
        $total_amount = 0;
        $query_sub = "SELECT p_itemprice,p_qty FROM po_sub WHERE p_id=".$row_main['p_id'];
        $recordset_sub = mysql_query($query_sub) or die('Query failed: ' . mysql_error());
        while ($row_sub = mysql_fetch_array($recordset_sub, MYSQL_ASSOC)) {
            $total_amount = $total_amount + ( $row_sub['p_itemprice'] * $row_sub['p_qty'] );
        }   
        $total_amount = $total_amount + ($row_main['p_charge_tax']+$row_main['p_charge_shipping']+$row_main['p_charge_others']) - $row_main['p_discount'];
        $data .= '{
            "p_id":"'.$row_main['p_id'].'",
            "p_no":"'.'P'.$row_main['p_id'].'",
            "verified_date":"'.date('m/d/Y',strtotime($row_main['verified_date'])).'",
            "p_orderdate":"'.date('m/d/Y',strtotime($row_main['p_orderdate'])).'",
            "p_requestedby_id":"'.$row_main['p_requestedby_id'].'",
            "p_requestedby_name":"'.$row_main['p_requestedby_name'].'",
            "p_branch":"'.$row_main['p_branch'].'",
            "p_vendor_name":"'.preg_replace('/\r|\n/','',$row_main['p_vendor_name']).'",
            "p_total":"'.number_format($total_amount,2,".",",").'",
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
                itemTemplate: function(value,e) { return $("<div>").append('<div><input type="radio" name="selected-po" value="'+e.p_id+'"></div>'); },
            },  
            {   
                title:"PO", align:"left",width:70, align:"left", sorting:true,
                itemTemplate: function(value,e) { return $("<div>").append('<div><span data-id="'+e.p_id+'" class="grid-link po-view">'+e.p_no+'</span></div>'); },                
            },  
            {name:"verified_date", type:"text", width:"100px", title:"VERIFIED", align:"center"},
            {name:"p_orderdate", type:"text", width:"100px", title:"ORDER DATE", align:"center"},
            {name:"p_total", type:"text", width:"100px", title:"TOTAL", align:"right"},
            {name:"p_branch", type:"text", width:"150px", title:"BRANCH", align:"left"},
            {name:"p_vendor_name", type:"text", width:"200px", title:"VENDOR", align:"left"},
        ]
    });
</script>

