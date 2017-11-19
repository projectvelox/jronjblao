<?php
    include "../includes/config.php";
    include "../includes/functions.php";
    include "../includes/functions.form.php";
    include "../includes/login_functions.php";
    include "../includes/session.php";
    
    $data = array();
    if($_GET["f"]=="edit") {
            $query = "SELECT * FROM insurance WHERE i_id=".$_GET['id'];
            $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
            while($row = mysql_fetch_assoc($recordset)){
                $data[] = $row;
            } 

    } else {
            $data[0]["i_id"] = "";
            $data[0]["i_name"] = "";
            $data[0]["i_officeaddress"] = "";
            $data[0]["i_contactperson"] = "";
            $data[0]["i_contactnumber"] = "";
            $data[0]["i_head"] = "";
    }

?>
<div class="form-container" height="386" width="450" uuid="">
    <form id="form-definition">   
        <input name="db" type="hidden" value="insurance">
        <input name="record_id" type="hidden" value="<?=$data[0]["i_id"];?>">
        <span class="label-block">Insurance Company</span>
        <input name="i_name" type="text" value="<?=$data[0]["i_name"];?>" class="field-input-block">
        <span class="label-block">Office Address</span>
        <input name="i_officeaddress" type="text" value="<?=$data[0]["i_officeaddress"];?>" class="field-input-block">
        <span class="label-block">Contact Person</span>
        <input name="i_contactperson" type="text" value="<?=$data[0]["i_contactperson"];?>" class="field-input-block">
        <span class="label-block">Contact Phone</span>
        <input name="i_contactnumber" type="text" value="<?=$data[0]["i_contactnumber"];?>" class="field-input-block">
        <span class="label-block">District Head</span>
        <input name="i_head" type="text" value="<?=$data[0]["i_head"];?>" class="field-input-block">
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

