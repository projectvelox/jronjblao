<link type="text/css" rel="stylesheet" href="../css/jsgrid.min.css" />
<link type="text/css" rel="stylesheet" href="../css/jsgrid-theme.min.css" />
<script type="text/javascript" src="../js/jsgrid.min.js"></script>
<?php 
    include "../includes/config.php";
    include "../includes/session.php";
    $query = "SELECT *,
                (SELECT p_code FROM products WHERE products.p_id=inventory.p_product_id) AS p_product_code,
                (SELECT p_name FROM products WHERE products.p_id=inventory.p_product_id) AS p_product_name,
                (SELECT i_name FROM inv_category WHERE inv_category.i_id=(SELECT p_category_id FROM products WHERE products.p_id=inventory.p_product_id)) AS p_category,
                (SELECT i_name FROM inv_category WHERE inv_category.i_id=((SELECT i_parent_id FROM inv_category WHERE inv_category.i_id=(SELECT p_category_id FROM products WHERE products.p_id=inventory.p_product_id)))) AS p_category_parent,
                (SELECT b_name FROM branches WHERE branches.b_id=inventory.p_branch_id) AS p_branch                FROM inventory 
                    WHERE p_status='S' 
                    AND (SELECT i_name FROM inv_category WHERE inv_category.i_id=((SELECT i_parent_id FROM inv_category WHERE inv_category.i_id=(SELECT p_category_id FROM products WHERE products.p_id=inventory.p_product_id)))) = '".$_GET['f']."'
                    AND p_branch_id=".$_GET['b']." ORDER BY p_recieved_date ASC";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    $data = '[';
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $data .= '{
            "p_id":"'.$row['p_id'].'",
            "p_stockno":"'.get_stockcode($row['p_id']).'-'.$row['p_id'].'",
            "p_product_id":"'.$row['p_product_id'].'",
            "p_recieved_date":"'.date('m/d/Y',strtotime($row['p_recieved_date'])).'",
            "p_branch_id":"'.$row['p_branch_id'].'",
            "p_selling_price":"'.number_format($row['p_selling_price'],2,".",",").'",
            "p_qty":"'.$row['p_qty'].'",
            "p_branch":"'.$row['p_branch'].'",
            "p_purchase_cost":"'.number_format($row['p_purchase_cost'],2,".",",").'",            
            "p_product_code":"'.strtoupper($row['p_product_code']).'",            
            "p_product_name":"'.$row['p_product_name'].'",            
            "p_category":"'.$row['p_category'].'",            
            "p_category_parent":"'.$row['p_category_parent'].'",            
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
                itemTemplate: function(value,e) { return $("<div>").append('<div><input type="radio" name="selected-stock" value="'+e.p_id+'"></div>'); },
            },  
            {   
                title:"STOCKNO", align:"left",width:70, align:"left", sorting:true,
                itemTemplate: function(value,e) { return $("<div>").append('<div><span data-id="'+e.p_id+'" class="grid-link stock-edit">'+e.p_stockno+'</span></div>'); },                
            }, 
            {name:"p_product_code", type:"text", width:"100px", title:"PCODE", align:"left"},
            {name:"p_product_name", type:"text", width:"200px", title:"PRODUCT", align:"left"},
            {name:"p_qty", type:"text", width:"60px", title:"QTY", align:"center"},
            {name:"p_selling_price", type:"text", width:"120px", title:"SELLING PRICE", align:"right"},
            {name:"p_category", type:"text", width:"120px", title:"CATEGORY", align:"left"},
            {name:"p_category_parent", type:"text", width:"120px", title:"GROUP", align:"left"},
            {name:"p_purchase_cost", type:"text", width:"120px", title:"PURCHASE COST", align:"right"},
            {name:"p_branch", type:"text", width:"100px", title:"BRANCH", align:"left"},
        ]
    });
</script>

