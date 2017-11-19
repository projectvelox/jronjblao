<?php 
    require '../includes/document_head.php'; 

    $query = "SELECT *,
                   (SELECT i_name FROM inv_category WHERE inv_category.i_id=p_category_id) AS p_category,
                   (SELECT i_name FROM inv_category WHERE inv_category.i_id=(SELECT i_parent_id FROM inv_category WHERE inv_category.i_id=p_category_id)) AS p_group
             FROM products WHERE p_is_deleted='N' ORDER BY p_code ASC";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    $data = '[';
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $data .= '{
            "p_id":"'.$row['p_id'].'",
            "p_code":"'.strtoupper($row['p_code']).'",
            "p_name":"'.string_sanitize($row['p_name']).'",
            "p_reorderlevel":"'.$row['p_reorderlevel'].'",
            "p_category":"'.$row['p_category'].'",
            "p_group":"'.$row['p_group'].'",
        },';
    }
    $data .= ']';  

  

?>

<div id="wrapper">
    <?php include '../includes/sidebar.php'; ?>
    <?php include '../includes/navigation.php' ?>
    <div class="main_container container_16 clearfix fullsize">
        <div class="table_header">
            <i class="fa fa-th-large" aria-hidden="true"></i> Products
            <button class="toolbar-button edit-product"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Product</button>
            <button class="toolbar-button new-product"><i class="fa fa-plus" aria-hidden="true"></i> New Product</button>
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
        height: "500px",
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
                itemTemplate: function(value,e) { return $("<div>").append('<div><input type="radio" name="selected-record" value="'+e.p_id+'"></div>'); },
            }, 
            {   
                title:"CODE", align:"left",width:100, align:"left", sorting:true,
                itemTemplate: function(value,e) { return $("<div>").append('<div><span data-id="'+e.p_id+'" class="grid-link view_product">'+e.p_code+'</span></div>'); },                
            },  
            {name:"p_name", type:"text", width:250, title:"PRODUCT NAME", align:"left"},
            {name:"p_group", type:"text", width:150, title:"GROUP", align:"left"},
            {name:"p_category", type:"text", width:150, title:"CATEGORY", align:"left"},
            {name:"p_reorderlevel", type:"text", width:70, title:"REORDER", align:"left"},                     
        ]
    });
</script>
<script src="product.js"></script>
<?php require '../includes/closing_items.php' ?>
