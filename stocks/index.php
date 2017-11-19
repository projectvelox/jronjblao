<?php 
    require '../includes/document_head.php'; 

    $query = "SELECT *,
                   (SELECT i_name FROM inv_category WHERE inv_category.i_id=p_category_id) AS p_category,
                   (SELECT i_name FROM inv_category WHERE inv_category.i_id=(SELECT i_parent_id FROM inv_category WHERE inv_category.i_id=p_category_id)) AS p_group
             FROM products WHERE p_is_deleted='N' ORDER BY p_code ASC";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    $menu = '[';
    $menu .= "{";
    $menu .= "text: '<font color=red>My Branch</font>',";
    $menu .= "href: '',";
    $menu .= "selectable: false,";
    $menu .= "nodes: [";
    $active = '';
    $first = '';
    $group = '';
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        if($first=="") {
            $menu .= "{ text: '".$row['p_group']."', href: '',tags: ['0'], selectable:false, state: { selected: false, expanded: true },";
            $menu .= "nodes:";
            $menu .= "[";
            $active =  "warehouse.php?f=".$row["p_group"];
            $group = $row['p_group'];
            $menu .= "  { other: '0', filter: '".$row['p_group']."', text: 'Warehouse',href: 'warehouse.php?f=".$row['p_group']."', tags: 'Warehouse', state: { selected: true, expanded: true }, },";
            $menu .= "  { other: '0', filter: '".$row["p_group"]."', text: 'Store', href: 'store.php?f=".$row['p_group']."', tags: 'Store' },";
            $menu .= "  { other: '0', filter: '".$row["p_group"]."', text: 'Sold', href: 'sold.php?f=".$row['p_group']."', tags: 'Sold' },";
            $menu .= "  { other: '0', filter: '".$row["p_group"]."', text: 'Lost / Damage', href: 'lostdamage.php?f=".$row['p_group']."', tags: 'Lost' },";
            $menu .= "  { other: '0', filter: '".$row["p_group"]."', text: 'Returned', href: 'returned.php?f=".$row['p_group']."', tags: 'Returned' },";
            $first = "done";
        } else {
            $menu .= "{ text: '".$row['p_group']."', href: '',tags: ['0'], selectable:false,";
            $menu .= "nodes:";
            $menu .= "[";
            $menu .= "  { other: '0', filter: '".$row["p_group"]."', text: 'Warehouse',href: 'warehouse.php?f=".$row['p_group']."', tags: 'Warehouse' },";
            $menu .= "  { other: '0', filter: '".$row["p_group"]."', text: 'Store', href: 'store.php?f=".$row['p_group']."', tags: 'Store' },";
            $menu .= "  { other: '0', filter: '".$row["p_group"]."', text: 'Sold', href: 'sold.php?f=".$row['p_group']."', tags: 'Sold' },";
            $menu .= "  { other: '0', filter: '".$row["p_group"]."', text: 'Lost / Damage', href: 'lostdamage.php?f=".$row['p_group']."', tags: 'Lost' },";
            $menu .= "  { other: '0', filter: '".$row["p_group"]."', text: 'Returned', href: 'returned.php?f=".$row['p_group']."', tags: 'Returned' },";
        }
        $menu .= "],";
        $menu .= "},";
    }

    $menu .= "{ text: 'Transfer In Process', href: 'intransit.php',tags: ['0'] },";
    $menu .= "{ text: 'Pulled Out', href: 'pulledout.php',tags: ['0'] },";
    $menu .= "{ text: 'Repossed Units', href: 'repo.php',tags: ['0'] },";
    $menu .= "],";
    $menu .= "},";


    //Other Branches
    $query_branches = "SELECT * FROM branches WHERE b_id <> ".$_SESSION['branch_id']." ORDER BY b_name";
    $recordset_branches = mysql_query($query_branches) or die('Query failed: ' . mysql_error());
    while ($row_branches = mysql_fetch_array($recordset_branches, MYSQL_ASSOC)) {
        $b_id = $row_branches['b_id'];
        $menu .= "{";
        $menu .= "text: '<font color=blue>".$row_branches['b_name']."</font>',";
        $menu .= "href: '',";
        $menu .= "selectable: false, state : { expanded: false },";
        $menu .= "nodes: [";
        $query_sub = "SELECT *,
                       (SELECT i_name FROM inv_category WHERE inv_category.i_id=p_category_id) AS p_category,
                       (SELECT i_name FROM inv_category WHERE inv_category.i_id=(SELECT i_parent_id FROM inv_category WHERE inv_category.i_id=p_category_id)) AS p_group
                 FROM products WHERE p_is_deleted='N' ORDER BY p_code ASC";
        $recordset_sub = mysql_query($query_sub) or die('Query failed: ' . mysql_error());
        while ($row_sub = mysql_fetch_array($recordset_sub, MYSQL_ASSOC)) {
            $menu .= "{ text: '".$row_sub['p_group']."', href: '',tags: ['0'], selectable:false, state: { expanded: false },";
            $menu .= "nodes:";
            $menu .= "[";
            $menu .= "  { other: '1', filter: '".$row_sub["p_group"]."', text: 'Warehouse',href: 'warehouse_other.php?b=".$b_id."&f=".$row_sub['p_group']."', tags: 'Warehouse' },";
            $menu .= "  { other: '1', filter: '".$row_sub["p_group"]."', text: 'Store', href: 'store_other.php?b=".$b_id."&f=".$row_sub['p_group']."', tags: 'Store' },";
            $menu .= "  { other: '1', filter: '".$row_sub["p_group"]."', text: 'Sold', href: 'sold_other.php?b=".$b_id."&f=".$row_sub['p_group']."', tags: 'Sold' },";
            $menu .= "  { other: '1', filter: '".$row_sub["p_group"]."', text: 'Lost / Damage', href: 'lostdamage_other.php?b=".$b_id."&f=".$row_sub['p_group']."', tags: 'Lost' },";
            $menu .= "  { other: '1', filter: '".$row_sub["p_group"]."', text: 'Returned', href: 'returned_other.php?b=".$b_id."&f=".$row_sub['p_group']."', tags: 'Returned' },";
            $menu .= "],";
            $menu .= "},";
        }
        $menu .= "{ text: 'Transfer In Process', href: 'intransit_other.php?b=".$b_id."',tags: ['0'] },";
        $menu .= "{ text: 'Pulled Out', href: 'pulledout_other.php?b=".$b_id."',tags: ['0'] },";
        $menu .= "{ text: 'Repossed Units', href: 'repo_other.php?b=".$b_id."',tags: ['0'] },";

        $menu .= "],";
        $menu .= "},";
    }

    $menu .= ']'; 
