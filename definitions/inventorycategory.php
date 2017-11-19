    
<link type="text/css" rel="stylesheet" href="../css/jsgrid.min.css" />
<link type="text/css" rel="stylesheet" href="../css/jsgrid-theme.min.css" />
<script type="text/javascript" src="../js/jsgrid.min.js"></script>
<?php 
    include "../includes/config.php";
    $query = "SELECT a.*,
                (SELECT i_name FROM inv_category AS b WHERE a.i_parent_id = b.i_id) as i_parent
                FROM inv_category AS a WHERE i_parent_id<>0 ORDER BY a.i_name ASC";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    $data = '[';
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $data .= '{
            "i_id":"'.$row['i_id'].'",
            "i_name":"'.$row['i_name'].'",
            "i_parent":"'.$row['i_parent'].'",
            "i_code":"'.$row['i_code'].'",
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
                itemTemplate: function(value,e) { return $("<div>").append('<div><i data-id="'+e.i_id+'" class="fa fa-pencil-square grid-icon definition-edit" aria-hidden="true"></i></div>'); },
            },
            {name:"i_name", type:"text", width:"40%", title:"CATEGORY", align:"left"},
            {name:"i_parent", type:"text", width:"40%", title:"GROUP", align:"left"},
            {name:"i_code", type:"text", width:"40%", title:"CODE", align:"left"},

        ]
    });
</script>

