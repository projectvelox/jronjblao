<?php 
    require '../includes/document_head.php'; 

    $query = "SELECT 
                    *,
                    (SELECT b_name FROM branches WHERE c_branch_id=branches.b_id) AS branch_name,
                    (SELECT a_name FROM areas WHERE c_area=areas.a_id) AS area                   
                FROM customers WHERE c_is_deleted='N' ORDER BY c_lastname ASC, c_firstname ASC";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    $data = '[';
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $data .= '{
            "c_id":"'.$row['c_id'].'",
            "c_no":"'.'C'.$row['c_id'].'",
            "c_lastname":"'.$row['c_lastname'].'",
            "c_firstname":"'.$row['c_firstname'].'",
            "branch_name":"'.$row['branch_name'].'",
            "area":"'.$row['area'].'"
        },';
    }
    $data .= ']';  

?>

<div id="wrapper">
    <?php include '../includes/sidebar.php'; ?>
    <?php include '../includes/navigation.php' ?>
    <div class="main_container container_16 clearfix fullsize">
        <div class="table_header">
            <i class="fa fa-th-large" aria-hidden="true"></i> Customers
            <button class="toolbar-button ledger-customer"><i class="fa fa-list-alt" aria-hidden="true"></i> Ledger</button>
            <button class="toolbar-button edit-customer"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Customer</button>
            <button class="toolbar-button new-customer"><i class="fa fa-plus" aria-hidden="true"></i> New Customer</button>
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
                itemTemplate: function(value,e) { return $("<div>").append('<div><input type="radio" name="selected-record" value="'+e.c_id+'"></div>'); },
            },   
            {   
                title:"ID", align:"left",width:55, align:"left", sorting:true,
                itemTemplate: function(value,e) { return $("<div>").append('<div><span data-id="'+e.c_id+'" class="grid-link view_customer">'+e.c_no+'</span></div>'); },                
            },  
            {name:"c_firstname", type:"text", width:100, title:"FIRST NAME", align:"left"},
            {name:"c_lastname", type:"text", width:100, title:"LASTNAME", align:"left"},                     
            {name:"branch_name", type:"text", width:150, title:"BRANCH", align:"left"},
            {name:"area", type:"text", width:150, title:"AREA", align:"left"},
        ]
    });
</script>
<script src="customer.js"></script>
<?php require '../includes/closing_items.php' ?>
