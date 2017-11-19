<style>
    .jsgrid-grid-header, .jsgrid-grid-body {
        overflow-y: scroll !important;
    }
</style>
<?php
    include "../includes/config.php";
    include "../includes/functions.php";
    include "../includes/functions.form.php";
    include "../includes/login_functions.php";
    include "../includes/session.php";
?>
<div class="form-container bg-lightgray" height="472" width="400" uuid="">
    <form id="form_newtransferrequest"> 
        <div class="col-lg-12">
            <span class="label-block">Request Date</span>
            <input name="t_request_date" type="text" value="" class="field-input-block-thin">
        </div>
        <div class="col-lg-12">
            <span class="label-block">Requested By</span>
            <select name="t_requestby_id" id="t_requestby_id" class="field-input-block-thin">
            <?php
                echo "<option value=''></option>";
                $query = "SELECT * FROM employees WHERE e_is_deleted='N' ORDER BY e_lastname ASC, e_firstname ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    if($_SESSION['user_id']==$row['e_id']) {
                        echo "<option value='".$row['e_id']."' selected>".$row['e_lastname'].",".$row['e_firstname']."</option>";
                    } else {
                        echo "<option value='".$row['e_id']."'>".$row['e_lastname'].",".$row['e_firstname']."</option>";
                    }
                }
            ?>
            </select>
        </div>    
        <div class="col-lg-12">
            <span class="label-block">Origination</span>
            <select name="t_branch_origin_id" id="t_branch_origin_id" class="field-input-block-thin">
            <?php
                echo "<option value='' selected></option>";
                $query = "SELECT * FROM branches ORDER BY b_name ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    if($_SESSION['branch_id']==$row['b_id']){
                        echo "<option value='".$row['b_id']."' selected>".$row['b_name']."</option>";   
                    } else {
                        echo "<option value='".$row['b_id']."'>".$row['b_name']."</option>";   
                    }
                }
            ?>
            </select>
        </div> 
        <div class="col-lg-12">
            <span class="label-block">Destination</span>
            <select name="t_branch_destination_id" id="t_branch_destination_id" class="field-input-block-thin">
            <?php
                echo "<option value='' selected></option>";
                $query = "SELECT * FROM branches ORDER BY b_name ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    echo "<option value='".$row['b_id']."'>".$row['b_name']."</option>";   
                }
            ?>
            </select>
        </div> 
        <div class="col-lg-12">
            <span class="label-block">Notes</span>
            <textarea name="t_notes" rows="4" cols="50" class="textarea-po-input"></textarea>
        </div> 
    </form>
</div>
<div class="form-footer">
    <button closewindow="off" class="form-button transfer_create"><i class="fa fa-plus" aria-hidden="true"></i> Create</button>
    <button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
</div>
<script>
    $('input[name="t_request_date"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
</script>