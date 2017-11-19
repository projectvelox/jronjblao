<?php
    include "../includes/config.php";
    include "../includes/functions.php";
    include "../includes/functions.form.php";
    include "../includes/login_functions.php";
    include "../includes/session.php";
?>

<div class="form-container">
    <form id="form_create_customer">    
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">First Name</span>
            <input name="c_firstname" type="text" class="form-control input-md field_input" value="" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Middle Name</span>
            <input name="c_middlename" type="text" class="form-control input-md field_input" value="" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Last Name</span>
            <input name="c_lastname" type="text" class="form-control input-md field_input" value="" required>
        </div>
        <div class="col-xs-2 col-md-2 col-lg-2">
            <span class="mesmer-form-label label-block">Gender</span>
            <select name="c_gender" id="c_gender" class="form-control input-md field_input">
                <?php
                    echo "<option value='' selected></option>";
                    echo "<option value='M'>Male</option>";
                    echo "<option value='F'>Female</option>";
                ?>
            </select>
        </div>
        <div class="col-xs-2 col-md-2 col-lg-2">
            <span class="mesmer-form-label label-block">Marital Status</span>
            <select name="c_civil_status" id="c_civil_status" class="form-control input-md field_input">
            <?php
                echo "<option value=''></option>";
                $query = "SELECT * FROM options_civilstatus ORDER BY value ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {  
                    echo "<option value='".$row['id']."'>".$row['value']."</option>";
                }
            ?>
            </select>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Area</span>
            <select name="c_area" id="c_area" class="form-control input-md field_input">
            <?php
                echo "<option value='' selected></option>";
                $query = "SELECT * FROM areas ORDER BY a_name ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    echo "<option value='".$row['a_id']."'>".$row['a_name']."</option>";
                }
            ?>
            </select> 
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Branch</span>
            <select name="c_branch_id" id="c_branch_id" class="form-control input-md field_input">
            <?php
                echo "<option value=''></option>";
                $query = "SELECT * FROM branches ORDER BY b_name ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    echo "<option value='".$row['b_id']."'>".$row['b_name']."</option>";   
                }
            ?>
            </select>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Home Phone</span>
            <input name="c_phone_home" type="text" class="form-control input-md field_input" value="" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Office Phone</span>
            <input name="c_phone_office" type="text" class="form-control input-md field_input" value="" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Mobile Phone</span>
            <input name="c_phone_mobile" type="text" class="form-control input-md field_input" value="" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Email</span>
            <input name="c_email" type="text" class="form-control input-md field_input" value="" required>
        </div>
        <div class="col-xs-2 col-md-2 col-lg-2">
            <span class="mesmer-form-label label-block">Birth Date</span>
            <input name="c_birthdate" type="text" class="form-control input-md field_input date_input_lock" value="" required>
        </div>
        <div class="col-xs-2 col-md-2 col-lg-2">
            <span class="mesmer-form-label label-block">Active</span>
            <select name="c_active" id="c_active" class="form-control input-md field_input">
            <?php
                echo "<option value='Y' selected>Yes</option>";
                echo "<option value='N'>No</option>";                        
            ?>
            </select>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Citizenship</span>
            <select name="c_citizenship" id="c_citizenship" class="form-control input-md field_input">
            <?php
                echo "<option value='' selected></option>";
                $query = "SELECT * FROM options_country ORDER BY value ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    if($row['id']==174) {
                        echo "<option value='".$row['id']."' selected>".$row['value']."</option>";
                    } else {
                        echo "<option value='".$row['id']."'>".$row['value']."</option>";
                    }
                }
            ?>
            </select>  
        </div>
        <div class="col-xs-6 col-md-6 col-lg-6">
            <span class="mesmer-form-label label-block">Street Address</span>
            <input name="c_address_street" type="text" class="form-control input-md field_input" value="" required>
        </div>
        <div class="col-xs-6 col-md-6 col-lg-6">
            <span class="mesmer-form-label label-block">Town</span>
            <input name="c_address_town" type="text" class="form-control input-md field_input" value="" required>
        </div>
        <div class="col-xs-5 col-md-5 col-lg-5">
            <span class="mesmer-form-label label-block">City/Province</span>
            <input name="c_address_city" type="text" class="form-control input-md field_input" value="" required>
        </div>
        <div class="col-xs-2 col-md-2 col-lg-2">
            <span class="mesmer-form-label label-block">Zip Code</span>
            <input name="c_address_zipcode" type="text" class="form-control input-md field_input" value="" required>
        </div>
        <div class="col-xs-5 col-md-5 col-lg-5">
            <span class="mesmer-form-label label-block">Country</span>
            <select name="c_address_country" id="c_address_country" class="form-control input-md field_input">
            <?php
                echo "<option value='' selected></option>";
                $query = "SELECT * FROM options_country ORDER BY value ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    if($row['id']==174) {
                        echo "<option value='".$row['id']."' selected>".$row['value']."</option>";
                    } else {
                        echo "<option value='".$row['id']."'>".$row['value']."</option>";
                    }
                }
            ?>
            </select> 
        </div>
    </form>     
</div>
<div class="form-footer">
    <button closewindow="off" class="form-button save-customer"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
    <button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Cancel</button>
</div>
<script>
    //$('input[name="c_birthdate"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
</script>