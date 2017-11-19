<?php
    include "../includes/config.php";
    include "../includes/functions.php";
    include "../includes/functions.form.php";
    include "../includes/login_functions.php";
    include "../includes/session.php";

    $query = "SELECT 
                    *,
                    (SELECT cat_name FROM vendors_category WHERE v_category_id=vendors_category.cat_id) AS v_category
                FROM vendors WHERE v_id=".$_GET['id'];
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    $data = array();
    while($row = mysql_fetch_assoc($recordset)){
        $data[] = $row;
   } 

?>

<div class="form-container">
    <form id="form_edit_vendor">   
        <?php textbox("v_id","field_input","60",$data[0]['v_id'],"RW","hidden","1","60");?>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Company Name</span>
            <input disabled name="v_companyname" type="text" class="form-control input-md field_input" value="<?=$data[0]['v_companyname'];?>" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Website</span>
            <input disabled name="v_website" type="text" class="form-control input-md field_input" value="<?=$data[0]['v_website'];?>" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Trade Name</span>
            <input disabled name="v_tradename" type="text" class="form-control input-md field_input" value="<?=$data[0]['v_tradename'];?>" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Contact First Name</span>
            <input disabled name="v_contact_firstname" type="text" class="form-control input-md field_input" value="<?=$data[0]['v_contact_firstname'];?>" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Contact Last Name</span>
            <input disabled name="v_contact_lastname" type="text" class="form-control input-md field_input" value="<?=$data[0]['v_contact_lastname'];?>" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Email</span>
            <input disabled name="v_contact_email" type="text" class="form-control input-md field_input" value="<?=$data[0]['v_contact_email'];?>" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Office Phone</span>
            <input disabled name="v_phone_office" type="text" class="form-control input-md field_input" value="<?=$data[0]['v_phone_office'];?>" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Home Phone</span>
            <input disabled name="v_phone_home" type="text" class="form-control input-md field_input" value="<?=$data[0]['v_phone_home'];?>" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">Mobile Phone</span>
            <input disabled name="v_phone_mobile" type="text" class="form-control input-md field_input" value="<?=$data[0]['v_phone_mobile'];?>" required>
        </div>
        <div class="col-xs-6 col-md-6 col-lg-6">
            <span class="mesmer-form-label label-block">Street Address</span>
            <input disabled name="v_address_street" type="text" class="form-control input-md field_input" value="<?=$data[0]['v_address_street'];?>" required>
        </div>
        <div class="col-xs-6 col-md-6 col-lg-6">
            <span class="mesmer-form-label label-block">Town</span>
            <input disabled name="v_address_town" type="text" class="form-control input-md field_input" value="<?=$data[0]['v_address_town'];?>" required>
        </div>
        <div class="col-xs-4 col-md-4 col-lg-4">
            <span class="mesmer-form-label label-block">City/Province</span>
            <input disabled name="v_address_city" type="text" class="form-control input-md field_input" value="<?=$data[0]['v_address_city'];?>" required>
        </div>
        <div class="col-xs-2 col-md-2 col-lg-2">
            <span class="mesmer-form-label label-block">Zip Code</span>
            <input disabled name="v_address_zipcode" type="text" class="form-control input-md field_input" value="<?=$data[0]['v_address_zipcode'];?>" required>
        </div>
        <div class="col-xs-3 col-md-3 col-lg-3">
            <span class="mesmer-form-label label-block">Country</span>
            <select disabled name="v_address_country" id="v_address_country" class="form-control input-md field_input">
            <?php
                echo "<option value=''></option>";
                $query = "SELECT * FROM options_country ORDER BY value ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    if($data[0]['v_address_country']==$row['id']) {
                        echo "<option value='".$row['id']."' selected>".$row['value']."</option>";
                    } else {
                        echo "<option value='".$row['id']."'>".$row['value']."</option>";
                    }                            
                }
            ?>
            </select>  
        </div>
        <div class="col-xs-3 col-md-3 col-lg-3">
            <span class="mesmer-form-label label-block">Category</span>
            <select disabled name="v_category_id" id="v_category_id" class="form-control input-md field_input">
            <?php
                echo "<option value='' selected></option>";
                $query = "SELECT * FROM vendors_category ORDER BY cat_name ASC";
                $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                    if($data[0]['v_category_id']==$row['cat_id']) {
                        echo "<option value='".$row['cat_id']."' selected>".$row['cat_name']."</option>";
                    } else {
                        echo "<option value='".$row['cat_id']."'>".$row['cat_name']."</option>";
                    }
                }
            ?>
            </select> 
        </div>
    </form>     
</div>
<div class="form-footer">
    <button class="form-button"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
    <button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Cancel</button>
</div>