    
<link type="text/css" rel="stylesheet" href="../css/jsgrid.min.css" />
<link type="text/css" rel="stylesheet" href="../css/jsgrid-theme.min.css" />
<script type="text/javascript" src="../js/jsgrid.min.js"></script>
<?php 
    include "../includes/config.php";
    $query = "SELECT * FROM vendors_category ORDER BY cat_name ASC";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    $data = '[';
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $data .= '{
            "cat_id":"'.$row['cat_id'].'",
            "cat_name":"'.$row['cat_name'].'",
            "cat_desc":"'.$row['cat_desc'].'",
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
                itemTemplate: function(value,e) { return $("<div>").append('<div><i data-id="'+e.cat_id+'" class="fa fa-pencil-square grid-icon definition-edit" aria-hidden="true"></i></div>'); },
            },
            {name:"cat_name", type:"text", width:"40%", title:"CATEGORY", align:"left"},
            {name:"cat_desc", type:"text", width:"60%", title:"DESCRIPTION", align:"left"},
        ]
    });
</script>