?>

<div id="wrapper">
    <?php include '../includes/sidebar.php'; ?>
    <?php include '../includes/navigation.php' ?>
    <div class="main_container container_16 clearfix fullsize">
        <div class="table_header">
            <i class="fa fa-th-large" aria-hidden="true"></i> Stocks
            <div data-page="" class="header-buttons"></div>
        </div>
        <div class="page-wrapper">
            <div class="panel-sidebar"><div id="tree"></div></div>                           
            <div class="panel-page"></div>                           
        </div>
    </div>  
</div>
<script src="stocks.js"></script>
<script>
    var active = '<?php echo $active;?>';                 
    $('#tree').treeview({ 
        data: <?=$menu;?>, 
        enableLinks:false, 
        onhoverColor:'#ddd',
    });
    $(document).find('.panel-page').load(active);

    var html = '<button class="toolbar-button action-lost"><i class="fa fa-eye-slash" aria-hidden="true"></i> Lost</button>';
      html += '<button class="toolbar-button action-damage"><i class="fa fa-chain-broken" aria-hidden="true"></i> Damage</button>';
      html += '<button class="toolbar-button action-return"><i class="fa fa-truck" aria-hidden="true"></i> Return</button>';
      html += '<button class="toolbar-button action-print"><i class="fa fa-print" aria-hidden="true"></i> Print</button>';
    $(document).find('.header-buttons').html(html);
    $(document).find('.header-buttons').attr('data-page','Warehouse');
    $(document).find('.header-buttons').attr('data-filter','<?=$group;?>');

    $('#tree').on('nodeSelected', function(event, data) {
      console.log(data);
        var html = "";
        if(data.href != "") { 
            $(document).find('.panel-page').load(data.href); 
            $(document).find('.header-buttons').attr('data-filter',data.filter);
            if(data.tags == "Warehouse" || data.tags == "Store") {
                if(data.other==0) {
                    var html = '<button class="toolbar-button action-lost"><i class="fa fa-eye-slash" aria-hidden="true"></i> Lost</button>';
                        html += '<button class="toolbar-button action-damage"><i class="fa fa-chain-broken" aria-hidden="true"></i> Damage</button>';
                        html += '<button class="toolbar-button action-return"><i class="fa fa-truck" aria-hidden="true"></i> Return</button>';
                        html += '<button class="toolbar-button action-print"><i class="fa fa-print" aria-hidden="true"></i> Print</button>';
                    $(document).find('.header-buttons').attr('data-page',data.tags);
                }
            } else if(data.tags == "Returned") {
                if(data.other==0) {
                    var html = '<button class="toolbar-button action-recieved"><i class="fa fa-check" aria-hidden="true"></i> Recieve</button>';
                        html += '<button class="toolbar-button action-print"><i class="fa fa-print" aria-hidden="true"></i> Print</button>';
                    $(document).find('.header-buttons').attr('data-page',data.tags);
                }
            } else if(data.tags=="Lost") {
                if(data.other==0) {
                  var html = '<button class="toolbar-button action-print"><i class="fa fa-print" aria-hidden="true"></i> Print</button>';
                }
            } else {
                if(data.other==0) {
                  var html = '<button class="toolbar-button action-print"><i class="fa fa-print" aria-hidden="true"></i> Print</button>';
                }
            }
            $(document).find('.header-buttons').html(html);
        }
    }); 

</script>
<?php require '../includes/closing_items.php' ?>
