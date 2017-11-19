<?php
    include "../includes/config.php";
    include "../includes/functions.php";
    include "../includes/functions.form.php";
    include "../includes/login_functions.php";
    include "../includes/session.php";

    $query = "SELECT *,
                    (SELECT b_name FROM branches WHERE e_branch_id=branches.b_id) AS branch_name,
                    (SELECT d_name FROM departments WHERE e_department_id=departments.d_id) AS department_name,
                    (SELECT d_name FROM employee_designations WHERE e_designation_id=employee_designations.d_id) AS designation,
                    (SELECT value FROM options_civilstatus WHERE e_marital_status_id=options_civilstatus.id) AS maritalstatus,
                    (SELECT value FROM options_country WHERE e_address_country=options_country.id) AS country,
                    (SELECT value FROM options_country WHERE e_citizenship=options_country.id) AS citizenship,
                    IF(e_gender = 'M','Male','Female') AS gender,
                    IF(e_is_salesagent = 'Y','Yes','No') AS salesagent,
                    IF(e_active = 'Y','Yes','No') AS status
                    FROM employees WHERE e_id=".$_GET['id'];
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    $data = array();
    while($row = mysql_fetch_assoc($recordset)){
        $data[] = $row;
   }  
?>

<div class="form-container">
    <form id="form_edit_employee">    
        <?php textbox("e_id","field_input","60",$data[0]['e_id'],"RW","hidden","1","60");?>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">First Name</span>
            <input name="e_firstname" type="text" class="form-control input-md field_input" value="<?=$data[0]['e_firstname'];?>" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Middle Name</span>
            <input name="e_middlename" type="text" class="form-control input-md field_input" value="<?=$data[0]['e_middlename'];?>" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Last Name</span>
            <input name="e_lastname" type="text" class="form-control input-md field_input" value="<?=$data[0]['e_lastname'];?>" required>
        </div>

        <div class="col-xs-6col-md-6 col-lg-6">
            <span class="mesmer-form-label label-block">User Name</span>
            <input name="e_username" type="text" class="form-control input-md field_input" value="<?=$data[0]['e_username'];?>" required>
        </div>
        <div class="col-xs-6 col-md-6 col-lg-6">
            <span class="mesmer-form-label label-block">Password</span>
            <input name="e_password" type="password" class="form-control input-md field_input" value="<?=$data[0]['e_password'];?>" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Branch</span>
            <select name="e_branch_id" id="e_branch_id" class="form-control input-md field_input">
            <?php
                echo "<option value=''></option>";
                $query = "SELECT * FROM branches ORDER BY b_name ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    if($data[0]['e_branch_id']==$row['b_id']) {
                        echo "<option value='".$row['b_id']."' selected>".$row['b_name']."</option>";
                    } else {
                        echo "<option value='".$row['b_id']."'>".$row['b_name']."</option>";   
                    }
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
                    if($data[0]['e_designation_id']==$row['d_id']) {
                        echo "<option value='".$row['d_id']."' selected>".$row['d_name']."</option>";
                    } else {
                        echo "<option value='".$row['d_id']."'>".$row['d_name']."</option>";
                    }    
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
                    if($data[0]['e_department_id']==$row['d_id']) {
                        echo "<option value='".$row['d_id']."' selected>".$row['d_name']."</option>";
                    } else {
                        echo "<option value='".$row['d_id']."'>".$row['d_name']."</option>";
                    }
                }
            ?>
            </select>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Home Phone</span>
            <input name="e_phone_home" type="text" class="form-control input-md field_input" value="<?=$data[0]['e_phone_home'];?>" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Office Phone</span>
            <input name="e_phone_office" type="text" class="form-control input-md field_input" value="<?=$data[0]['e_phone_office'];?>" required>
        </div>    
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Mobile Phone</span>
            <input name="e_phone_mobile" type="text" class="form-control input-md field_input" value="<?=$data[0]['e_phone_mobile'];?>" required>
        </div>           
        <div class="col-xs-2 col-md-2 col-lg-2">
            <span class="mesmer-form-label label-block">Gender</span>
            <select name="e_gender" id="e_gender" class="form-control input field_input">
            <?php
                if($data[0]['e_gender']=="M") {
                    echo "<option value=''></option>";
                    echo "<option value='M' selected>Male</option>";
                    echo "<option value='F'>Female</option>";                        
                } else if($data[0]['e_gender']=="F") {
                    echo "<option value=''></option>";
                    echo "<option value='M' selected>Male</option>";
                    echo "<option value='F' selected>Female</option>";                            
                } else {
                    echo "<option value='' selected></option>";
                    echo "<option value='M'>Male</option>";
                    echo "<option value='F'>Female</option>";
                }
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
                    if($data[0]['e_marital_status_id']==$row['id']) {
                        echo "<option value='".$row['id']."' selected>".$row['value']."</option>";
                    } else {    
                        echo "<option value='".$row['id']."'>".$row['value']."</option>";
                    }
                }
            ?>
            </select>
        </div>  
        <div class="col-xs-2 col-md-2 col-lg-2">
            <span class="mesmer-form-label label-block">Birth Date</span>
            <input name="e_birthdate" type="text" class="form-control input-md field_input date_input_lock" value="<?=$data[0]['e_birthdate'];?>" required>
        </div>
        <div class="col-xs-2 col-md-2 col-lg-2">
            <span class="mesmer-form-label label-block">Hide Date</span>
            <input name="e_hiredate" type="text" class="form-control input-md field_input date_input_lock" value="<?=date('m/d/Y',strtotime($data[0]['e_hiredate']));?>" required>
        </div>
        <div class="col-xs-3 col-md-3 col-lg-3">
            <span class="mesmer-form-label label-block">Passport</span>
            <input name="e_passport" type="text" class="form-control input-md field_input" value="<?=$data[0]['e_passport'];?>" required>
        </div>
        <div class="col-xs-3 col-md-3 col-lg-3">
            <span class="mesmer-form-label label-block">Driver License</span>
            <input name="e_driverlicense" type="text" class="form-control input-md field_input" value="<?=$data[0]['e_driverlicense'];?>" required>
        </div>
        <div class="col-xs-3 col-md-3 col-lg-3">
            <span class="mesmer-form-label label-block">Philhealth</span>
            <input name="e_philhealth" type="text" class="form-control input-md field_input" value="<?=$data[0]['e_philhealth'];?>" required>
        </div>
        <div class="col-xs-3 col-md-3 col-lg-3">
            <span class="mesmer-form-label label-block">SSS Number</span>
            <input name="e_sssno" type="text" class="form-control input-md field_input" value="<?=$data[0]['e_sssno'];?>" required>
        </div>
        <div class="col-xs-3 col-md-3 col-lg-3">
            <span class="mesmer-form-label label-block">TIN No.</span>
            <input name="e_tinno" type="text" class="form-control input-md field_input" value="<?=$data[0]['e_tinno'];?>" required>
        </div>
        <div class="col-xs-6 col-md-6 col-lg-6">
            <span class="mesmer-form-label label-block">Email</span>
            <input name="e_email" type="text" class="form-control input-md field_input" value="<?=$data[0]['e_email'];?>" required>
        </div>
        <div class="col-xs-6 col-md-6 col-lg-6">
            <span class="mesmer-form-label label-block">Street Address</span>
            <input name="e_address_street" type="text" class="form-control input-md field_input" value="<?=$data[0]['e_address_street'];?>" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Town</span>
            <input name="e_address_town" type="text" class="form-control input-md field_input" value="<?=$data[0]['e_address_town'];?>" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">City/Province</span>
            <input name="e_address_city" type="text" class="form-control input-md field_input" value="<?=$data[0]['e_address_city'];?>" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Zip Code</span>
            <input name="e_address_zipcode" type="text" class="form-control input-md field_input" value="<?=$data[0]['e_address_zipcode'];?>" required>
        </div>

        <div class="col-xs-3 col-md-3 col-lg-3">
            <span class="mesmer-form-label label-block">Country</span>
            <select name="e_address_country" id="e_address_country" class="form-control input-md field_input">
            <?php
                echo "<option value='' selected></option>";
                $query = "SELECT * FROM options_country ORDER BY value ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    if($data[0]['e_address_country']==$row['id']) {
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
                    if($data[0]['e_citizenship']==$row['id']) {
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
                if($data[0]['e_is_salesagent']=="Y") {
                    echo "<option value='Y' selected>No</option>";
                    echo "<option value='N'>Yes</option>";                   
                } else {
                    echo "<option value='Y'>No</option>";
                    echo "<option value='N' selected>Yes</option>";                       
                } 
            ?>
            </select>   
        </div>
        <div class="col-xs-2 col-md-2 col-lg-2">
            <span class="mesmer-form-label label-block">Collector</span>
            <select name="e_is_collector" id="e_is_collector" class="form-control input-md field_input">
            <?php
                if($data[0]['e_is_collector']=="Y") {
                    echo "<option value='Y' selected>Yes</option>";
                    echo "<option value='N'>No</option>";
                } else {
                    echo "<option value='Y'>Yes</option>";
                    echo "<option value='N' selected>No</option>";
                }
            ?>
            </select>
        </div>
        <div class="col-xs-2 col-md-2 col-lg-2">
            <span class="mesmer-form-label label-block">Active</span>
            <select name="e_active" id="e_active" class="form-control input-md field_input">
            <?php
                if($data[0]['e_active']=="Y") {
                    echo "<option value='Y' selected>Yes</option>";
                    echo "<option value='N'>No</option>";                        
                } else if($data[0]['e_active']=="N") {
                    echo "<option value='Y'>Yes</option>";
                    echo "<option value='N' selected>No</option>";                          
                } else {
                    echo "<option value='Y'>Yes</option>";
                    echo "<option value='N'>No</option>";                          
                } 
            ?>
            </select> 
        </div>
    </form>     
</div>
<div class="form-footer">
    <button closewindow="off" class="form-button update-employee"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
    <button data-id="<?php echo $data[0]['e_id'];?>" closewindow="off" class="form-button delete-employee"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
    <button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
</div>

<script>
    //$('input[name="e_birthdate"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
    $('input[name="e_hiredate"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
</script>