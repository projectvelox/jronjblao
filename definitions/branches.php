    
<link type="text/css" rel="stylesheet" href="../css/jsgrid.min.css" />
<link type="text/css" rel="stylesheet" href="../css/jsgrid-theme.min.css" />
<script type="text/javascript" src="../js/jsgrid.min.js"></script>
<?php 
    include "../includes/config.php";
    $query = "SELECT * FROM branches ORDER BY b_name ASC";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    $data = '[';
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $data .= '{
            "b_id":"'.$row['b_id'].'",
            "b_name":"'.$row['b_name'].'",
            "b_address_street":"'.$row['b_address_street'].'",
            "b_address_town":"'.$row['b_address_town'].'",
            "b_address_city":"'.$row['b_address_city'].'",
            "b_address_zipcode":"'.$row['b_address_zipcode'].'",
            "b_phone_office":"'.$row['b_phone_office'].'",
            "b_email":"'.$row['b_email'].'",
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
                itemTemplate: function(value,e) { return $("<div>").append('<div><i data-id="'+e.b_id+'" class="fa fa-pencil-square grid-icon definition-edit" aria-hidden="true"></i></div>'); },
            },
            {name:"b_name", type:"text", width:"200px", title:"BRANCH", align:"left"},
            {name:"b_address_street", type:"text", width:"300px", title:"STREET", align:"left"},
            {name:"b_address_town", type:"text", width:"150px", title:"TOWN", align:"left"},
            {name:"b_address_city", type:"text", width:"150px", title:"CITY", align:"left"},
            {name:"b_address_zipcode", type:"text", width:"150px", title:"ZIP CODE", align:"left"},
            {name:"b_phone_office", type:"text", width:"150px", title:"PHONE", align:"left"},
            {name:"b_email", type:"text", width:"200px", title:"EMAIL", align:"left"},
        ]
    });
</script>

