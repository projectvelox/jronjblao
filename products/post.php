<?php

include "../includes/config.php";

if($_GET['action']=="create") {
    $p_code = addslashes($_POST['p_code']);
    $p_name = addslashes($_POST['p_name']);
    $p_description = addslashes($_POST['p_description']);
    $p_category_id = addslashes($_POST['p_category_id']);
    $p_reorderlevel = addslashes($_POST['p_reorderlevel']);
    $p_sellingprice = addslashes($_POST['p_sellingprice']);
    $p_brand = addslashes($_POST['p_brand']);
    $p_property_1 = addslashes($_POST['p_property_1']);
    $p_property_2 = addslashes($_POST['p_property_2']);
    $p_property_3 = addslashes($_POST['p_property_3']);
    $p_property_4 = addslashes($_POST['p_property_4']);
    $p_property_5 = addslashes($_POST['p_property_5']);
    $p_property_6 = addslashes($_POST['p_property_6']);
    $p_property_7 = addslashes($_POST['p_property_7']);
    $sql = "INSERT INTO products (
              p_code,
              p_name,
              p_description,
              p_category_id,
              p_reorderlevel,
              p_sellingprice,
              p_brand,
              p_color,
              p_property_1,
              p_property_2,
              p_property_3,
              p_property_4,
              p_property_5,
              p_property_6,
              p_property_7
          ) VALUES ( 
              '$p_code',
              '$p_name',
              '$p_description',
              '$p_category_id',  
              '$p_reorderlevel',
              '$p_sellingprice',
              '$p_brand',
              '$p_color',
              '$p_property_1',
              '$p_property_2',
              '$p_property_3',
              '$p_property_4',
              '$p_property_5',
              '$p_property_6',
              '$p_property_7'
          )";
    mysql_query($sql) or die('<div class="message">Error! ' . mysql_error() . '</div>');
} else if ($_GET['action']=="update") {
    $p_id = addslashes($_POST['p_id']);
    $p_code = addslashes($_POST['p_code']);
    $p_name = addslashes($_POST['p_name']);
    $p_description = addslashes($_POST['p_description']);
    $p_category_id = addslashes($_POST['p_category_id']);
    $p_reorderlevel = addslashes($_POST['p_reorderlevel']);
    $p_sellingprice = addslashes($_POST['p_sellingprice']);
    $p_brand = addslashes($_POST['p_brand']);
    $p_color = addslashes($_POST['p_color']);
    $p_property_1 = addslashes($_POST['p_property_1']);
    $p_property_2 = addslashes($_POST['p_property_2']);
    $p_property_3 = addslashes($_POST['p_property_3']);
    $p_property_4 = addslashes($_POST['p_property_4']);
    $p_property_5 = addslashes($_POST['p_property_5']);
    $p_property_6 = addslashes($_POST['p_property_6']);
    $p_property_7 = addslashes($_POST['p_property_7']);
    $sql = "UPDATE products SET 
              p_code='$p_code',
              p_name='$p_name',
              p_description='$p_description',
              p_category_id='$p_category_id',
              p_reorderlevel='$p_reorderlevel',
              p_brand='$p_brand',
              p_color='$p_color',
              p_property_1='$p_property_1',
              p_property_2='$p_property_2',
              p_property_3='$p_property_3',
              p_property_4='$p_property_4',
              p_property_5='$p_property_5',
              p_property_6='$p_property_6',
              p_property_7='$p_property_7'
          WHERE p_id=".$p_id;
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} elseif ($_GET['action'] == "delete") { 
    $p_id = addslashes($_GET['id']);   
    $sql = "UPDATE products SET p_is_deleted='Y' WHERE p_id=".$p_id;
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} 
?>  