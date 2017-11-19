<?php
    include "../includes/config.php";
    include "../includes/functions.php";
    include "../includes/functions.form.php";
    include "../includes/login_functions.php";
    include "../includes/session.php";
?>

<div class="form-container-withchild">
    <form id="form_create_product">    
        <table>
            <tr>
                <td class="form_col1"><?php labelbox("label","Code");?></td>
                <td class="form_col2"><?php textbox("p_code","field_input","20","","RW","text","1","20");?></td>
            </tr>
            <tr>
                <td class="form_col1"><?php labelbox("label","Product Name");?></td>
                <td class="form_col2"><?php textbox("p_name","field_input","70","","RW","text","1","100");?></td>
            </tr>
            <tr>
                <td class="form_col1"><?php labelbox("label","Re-Order Alert");?></td>
                <td class="form_col2"><?php textbox("p_reorderlevel","field_input_number","20","10","RW","number","1","20");?></td>
            </tr>
            <tr>
                <td class="form_col1"><?php labelbox("label","Selling Price");?></td>
                <td class="form_col2"><?php textbox("p_sellingprice","field_input_number","70","0","RW","number","1","70");?></td>
            </tr>
            <tr>
                <td class="form_col1"><?php labelbox("label","Category");?></td>
                <td class="form_col2">
                    <select name="p_category_id" id="p_category_id" class="field_input">
                    <?php
                        echo "<option value=''></option>";
                        $query = "SELECT *,
                                    (SELECT i_name FROM inv_category AS a WHERE a.i_id=inv_category.i_parent_id) AS p_group
                                    FROM inv_category WHERE i_parent_id<>0 ORDER BY i_parent_id ASC,i_name ASC";
                        $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                        while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                            echo "<option value='".$row['i_id']."'>".$row['p_group']." / ".$row['i_name']."</option>";
                        }
                    ?>
                    </select>                
                </td>
            </tr>   
            <tr>
                <td class="form_col1"><?php labelbox("label","Color");?></td>
                <td class="form_col2">
                    <select name="p_color" id="p_color" class="field_input">
                    <?php
                        echo "<option value=''></option>";
                        $query = "SELECT * FROM options_colors ORDER BY color_name ASC";
                        $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                        while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                                echo "<option value='".$row['color_id']."'>".$row['color_name']."</option>";
                        }
                    ?>
                    </select>                
                </td>
            </tr> 
            <tr>
                <td class="form_col1"><?php labelbox("label","Brand");?></td>
                <td class="form_col2">
                    <select name="p_brand" id="p_brand" class="field_input">
                    <?php
                        echo "<option value=''></option>";
                        $query = "SELECT * FROM brands ORDER BY b_name ASC";
                        $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                        while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                                echo "<option value='".$row['b_id']."'>".$row['b_name']."</option>";
                        }
                    ?>
                    </select>                
                </td>
            </tr> 
            <tr>
                <td class="form_col1"><?php labelbox("label","Description");?></td>
                <td class="form_col2">
                    <textarea name="p_description" rows="4" cols="50" class="field-textarea-block "></textarea>
                </td>    
            </tr>       
            <tr>
                <td class="form_col1"><?php labelbox("label","Properties");?></td>
                <td class="form_col2">
                    <?php textbox("p_property_1","property_input","50","","RW","text","1","50");?>
                    <?php textbox("p_property_2","property_input","50","","RW","text","1","50");?>
                    <?php textbox("p_property_3","property_input","50","","RW","text","1","50");?>
                    <?php textbox("p_property_4","property_input","50","","RW","text","1","50");?>
                    <?php textbox("p_property_5","property_input","50","","RW","text","1","50");?>
                    <?php textbox("p_property_6","property_input","50","","RW","text","1","50");?>
                    <?php textbox("p_property_7","property_input","50","","RW","text","1","50");?>        
                </td>
            </tr>   
        </table> 
    </form>     
</div>
<div class="form-footer">
    <button closewindow="off" class="form-button save-product"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
    <button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Cancel</button>
</div>

