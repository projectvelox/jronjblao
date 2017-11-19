<?php 
    require '../includes/document_head.php'; 
    $data = '[';
    $data .= ']';  

?>

<div id="wrapper">
    <?php include '../includes/sidebar.php'; ?>
    <?php include '../includes/navigation.php' ?>
    <div class="main_container container_16 clearfix fullsize">
        <div class="table_header">
            <i class="fa fa-th-large" aria-hidden="true"></i> Definitions
            <button data-pageurl="colors.php" data-pageheader="Colors" class="toolbar-button definition-new"><i class="fa fa-plus" aria-hidden="true"></i> New Record</button>
        </div>
        <div class="page-wrapper">
            <div class="panel-sidebar"><div id="tree"></div></div>                           
            <div class="panel-page"></div>                           
        </div>
    </div>  
</div>
<script src="definitions.js"></script>
<?php require '../includes/closing_items.php' ?>
