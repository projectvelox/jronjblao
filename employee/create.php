<?php
    include "../includes/config.php";
    include "../includes/functions.php";
    include "../includes/functions.form.php";
    include "../includes/login_functions.php";
    include "../includes/session.php"; 
?>

<div class="form-container">
    <form id="form_create_employee">    
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">First Name</span>
            <input name="e_firstname" type="text" class="form-control input-md field_input" value="" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Middle Name</span>
            <input name="e_middlename" type="text" class="form-control input-md field_input" value="" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Last Name</span>
            <input name="e_lastname" type="text" class="form-control input-md field_input" value="" required>
        </div>

        <div class="col-xs-6col-md-6 col-lg-6">
            <span class="mesmer-form-label label-block">User Name</span>
            <input name="e_username" type="text" class="form-control input-md field_input" value="" required>
        </div>
        <div class="col-xs-6 col-md-6 col-lg-6">
            <span class="mesmer-form-label label-block">Password</span>
            <input name="e_password" type="password" class="form-control input-md field_input" value="" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Branch</span>
            <select name="e_branch_id" id="e_branch_id" class="form-control input-md field_input">
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
            <span class="mesmer-form-label label-block">Designation</span>
            <select name="e_designation_id" id="e_designation_id" class="form-control input-md field_input">
            <?php
                echo "<option value=''></option>";
                $query = "SELECT * FROM employee_designations ORDER BY d_name ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    echo "<option value='".$row['d_id']."'>".$row['d_name']."</option>";
                }
            ?>
            </select>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Department</span>
            <select name="e_department_id" id="e_department_id" class="form-control input-md field_input">
            <?php
                echo "<option value=''></option>";
                $query = "SELECT * FROM departments ORDER BY d_name ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    echo "<option value='".$row['d_id']."'>".$row['d_name']."</option>";
                }
            ?>
            </select>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Home Phone</span>
            <input name="e_phone_home" type="text" class="form-control input-md field_input" value="" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Office Phone</span>
            <input name="e_phone_office" type="text" class="form-control input-md field_input" value="" required>
        </div>    
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Mobile Phone</span>
            <input name="e_phone_mobile" type="text" class="form-control input-md field_input" value="" required>
        </div>           
        <div class="col-xs-2 col-md-2 col-lg-2">
            <span class="mesmer-form-label label-block">Gender</span>
            <select name="e_gender" id="e_gender" class="form-control input field_input">
            <?php
                echo "<option value='' selected></option>";
                echo "<option value='M'>Male</option>";
                echo "<option value='F'>Female</option>";
            ?>
            </select>
        </div>  
        <div class="col-xs-3 col-md-3 col-lg-3">
            <span class="mesmer-form-label label-block">Marital Status</span>
            <select name="e_marital_status_id" id="e_marital_status_id" class="form-control input-md field_input">
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
        <div class="col-xs-2 col-md-2 col-lg-2">
            <span class="mesmer-form-label label-block">Birth Date</span>
            <input name="e_birthdate" type="text" class="form-control input-md field_input date_input_lock" value="" required>
        </div>
        <div class="col-xs-2 col-md-2 col-lg-2">
            <span class="mesmer-form-label label-block">Hide Date</span>
            <input name="e_hiredate" type="text" class="form-control input-md field_input date_input_lock" value="" required>
        </div>
        <div class="col-xs-3 col-md-3 col-lg-3">
            <span class="mesmer-form-label label-block">Passport</span>
            <input name="e_passport" type="text" class="form-control input-md field_input" value="" required>
        </div>
        <div class="col-xs-3 col-md-3 col-lg-3">
            <span class="mesmer-form-label label-block">Driver License</span>
            <input name="e_driverlicense" type="text" class="form-control input-md field_input" value="" required>
        </div>
        <div class="col-xs-3 col-md-3 col-lg-3">
            <span class="mesmer-form-label label-block">Philhealth</span>
            <input name="e_philhealth" type="text" class="form-control input-md field_input" value="" required>
        </div>
        <div class="col-xs-3 col-md-3 col-lg-3">
            <span class="mesmer-form-label label-block">SSS Number</span>
            <input name="e_sssno" type="text" class="form-control input-md field_input" value="" required>
        </div>
        <div class="col-xs-3 col-md-3 col-lg-3">
            <span class="mesmer-form-label label-block">TIN No.</span>
            <input name="e_tinno" type="text" class="form-control input-md field_input" value="" required>
        </div>
        <div class="col-xs-6 col-md-6 col-lg-6">
            <span class="mesmer-form-label label-block">Email</span>
            <input name="e_email" type="text" class="form-control input-md field_input" value="" required>
        </div>
        <div class="col-xs-6 col-md-6 col-lg-6">
            <span class="mesmer-form-label label-block">Street Address</span>
            <input name="e_address_street" type="text" class="form-control input-md field_input" value="" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Town</span>
            <input name="e_address_town" type="text" class="form-control input-md field_input" value="" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">City/Province</span>
            <input name="e_address_city" type="text" class="form-control input-md field_input" value="" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Zip Code</span>
            <input name="e_address_zipcode" type="text" class="form-control input-md field_input" value="" required>
        </div>

        <div class="col-xs-3 col-md-3 col-lg-3">
            <span class="mesmer-form-label label-block">Country</span>
            <select name="e_address_country" id="e_address_country" class="form-control input-md field_input">
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
        <div class="col-xs-3 col-md-3 col-lg-3">
            <span class="mesmer-form-label label-block">Citizenship</span>
            <select name="e_citizenship" id="e_citizenship" class="form-control input-md field_input">
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
        <div class="col-xs-2 col-md-2 col-lg-2">
            <span class="mesmer-form-label label-block">Sales Agent</span>
            <select name="e_is_salesagent" id="e_is_salesagent" class="form-control input-md field_input">
            <?php
                echo "<option value='Y'>No</option>";
                echo "<option value='N' selected>Yes</option>";                   
            ?>
            </select>   
        </div>
        <div class="col-xs-2 col-md-2 col-lg-2">
            <span class="mesmer-form-label label-block">Collector</span>
            <select name="e_is_collector" id="e_is_collector" class="form-control input-md field_input">
            <?php
                echo "<option value='Y'>Yes</option>";
                echo "<option value='N' selected>No</option>";
            ?>
            </select>
        </div>
        <div class="col-xs-2 col-md-2 col-lg-2">
            <span class="mesmer-form-label label-block">Active</span>
            <select name="e_active" id="e_active" class="form-control input-md field_input">
            <?php
                echo "<option value='Y' selected>Yes</option>";
                echo "<option value='N'>No</option>";                        
            ?>
            </select> 
        </div>
    </form>     
</div>
<div class="form-footer">
    <button closewindow="off" class="form-button save-employee"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
    <button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Cancel</button>
</div>

<script>
    //$('input[name="e_birthdate"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
    $('input[name="e_hiredate"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
</script>


