<?php
    include "../includes/config.php";
    include "../includes/functions.php";
    include "../includes/functions.form.php";
    include "../includes/login_functions.php";
    include "../includes/session.php";
    
    $data = array();
    if($_GET["f"]=="edit") {
            $query = "SELECT * FROM witness WHERE w_id=".$_GET['id'];
            $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
            while($row = mysql_fetch_assoc($recordset)){
                $data[] = $row;
            } 

    } else {
            $data[0]["w_id"] = "";
            $data[0]["b_id"] = "";
            $data[0]["w_name1"] = "";
            $data[0]["w_rcno1"] = "";
            $data[0]["w_doi1"] = "";
            $data[0]["w_poi1"] = "";
            $data[0]["w_name2"] = "";
            $data[0]["w_rcno2"] = "";
            $data[0]["w_doi2"] = "";
            $data[0]["w_poi2"] = "";
    }

?>
<div class="form-container" height="500" width="450" uuid="">
    <form id="form-definition">   
        <input name="db" type="hidden" value="witness">
        <input name="record_id" type="hidden" value="<?=$data[0]["w_id"];?>">
        <span class="label-block">Branch</span>
        <select name="b_id" id="b_id" class="field-input-block">
        <?php
            echo "<option value=''></option>";
            $query = "SELECT * FROM branches ORDER BY b_name ASC";
            $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
            while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                if($data[0]['b_id']==$row['b_id']) {
                    echo "<option value='".$row['b_id']."' selected>".$row['b_name']."</option>";
                } else {
                    echo "<option value='".$row['b_id']."'>".$row['b_name']."</option>";   
                }
            }
        ?>
        </select>
        <span class="label-block-header">Primary</span>
        <span class="label-block">Witness</span>
        <input name="w_name1" type="text" value="<?=$data[0]["w_name1"];?>" class="field-input-block">
        <span class="label-block">RCNO</span>
        <input name="w_rcno1" type="text" value="<?=$data[0]["w_rcno1"];?>" class="field-input-block">
        <span class="label-block">DOI</span>
        <input name="w_doi1" type="text" value="<?=$data[0]["w_doi1"];?>" class="field-input-block">
        <span class="label-block">POI</span>
        <input name="w_poi1" type="text" value="<?=$data[0]["w_poi1"];?>" class="field-input-block">
        <span class="label-block-header">Secondary</span>
        <span class="label-block">Witness</span>
        <input name="w_name2" type="text" value="<?=$data[0]["w_name2"];?>" class="field-input-block">
        <span class="label-block">RCNO</span>
        <input name="w_rcno2" type="text" value="<?=$data[0]["w_rcno2"];?>" class="field-input-block">
        <span class="label-block">DOI</span>
        <input name="w_doi2" type="text" value="<?=$data[0]["w_doi2"];?>" class="field-input-block">
        <span class="label-block">POI</span>
        <input name="w_poi2" type="text" value="<?=$data[0]["w_poi2"];?>" class="field-input-block">


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

