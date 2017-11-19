<?php 
    require '../includes/document_head.php'; 
?>
<div id="wrapper">
    <?php include '../includes/sidebar.php'; ?>
    <?php include '../includes/navigation.php' ?>
    <div class="main_container container_16 clearfix fullsize">
        <div class="table_header">
            <i class="fa fa-th-large" aria-hidden="true"></i> Sales
            <div class="header-buttons"></div>
        </div>
        <div class="page-wrapper">
            <div class="col-xs-12 col-md-12 col-lg-12 search-container">
                <input name="search" type="text" class="form-control input-md field_input search-box" value="" required>
                <button class="toolbar-button search-buttons action-search"><i class="fa fa-times" aria-hidden="true"></i> Clear</button>
                <button class="toolbar-button search-buttons action-searchclear"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
            </div>  
            <div class="panel-page-whole"></div>                           
        </div>
    </div>  
</div>
<script src="sales.js"></script>
<?php require '../includes/closing_items.php' ?>
