    
<link type="text/css" rel="stylesheet" href="../css/jsgrid.min.css" />
<link type="text/css" rel="stylesheet" href="../css/jsgrid-theme.min.css" />
<script type="text/javascript" src="../js/jsgrid.min.js"></script>
<?php 
    include "../includes/config.php";
    $query = "SELECT *,
                (SELECT b_name FROM branches WHERE a_branch_id=branches.b_id) AS a_branch,
                (SELECT CONCAT(e_lastname,', ',e_firstname) FROM employees WHERE a_collector_id=employees.e_id) AS a_collector
                FROM areas ORDER BY a_name ASC";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    $data = '[';
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $data .= '{
            "a_id":"'.$row['a_id'].'",
            "a_name":"'.$row['a_name'].'",
            "a_code":"'.$row['a_code'].'",
            "a_branch":"'.$row['a_branch'].'",
            "a_collector":"'.$row['a_collector'].'",
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
        pageSize: 15,
        pageButtonCount: 15,
        data: <?php echo $data; ?>,
        fields: [
            {   
                title:"", align:"left",width:30, align:"center", sorting:false,
                itemTemplate: function(value,e) { return $("<div>").append('<div><i data-id="'+e.a_id+'" class="fa fa-pencil-square grid-icon definition-edit" aria-hidden="true"></i></div>'); },
            },
            {name:"a_name", type:"text", width:"30%", title:"AREA", align:"left"},
            {name:"a_code", type:"text", width:"20%", title:"CODE", align:"left"},
            {name:"a_branch", type:"text", width:"20%", title:"BRANCH", align:"left"},
            {name:"a_collector", type:"text", width:"30%", title:"COLLECTOR", align:"left"},
        ]
    });
</script>

