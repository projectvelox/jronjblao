    
<link type="text/css" rel="stylesheet" href="../css/jsgrid.min.css" />
<link type="text/css" rel="stylesheet" href="../css/jsgrid-theme.min.css" />
<script type="text/javascript" src="../js/jsgrid.min.js"></script>
<?php 
    include "../includes/config.php";
    $query = "SELECT * FROM insurance ORDER BY i_name ASC";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    $data = '[';
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $data .= '{
            "i_id":"'.$row['i_id'].'",
            "i_name":"'.$row['i_name'].'",
            "i_officeaddress":"'.$row['i_officeaddress'].'",
            "i_contactperson":"'.$row['i_contactperson'].'",
            "i_contactnumber":"'.$row['i_contactnumber'].'",
            "i_head":"'.$row['i_head'].'",
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
            {name:"i_name", type:"text", width:"400px", title:"INSURANCE", align:"left"},
            {name:"i_officeaddress", type:"text", width:"400px", title:"OFFICE ADDRESS", align:"left"},
            {name:"i_contactperson", type:"text", width:"250px", title:"CONTACT PERSON", align:"left"},
            {name:"i_contactnumber", type:"text", width:"250px", title:"CONTACT NUMBER", align:"left"},
            {name:"i_head", type:"text", width:"250px", title:"HEAD", align:"left"},
        ]
    });
</script>

