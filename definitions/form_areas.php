<?php
    include "../includes/config.php";
    include "../includes/functions.php";
    include "../includes/functions.form.php";
    include "../includes/login_functions.php";
    include "../includes/session.php";
    
    $data = array();
    if($_GET["f"]=="edit") {
            $query = "SELECT * FROM areas WHERE a_id=".$_GET['id'];
            $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
            while($row = mysql_fetch_assoc($recordset)){
                $data[] = $row;
            } 
    } else {
            $data[0]["a_id"] = "";
            $data[0]["a_name"] = "";
            $data[0]["a_code"] = "";
            $data[0]["a_branch_id"] = "";
            $data[0]["a_collector_id"] = "";
    }

?>
<div class="form-container" height="331" width="450" uuid="">
    <form id="form-definition">   
        <input name="db" type="hidden" value="areas">
        <input name="record_id" type="hidden" value="<?=$data[0]["a_id"];?>">
        <span class="label-block">Area Name</span>
        <input name="a_name" type="text" value="<?=$data[0]["a_name"];?>" class="field-input-block">
        <span class="label-block">Area Code</span>
        <input name="a_code" type="text" value="<?=$data[0]["a_code"];?>" class="field-input-block">
        <span class="label-block">Branch</span>
        <select name="a_branch_id" id="a_branch_id" class="field-input-block">
        <?php
            echo "<option value=''></option>";
            $query = "SELECT * FROM branches ORDER BY b_name ASC";
            $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
            while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                if($data[0]['a_branch_id']==$row['b_id']) {
                    echo "<option value='".$row['b_id']."' selected>".$row['b_name']."</option>";
                } else {
                    echo "<option value='".$row['b_id']."'>".$row['b_name']."</option>";   
                }
            }
        ?>
        </select>
        <span class="label-block">Collector</span>
        <select name="a_collector_id" id="a_collector_id" class="field-input-block">
        <?php
            echo "<option value=''></option>";
            $query = "SELECT * FROM employees WHERE e_is_deleted='N' ORDER BY e_lastname ASC, e_firstname ASC";
            $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
            while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                if($data[0]['a_collector_id']==$row['e_id']) {
                    echo "<option value='".$row['e_id']."' selected>".$row['e_lastname'].",".$row['e_firstname']."</option>";
                } else {
                    echo "<option value='".$row['e_id']."'>".$row['e_lastname'].",".$row['e_firstname']."</option>";
                }
            }
        ?>
        </select>
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

