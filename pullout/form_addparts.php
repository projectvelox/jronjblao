<?php
    include "../includes/config.php";
    include "../includes/functions.php";
    include "../includes/functions.form.php";
    include "../includes/login_functions.php";
    include "../includes/session.php";
?>
<div class="form-container" height="219" width="450" uuid="">
    <form id="form-addparts">   
        <input name="p_parent_id" type="hidden" value="<?=$_GET['id'];?>" class="field-input-block">  
        <span class="label-block">Parts Name</span>
        <input name="p_parts_name" type="text" value="" class="field-input-block">
        <span class="label-block">Status</span>
        <select name="p_status" id="p_status" class="field-input-block">
            <option value='D'>Damaged</option>
            <option value='D'>Missing</option>
        </select>
    </form>     
</div>
<div class="form-footer">
    <button closewindow="off" class="form-button pullout-saveparts"><i class="fa fa-plus" aria-hidden="true"></i> Add</button>
    <button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Cancel</button>       
 </div>

