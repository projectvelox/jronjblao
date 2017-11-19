<?php

include "../includes/config.php";
session_start();


if($_GET['action']=="damage") {
    $sql = "UPDATE inventory SET p_status='D',p_reported='".date('Y-m-d')."' WHERE p_id=".$_GET['id'];
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} else if($_GET['action']=="lost") {
    $sql = "UPDATE inventory SET p_status='L',p_reported='".date('Y-m-d')."' WHERE p_id=".$_GET['id'];
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} else if($_GET['action']=="update") {
    $p_id = addslashes($_POST['p_id']);
    $p_selling_price = addslashes($_POST['p_selling_price']);
    $p_remarks = addslashes($_POST['p_remarks']);
    $p_status = addslashes($_POST['p_status']);
    $property = "";
    if(isset($_POST['p_property_1'])) { $property .= ",p_property_1='".$_POST['p_property_1']."'"; }
    if(isset($_POST['p_property_2'])) { $property .= ",p_property_2='".$_POST['p_property_2']."'"; }
    if(isset($_POST['p_property_3'])) { $property .= ",p_property_3='".$_POST['p_property_3']."'"; }
    if(isset($_POST['p_property_4'])) { $property .= ",p_property_4='".$_POST['p_property_4']."'"; }
    if(isset($_POST['p_property_5'])) { $property .= ",p_property_5='".$_POST['p_property_5']."'"; }
    if(isset($_POST['p_property_6'])) { $property .= ",p_property_6='".$_POST['p_property_6']."'"; }
    if(isset($_POST['p_property_7'])) { $property .= ",p_property_7='".$_POST['p_property_7']."'"; }
    $sql = "UPDATE inventory SET p_selling_price='$p_selling_price',p_remarks='$p_remarks',p_status='$p_status'".$property." WHERE p_id=".$p_id;
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} else if($_GET['action']=="return") {
    $sql = "UPDATE inventory SET p_status='R',p_returned_dated='".date('Y-m-d')."' WHERE p_id=".$_GET['id'];
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} else if($_GET['action']=="recieved") {
    $sql = "UPDATE inventory SET p_status='W',p_recieved_date='".date('Y-m-d')."' WHERE p_id=".$_GET['id'];
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
}
?>  