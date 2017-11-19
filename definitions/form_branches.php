<?php
    include "../includes/config.php";
    include "../includes/functions.php";
    include "../includes/functions.form.php";
    include "../includes/login_functions.php";
    include "../includes/session.php";
    
    $data = array();
    if($_GET["f"]=="edit") {
            $query = "SELECT * FROM branches WHERE b_id=".$_GET['id'];
            $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
            while($row = mysql_fetch_assoc($recordset)){
                $data[] = $row;
            } 

    } else {
            $data[0]["b_id"] = "";
            $data[0]["b_name"] = "";
            $data[0]["b_address_street"] = "";
            $data[0]["b_address_town"] = "";
            $data[0]["b_address_city"] = "";
            $data[0]["b_address_zipcode"] = "";
            $data[0]["b_phone_office"] = "";
            $data[0]["b_email"] = "";
    }

?>
<div class="form-container" height="500" width="450" uuid="">
    <form id="form-definition">   
        <input name="db" type="hidden" value="branches">
        <input name="record_id" type="hidden" value="<?=$data[0]["b_id"];?>">
        <span class="label-block">Branch</span>
        <input name="b_name" type="text" value="<?=$data[0]["b_name"];?>" class="field-input-block">
        <span class="label-block">Street</span>
        <input name="b_address_street" type="text" value="<?=$data[0]["b_address_street"];?>" class="field-input-block">
        <span class="label-block">Town</span>
        <input name="b_address_town" type="text" value="<?=$data[0]["b_address_town"];?>" class="field-input-block">
        <span class="label-block">City</span>
        <input name="b_address_city" type="text" value="<?=$data[0]["b_address_city"];?>" class="field-input-block">
        <span class="label-block">Zip Code</span>
        <input name="b_address_zipcode" type="text" value="<?=$data[0]["b_address_zipcode"];?>" class="field-input-block">
        <span class="label-block">Office Phone</span>
        <input name="b_phone_office" type="text" value="<?=$data[0]["b_phone_office"];?>" class="field-input-block">
        <span class="label-block">Email</span>
        <input name="b_email" type="text" value="<?=$data[0]["b_email"];?>" class="field-input-block">
    </form>     
</div>
<div class="form-footer">
    <?php
        if($_GET['f']=="new") {
            echo '<button closewindow="off" class="form-button definition-save"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>&nbsp;';
            echo '<button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Close</button>';        
        } else {
            echo '<button closewindow="off" class="form-button definition-update"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>&nbsp;';
            echo '<button data-id="" closewindow="off" class="form-button definition-delete"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>&nbsp;';
            echo '<button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Close</button>';
        }
    ?>
 </div>

