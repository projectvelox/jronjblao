    
<link type="text/css" rel="stylesheet" href="../css/jsgrid.min.css" />
<link type="text/css" rel="stylesheet" href="../css/jsgrid-theme.min.css" />
<script type="text/javascript" src="../js/jsgrid.min.js"></script>
<?php 
    include "../includes/config.php";
    $query = "SELECT 
                *,
                (SELECT b_name FROM branches WHERE b_id=witness.b_id) AS b_branch
                FROM witness ORDER BY b_id ASC, w_name1 ASC";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    $data = '[';
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $data .= '{
            "w_id":"'.$row['w_id'].'",
            "b_id":"'.$row['b_id'].'",
            "b_branch":"'.$row['b_branch'].'",
            "w_name1":"'.$row['w_name1'].'",
            "w_doi1":"'.$row['w_doi1'].'",
            "w_poi1":"'.$row['w_poi1'].'",
            "w_name2":"'.$row['w_name2'].'",
            "w_doi2":"'.$row['w_doi2'].'",
            "w_poi2":"'.$row['w_poi2'].'",
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
                itemTemplate: function(value,e) { return $("<div>").append('<div><i data-id="'+e.w_id+'" class="fa fa-pencil-square grid-icon definition-edit" aria-hidden="true"></i></div>'); },
            },
            {name:"b_branch", type:"text", width:"200px", title:"BRANCH", align:"left"},
            {name:"w_name1", type:"text", width:"200px", title:"WITNESS (PRIMARY)", align:"left"},
            {name:"w_doi1", type:"text", width:"200px", title:"DOI (PRIMARY)", align:"left"},
            {name:"w_poi1", type:"text", width:"200px", title:"POI (PRIMARY)", align:"left"},
            {name:"w_name2", type:"text", width:"200px", title:"WITNESS (SECONDARY)", align:"left"},
            {name:"w_doi2", type:"text", width:"200px", title:"DOI (SECONDARY)", align:"left"},
            {name:"w_poi2", type:"text", width:"200px", title:"POI (SECONDARY)", align:"left"},            
        ]
    });
</script>

