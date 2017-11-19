<?php
    include "../includes/config.php";
    include "../includes/functions.php";
    include "../includes/functions.form.php";
    include "../includes/login_functions.php";
    include "../includes/session.php";
    
    $data = array();
    if($_GET["f"]=="edit") {
            $query = "SELECT * FROM inv_category WHERE i_id=".$_GET['id'];
            $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
            while($row = mysql_fetch_assoc($recordset)){
                $data[] = $row;
            } 
    } else {
            $data[0]["i_id"] = "";
            $data[0]["i_name"] = "";
            $data[0]["i_parent_id"] = "";
            $data[0]["i_code"] = "";
    }

?>
<div class="form-container" height="273" width="450" uuid="">
    <form id="form-definition">   
        <input name="db" type="hidden" value="inventorycategory">
        <input name="record_id" type="hidden" value="<?=$data[0]["i_id"];?>">
        <span class="label-block">Category</span>
        <input name="i_name" type="text" value="<?=$data[0]["i_name"];?>" class="field-input-block">
        <span class="label-block">Group</span>
        <select name="i_parent_id" id="i_parent_id" class="field-input-block">
        <?php
            echo "<option value=''></option>";
            $query = "SELECT * FROM inv_category WHERE i_parent_id=0 ORDER BY i_name";
            $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
            while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                if($data[0]['i_parent_id']==$row['i_id']) {
                    echo "<option value='".$row['i_id']."' selected>".$row['i_name']."</option>";
                } else {
                    echo "<option value='".$row['i_id']."'>".$row['i_name']."</option>";   
                }
            }
        ?>
        </select>
        <span class="label-block">Code</span>
        <input name="i_code" type="text" value="<?=$data[0]["i_code"];?>" class="field-input-block">
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

