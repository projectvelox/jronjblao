<?php 
    require '../includes/document_head.php'; 

    $query = "SELECT 
                    *,
                    (SELECT cat_name FROM vendors_category WHERE v_category_id=vendors_category.cat_id) AS v_category
                FROM vendors WHERE v_is_deleted='N' ORDER BY v_companyname ASC";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    $data = '[';
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $data .= '{
            "v_id":"'.$row['v_id'].'",
            "v_no":"'.'V'.$row['v_id'].'",
            "v_tradename":"'.string_sanitize($row['v_tradename']).'",
            "v_category":"'.$row['v_category'].'",
        },';
    }
    $data .= ']';  


?>

<div id="wrapper">
    <?php include '../includes/sidebar.php'; ?>
    <?php include '../includes/navigation.php' ?>
    <div class="main_container container_16 clearfix fullsize">
        <div class="table_header">
            <i class="fa fa-th-large" aria-hidden="true"></i> Vendors
            <button class="toolbar-button edit-vendor"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Vendor</button>
            <button class="toolbar-button new-vendor"><i class="fa fa-plus" aria-hidden="true"></i> New Vendor</button>
        </div>
        <div class="page-wrapper">
            <div class="col-xs-12 col-md-12 col-lg-12 search-container">
                <input name="search" type="text" class="form-control input-md field_input search-box" value="" required>
                <button class="toolbar-button search-buttons action-search"><i class="fa fa-times" aria-hidden="true"></i> Clear</button>
                <button class="toolbar-button search-buttons action-searchclear"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
            </div>  
            <div id="dbgrid"></div>                           
        </div>
    </div>  
</div>

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
                itemTemplate: function(value,e) { return $("<div>").append('<div><input type="radio" name="selected-record" value="'+e.v_id+'"></div>'); },
            },   
            {   
                title:"ID", align:"left",width:40, align:"left", sorting:true,
                itemTemplate: function(value,e) { return $("<div>").append('<div><span data-id="'+e.v_id+'" class="grid-link view_vendor">'+e.v_no+'</span></div>'); },                
            },  
            {name:"v_tradename", type:"text", width:200, title:"VENDOR", align:"left"},
            {name:"v_category", type:"text", width:200, title:"CATEGORY", align:"left"},                     
        ]
    });
</script>
<script src="vendor.js"></script>
<?php require '../includes/closing_items.php' ?>
