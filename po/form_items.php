<?php
    include "../includes/config.php";
    include "../includes/functions.php";
    include "../includes/functions.form.php";
    include "../includes/login_functions.php";
    include "../includes/session.php";
    
    $data = array();
    if($_GET["f"]=="edit") {
            $query = "SELECT * FROM po_sub WHERE p_sub_id=".$_GET['item'];
            $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
            while($row = mysql_fetch_assoc($recordset)){
                $data[] = $row;
            } 
    } else {
            $data[0]["p_sub_id"] = "";
            $data[0]["p_id"] = $_GET['p_id'];
            $data[0]["p_product_id"] = "";
            $data[0]["p_qty"] = "";
            $data[0]["p_itemprice"] = "";
    }
?>
<div class="form-container" height="273" width="450" uuid="">
    <form id="form-po-additem">   
        <input name="p_sub_id" type="hidden" value="<?=$data[0]['p_sub_id'];?>" class="field-input-block">
        <input name="p_id" type="hidden" value="<?=$data[0]['p_id'];?>" class="field-input-block">
        <span class="label-block">Product</span>
        <select name="p_product_id" id="p_product_id" class="field-input-block">
        <?php
            echo "<option value=''></option>";
            $query = "SELECT * FROM products ORDER BY p_code ASC";
            $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
            while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                if($data[0]['p_product_id']==$row['p_id']){
                    echo "<option value='".$row['p_id']."' selected>".strtoupper($row['p_code'])." - ".$row['p_name']."</option>";
                } else {
                    echo "<option value='".$row['p_id']."'>".strtoupper($row['p_code'])." - ".$row['p_name']."</option>";
                }
            }
        ?>
        </select>
        <span class="label-block">Quantity</span>
        <input name="p_qty" type="number" value="<?=$data[0]['p_qty'];?>" class="field-input-block">
        <span class="label-block">Unit Price</span>
        <input name="p_itemprice" type="number" value="<?=$data[0]['p_itemprice'];?>" class="field-input-block">
    </form>     
</div>
<div class="form-footer">
    <?php
        if($_GET['f']=="new") {
            echo '<button closewindow="off" class="form-button po-itemsave"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>&nbsp;';
            echo '<button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Cancel</button>';        
        } else {
            echo '<button closewindow="off" class="form-button po-itemupdate"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>&nbsp;';
            echo '<button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Cancel</button>';
        }
    ?>
 </div>

