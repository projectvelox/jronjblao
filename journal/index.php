<?php 
    require '../includes/document_head.php'; 
?>
<div id="wrapper">
    <?php include '../includes/sidebar.php'; ?>
    <?php include '../includes/navigation.php' ?>
    <div class="main_container container_16 clearfix fullsize">
        <div class="table_header">
            <i class="fa fa-th-large" aria-hidden="true"></i> Journal Transactions
            <div class="header-buttons"></div>
        </div>
        <div class="page-wrapper">
            <div class="panel-sidebar"><div id="tree"></div></div>                           
            <div class="panel-page"></div>                           
        </div>
    </div>  
</div>
<script src="journal.js"></script>
<?php require '../includes/closing_items.php' ?>
