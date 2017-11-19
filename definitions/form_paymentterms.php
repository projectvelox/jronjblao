<?php
    include "../includes/config.php";
    include "../includes/functions.php";
    include "../includes/functions.form.php";
    include "../includes/login_functions.php";
    include "../includes/session.php";
    
    $data = array();
    if($_GET["f"]=="edit") {
            $query = "SELECT * FROM paymentterms WHERE t_id=".$_GET['id'];
            $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
            while($row = mysql_fetch_assoc($recordset)){
                $data[] = $row;
            } 
    } else {
            $data[0]["t_id"] = "";
            $data[0]["t_code"] = "";
            $data[0]["t_description"] = "";
            $data[0]["t_terms"] = "";
    }

?>
<div class="form-container" height="304" width="450" uuid="">
    <form id="form-definition">   
        <input name="db" type="hidden" value="paymentterms">
        <input name="record_id" type="hidden" value="<?=$data[0]["t_id"];?>">
        <span class="label-block">Code</span>
        <input name="t_code" type="text" value="<?=$data[0]["t_code"];?>" class="field-input-block">
        <span class="label-block">Description</span>
        <textarea name="t_description" rows="4" cols="50" class="field-textarea-block "><?=$data[0]["t_description"];?></textarea>
        <span class="label-block">Term (Months)</span>
        <select name="t_terms" id="t_terms" class="field-input-block">
        <?php
            echo "<option value=''></option>";
            $query = "SELECT * FROM term_months ORDER BY months ASC";
            $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
            while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                if($data[0]['t_terms']==$row['months']) {
                    echo "<option value='".$row['months']."' selected>".$row['months']."</option>";
                } else {
                    echo "<option value='".$row['months']."'>".$row['months']."</option>";   
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

