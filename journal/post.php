<?php

include "../includes/config.php";
session_start();


if($_GET['action']=="verify") {
    $sql = "UPDATE journal_main SET j_status='A',j_postedby_date='".date('Y-m-d')."',j_postedby_id='".$_SESSION['user_id']."' WHERE j_id=".$_GET['id'];
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
}

/*
if($_GET['action']=="deleteitem") {
    $sql = "DELETE FROM pullout_sub WHERE p_sub_id=".$_GET['id'];      
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} else if($_GET['action']=="addparts") {
    $p_parent_id = addslashes($_POST['p_parent_id']);
    $p_parts_name = addslashes($_POST['p_parts_name']);
    $p_status = addslashes($_POST['p_status']);
    $sql = "INSERT INTO pullout_sub (
            p_parent_id,p_parts_name,p_status) 
            VALUES ('$p_parent_id','$p_parts_name','$p_status')";
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} else if($_GET['action']=="update") {
    $p_date = date("Y-m-d",strtotime($_POST['p_date']));
    $p_id = addslashes($_POST['p_id']);
    $p_attestedby_id = addslashes($_POST['p_attestedby_id']);
    $p_inventoryby_id = addslashes($_POST['p_inventoryby_id']);
    $p_notes = addslashes($_POST['p_notes']);
    $sql = "UPDATE pullout_main SET 
                p_attestedby_id='".$p_attestedby_id."',
                p_inventoryby_id='".$p_inventoryby_id."',
                p_notes='".$p_notes."',
                p_date='".$p_date."'
            WHERE p_id=".$p_id;
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} else if($_GET['action']=="verify") {
    $sql = "UPDATE pullout_main SET p_status='A',p_checkedby_id='".$_SESSION['user_id']."' WHERE p_id=".$_GET['id'];
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} else if($_GET['action']=="received") {
    $sql = "UPDATE pullout_main SET p_status='P',p_recievedby_id='".$_SESSION['user_id']."' WHERE p_id=".$_GET['id'];
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    $query_sub = "SELECT * FROM pullout_main WHERE p_id=".$_GET['id'];
    $recordset_sub = mysql_query($query_sub) or die('Query failed: ' . mysql_error());
    while ($row_sub = mysql_fetch_array($recordset_sub, MYSQL_ASSOC)) {
        $product_id = $row_sub['p_product_id'];
    }
    $sql = "UPDATE inventory SET p_ispulledout='Y' WHERE p_id=".$product_id;
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} else if($_GET['action']=="reedem") {
    $redeem_fee = 0;
    $sql = "UPDATE pullout_main SET p_status='G',p_redeem_date='".date('Y-m-d')."',p_redeem_fee='$redeem_fee' WHERE p_id=".$_GET['id'];
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    $query_sub = "SELECT * FROM pullout_main WHERE p_id=".$_GET['id'];
    $recordset_sub = mysql_query($query_sub) or die('Query failed: ' . mysql_error());
    while ($row_sub = mysql_fetch_array($recordset_sub, MYSQL_ASSOC)) {
        $product_id = $row_sub['p_product_id'];
    }
    $sql = "UPDATE inventory SET p_ispulledout='N' WHERE p_id=".$product_id;
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} else if($_GET['action']=="repo") {
    $sql = "UPDATE pullout_main SET p_status='R',p_repo_date='".date('Y-m-d')."' WHERE p_id=".$_GET['id'];
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    $query_sub = "SELECT * FROM pullout_main WHERE p_id=".$_GET['id'];
    $recordset_sub = mysql_query($query_sub) or die('Query failed: ' . mysql_error());
    while ($row_sub = mysql_fetch_array($recordset_sub, MYSQL_ASSOC)) {
        $product_id = $row_sub['p_product_id'];
    }
    $sql = "UPDATE inventory SET p_ispulledout='N',p_isrepo='Y' WHERE p_id=".$product_id;
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} else if($_GET['action']=="delete") {
    $sql = "DELETE FROM pullout_main WHERE p_id=".$_GET['id'];      
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    $sql = "DELETE FROM pullout_sub WHERE p_parent_id=".$_GET['id'];      
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} else if($_GET['action']=="create") {
    $p_product_id = $_GET['product_id'];
    $p_sales_id = $_GET['sales_id'];
    $sql = "INSERT INTO pullout_main (
            p_product_id,p_sales_id,p_date,p_attestedby_id,p_status) 
            VALUES ('$p_product_id','$p_sales_id','".date('Y-m-d')."','".$_SESSION['user_id']."','D')";
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    echo mysql_insert_id();
}*/
?>  