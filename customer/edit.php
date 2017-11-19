<?php
    include "../includes/config.php";
    include "../includes/functions.php";
    include "../includes/functions.form.php";
    include "../includes/login_functions.php";
    include "../includes/session.php";

    $query = "SELECT *,
                    (SELECT b_name FROM branches WHERE c_branch_id=branches.b_id) AS branch_name,
                    (SELECT value FROM options_civilstatus WHERE c_civil_status=options_civilstatus.id) AS maritalstatus,
                    (SELECT value FROM options_country WHERE c_address_country=options_country.id) AS country,
                    (SELECT value FROM options_country WHERE c_citizenship=options_country.id) AS citizenship,
                    (SELECT a_name FROM areas WHERE c_area=areas.a_id) AS area,                   
                    IF(c_gender = 'M','Male','Female') AS gender,
                    IF(c_active = 'Y','Yes','No') AS status
                    FROM customers WHERE c_id=".$_GET['id'];
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    $data = array();
    while($row = mysql_fetch_assoc($recordset)){
        $data[] = $row;
   }  
?>

<div class="form-container">
    <form id="form_edit_customer">    
        <?php textbox("c_id","field_input","60",$data[0]['c_id'],"RW","hidden","1","60");?>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">First Name</span>
            <input name="c_firstname" type="text" class="form-control input-md field_input" value="<?=$data[0]['c_firstname'];?>" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Middle Name</span>
            <input name="c_middlename" type="text" class="form-control input-md field_input" value="<?=$data[0]['c_middlename'];?>" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Last Name</span>
            <input name="c_lastname" type="text" class="form-control input-md field_input" value="<?=$data[0]['c_lastname'];?>" required>
        </div>
        <div class="col-xs-2 col-md-2 col-lg-2">
            <span class="mesmer-form-label label-block">Gender</span>
            <select name="c_gender" id="c_gender" class="form-control input-md field_input">
                <?php
                    if($data[0]['c_gender']=="M") {
                        echo "<option value=''></option>";
                        echo "<option value='M' selected>Male</option>";
                        echo "<option value='F'>Female</option>";                        
                    } else if($data[0]['c_gender']=="F") {
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
        <div class="col-xs-2 col-md-2 col-lg-2">
            <span class="mesmer-form-label label-block">Marital Status</span>
            <select name="c_civil_status" id="c_civil_status" class="form-control input-md field_input">
            <?php
                echo "<option value=''></option>";
                $query = "SELECT * FROM options_civilstatus ORDER BY value ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    if($data[0]['c_civil_status']==$row['id']) {
                        echo "<option value='".$row['id']."' selected>".$row['value']."</option>";
                    } else {    
                        echo "<option value='".$row['id']."'>".$row['value']."</option>";
                    }
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
                    if($data[0]['c_area']==$row['a_id']) {
                        echo "<option value='".$row['a_id']."' selected>".$row['a_name']."</option>";
                    } else {
                        echo "<option value='".$row['a_id']."'>".$row['a_name']."</option>";
                    }
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
                    if($data[0]['c_branch_id']==$row['b_id']) {
                        echo "<option value='".$row['b_id']."' selected>".$row['b_name']."</option>";
                    } else {
                        echo "<option value='".$row['b_id']."'>".$row['b_name']."</option>";   
                    }
                }
            ?>
            </select>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Home Phone</span>
            <input name="c_phone_home" type="text" class="form-control input-md field_input" value="<?=$data[0]['c_phone_home'];?>" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Office Phone</span>
            <input name="c_phone_office" type="text" class="form-control input-md field_input" value="<?=$data[0]['c_phone_office'];?>" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Mobile Phone</span>
            <input name="c_phone_mobile" type="text" class="form-control input-md field_input" value="<?=$data[0]['c_phone_mobile'];?>" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Email</span>
            <input name="c_email" type="text" class="form-control input-md field_input" value="<?=$data[0]['c_email'];?>" required>
        </div>
        <div class="col-xs-2 col-md-2 col-lg-2">
            <span class="mesmer-form-label label-block">Birth Date</span>
            <input name="c_birthdate" type="text" class="form-control input-md field_input date_input_lock" value="<?=$data[0]['c_birthdate'];?>" required>
        </div>
        <div class="col-xs-2 col-md-2 col-lg-2">
            <span class="mesmer-form-label label-block">Active</span>
            <select name="c_active" id="c_active" class="form-control input-md field_input">
            <?php
                if($data[0]['c_active']=="Y") {
                    echo "<option value='Y' selected>Yes</option>";
                    echo "<option value='N'>No</option>";                        
                } else if($data[0]['c_active']=="N") {
                    echo "<option value='Y'>Yes</option>";
                    echo "<option value='N' selected>No</option>";                          
                } else {
                    echo "<option value='Y'>Yes</option>";
                    echo "<option value='N'>No</option>";                          
                } 
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
                    if($data[0]['c_citizenship']==$row['id']) {
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
            <input name="c_address_street" type="text" class="form-control input-md field_input" value="<?=$data[0]['c_address_street'];?>" required>
        </div>
        <div class="col-xs-6 col-md-6 col-lg-6">
            <span class="mesmer-form-label label-block">Town</span>
            <input name="c_address_town" type="text" class="form-control input-md field_input" value="<?=$data[0]['c_address_town'];?>" required>
        </div>
        <div class="col-xs-5 col-md-5 col-lg-5">
            <span class="mesmer-form-label label-block">City/Province</span>
            <input name="c_address_city" type="text" class="form-control input-md field_input" value="<?=$data[0]['c_address_city'];?>" required>
        </div>
        <div class="col-xs-2 col-md-2 col-lg-2">
            <span class="mesmer-form-label label-block">Zip Code</span>
            <input name="c_address_zipcode" type="text" class="form-control input-md field_input" value="<?=$data[0]['c_address_zipcode'];?>" required>
        </div>
        <div class="col-xs-5 col-md-5 col-lg-5">
            <span class="mesmer-form-label label-block">Country</span>
            <select name="c_address_country" id="c_address_country" class="form-control input-md field_input">
            <?php
                echo "<option value='' selected></option>";
                $query = "SELECT * FROM options_country ORDER BY value ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    if($data[0]['c_address_country']==$row['id']) {
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
    <button closewindow="off" class="form-button create-ci"><i class="fa fa-eye" aria-hidden="true"></i> Credit Investigation</button>
    <button closewindow="off" class="form-button update-customer"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
    <button data-id="<?php echo $data[0]['c_id'];?>" closewindow="off" class="form-button delete-customer"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
    <button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
</div>
<script>
    //$('input[name="c_birthdate"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
</script>