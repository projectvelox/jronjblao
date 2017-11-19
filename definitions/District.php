    
<link type="text/css" rel="stylesheet" href="../css/jsgrid.min.css" />
<link type="text/css" rel="stylesheet" href="../css/jsgrid-theme.min.css" />
<script type="text/javascript" src="../js/jsgrid.min.js"></script>
<?php 
    include "../includes/config.php";
    $query = "SELECT * FROM districts ORDER BY d_name ASC";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    $data = '[';
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $data .= '{
            "d_id":"'.$row['d_id'].'",
            "d_name":"'.$row['d_name'].'",
            "d_officeaddress":"'.$row['d_officeaddress'].'",
            "d_contactperson":"'.$row['d_contactperson'].'",
            "d_contactnumber":"'.$row['d_contactnumber'].'",
            "d_head":"'.$row['d_head'].'",
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
                itemTemplate: function(value,e) { return $("<div>").append('<div><i data-id="'+e.d_id+'" class="fa fa-pencil-square grid-icon definition-edit" aria-hidden="true"></i></div>'); },
            },
            {name:"d_name", type:"text", width:"250px", title:"DISTRICT", align:"left"},
            {name:"d_officeaddress", type:"text", width:"300px", title:"OFFICE ADDRESS", align:"left"},
            {name:"d_contactperson", type:"text", width:"250px", title:"CONTACT PERSON", align:"left"},
            {name:"d_contactnumber", type:"text", width:"250px", title:"CONTACT NUMBER", align:"left"},
            {name:"d_head", type:"text", width:"250px", title:"HEAD", align:"left"},
        ]
    });
</script>

