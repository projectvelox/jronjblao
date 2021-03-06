<?php
    include "../includes/config.php";
    include "../includes/functions.php";
    include "../includes/functions.form.php";
    include "../includes/login_functions.php";
    include "../includes/session.php";


    $query = "SELECT * FROM products WHERE p_id=".$_GET['id'];
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    $data = array();
    while($row = mysql_fetch_assoc($recordset)){
        $data[] = $row;
   }  

?>

<div class="form-container-viewer">
    <form id="form_edit_product">    
        <table>
            <?php textbox("p_id","field_readonly_block","",$data[0]['p_id'],"","hidden","","");?>
            <tr>
                <td class="form_col1"><?php labelbox("label","Code");?></td>
                <td class="form_col2"><?php textbox("p_code","field_readonly_block","20",$data[0]['p_code'],"R","text","1","20");?></td>
            </tr>
            <tr>
                <td class="form_col1"><?php labelbox("label","Product Name");?></td>
                <td class="form_col2"><?php textbox("p_name","field_readonly_block","70",$data[0]['p_name'],"R","text","1","100");?></td>
            </tr>
            <tr>
                <td class="form_col1"><?php labelbox("label","Re-Order Alert");?></td>
                <td class="form_col2"><?php textbox("p_reorderlevel","field_readonly_block_number","20",$data[0]['p_reorderlevel'],"R","number","1","20");?></td>
            </tr>
            <tr>
                <td class="form_col1"><?php labelbox("label","Selling Price");?></td>
                <td class="form_col2"><?php textbox("p_sellingprice","field_readonly_block_number","70",$data[0]['p_sellingprice'],"R","number","1","70");?></td>
            </tr>
            <tr>
                <td class="form_col1"><?php labelbox("label","Category");?></td>
                <td class="form_col2">
                    <select name="p_category_id" id="p_category_id" class="field_readonly_block" disabled>
                    <?php
                        echo "<option value=''></option>";
                        $query = "SELECT *,
                                    (SELECT i_name FROM inv_category AS a WHERE a.i_id=inv_category.i_parent_id) AS p_group
                                    FROM inv_category WHERE i_parent_id<>0 ORDER BY i_parent_id ASC,i_name ASC";
                        $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                        while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                            if($data[0]['p_category_id']==$row['i_id']) {
                                echo "<option value='".$row['i_id']."' selected>".$row['p_group']." / ".$row['i_name']."</option>";
                            } else {
                                echo "<option value='".$row['i_id']."'>".$row['p_group']." / ".$row['i_name']."</option>";
                            }
                        }
                    ?>
                    </select>                
                </td>
            </tr>   
            <tr>
                <td class="form_col1"><?php labelbox("label","Color");?></td>
                <td class="form_col2">
                    <select name="p_color" id="p_color" class="field_readonly_block" disabled>
                    <?php
                        echo "<option value=''></option>";
                        $query = "SELECT * FROM options_colors ORDER BY color_name ASC";
                        $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                        while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                            if($data[0]['p_color']==$row['color_id']) {
                                echo "<option value='".$row['color_id']."' selected>".$row['color_name']."</option>";
                            } else {
                                echo "<option value='".$row['color_id']."'>".$row['color_name']."</option>";
                            }
                        }
                    ?>
                    </select>                
                </td>
            </tr> 
            <tr>
                <td class="form_col1"><?php labelbox("label","Brand");?></td>
                <td class="form_col2">
                    <select name="p_brand" id="p_brand" class="field_readonly_block" disabled>
                    <?php
                        echo "<option value=''></option>";
                        $query = "SELECT * FROM brands ORDER BY b_name ASC";
                        $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                        while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                            if($data[0]['p_brand']==$row['b_id']) {
                                echo "<option value='".$row['b_id']."' selected>".$row['b_name']."</option>";
                            } else {
                                echo "<option value='".$row['b_id']."'>".$row['b_name']."</option>";
                            }
                        }
                    ?>
                    </select>                
                </td>
            </tr> 
            <tr>
                <td class="form_col1"><?php labelbox("label","Description");?></td>
                <td class="form_col2">
                    <textarea name="p_description" rows="4" cols="50" class="field_readonly_block_textarea" disabled><?=$data[0]['p_description'];?></textarea>
                </td>    
            </tr>
            <tr>
                <td class="form_col1"><?php labelbox("label","Properties");?></td>
                <td class="form_col2">
                    <?php textbox("p_property_1","property_input_readonly","50",$data[0]['p_property_1'],"R","text","1","50");?>
                    <?php textbox("p_property_2","property_input_readonly","50",$data[0]['p_property_2'],"R","text","1","50");?>
                    <?php textbox("p_property_3","property_input_readonly","50",$data[0]['p_property_3'],"R","text","1","50");?>
                    <?php textbox("p_property_4","property_input_readonly","50",$data[0]['p_property_4'],"R","text","1","50");?>
                    <?php textbox("p_property_5","property_input_readonly","50",$data[0]['p_property_5'],"R","text","1","50");?>
                    <?php textbox("p_property_6","property_input_readonly","50",$data[0]['p_property_6'],"R","text","1","50");?>
                    <?php textbox("p_property_7","property_input_readonly","50",$data[0]['p_property_7'],"R","text","1","50");?>        
                </td>
            </tr>

        </table> 
    </form>     
</div>
<div class="form-footer">
    <button class="form-button"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
    <button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
</div>

