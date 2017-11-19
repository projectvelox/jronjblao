<?php

include "../includes/config.php";

if($_GET['action']=="create") {
    $v_companyname = addslashes($_POST['v_companyname']);
    $v_website = addslashes($_POST['v_website']);
    $v_tradename = addslashes($_POST['v_tradename']);
    $v_contact_lastname = addslashes($_POST['v_contact_lastname']);
    $v_contact_firstname = addslashes($_POST['v_contact_firstname']);
    $v_contact_email = addslashes($_POST['v_contact_email']);
    $v_phone_office = addslashes($_POST['v_phone_office']);
    $v_phone_home = addslashes($_POST['v_phone_home']);
    $v_phone_mobile = addslashes($_POST['v_phone_mobile']);
    $v_createdate = date('Y-m-d');
    $v_address_street = addslashes($_POST['v_address_street']);
    $v_address_town = addslashes($_POST['v_address_town']);
    $v_address_city = addslashes($_POST['v_address_city']);
    $v_address_zipcode = addslashes($_POST['v_address_zipcode']);
    $v_address_country = addslashes($_POST['v_address_country']);
    $v_category_id = addslashes($_POST['v_category_id']);
    $v_active = "Y";
    $sql = "INSERT INTO vendors (
              v_companyname,
              v_website,
              v_tradename,
              v_contact_lastname,
              v_contact_firstname,
              v_contact_email,
              v_phone_office,
              v_phone_home,
              v_phone_mobile,
              v_createdate,
              v_address_street,
              v_address_town,
              v_address_city,
              v_address_zipcode,
              v_address_country,
              v_category_id,
              v_active
          ) VALUES ( 
              '$v_companyname',
              '$v_website',
              '$v_tradename',  
              '$v_contact_lastname',
              '$v_contact_firstname',
              '$v_contact_email',
              '$v_phone_office',
              '$v_phone_home',
              '$v_phone_mobile',
              '$v_createdate',
              '$v_address_street',
              '$v_address_town',
              '$v_address_city',
              '$v_address_zipcode',
              '$v_address_country',
              '$v_category_id',
              '$v_active'
          )";
    mysql_query($sql) or die('<div class="message">Error! ' . mysql_error() . '</div>');
} else if ($_GET['action']=="update") {
    $v_id = addslashes($_POST['v_id']);
    $v_companyname = addslashes($_POST['v_companyname']);
    $v_website = addslashes($_POST['v_website']);
    $v_tradename = addslashes($_POST['v_tradename']);
    $v_contact_lastname = addslashes($_POST['v_contact_lastname']);
    $v_contact_firstname = addslashes($_POST['v_contact_firstname']);
    $v_contact_email = addslashes($_POST['v_contact_email']);
    $v_phone_office = addslashes($_POST['v_phone_office']);
    $v_phone_home = addslashes($_POST['v_phone_home']);
    $v_phone_mobile = addslashes($_POST['v_phone_mobile']);
    $v_address_street = addslashes($_POST['v_address_street']);
    $v_address_town = addslashes($_POST['v_address_town']);
    $v_address_city = addslashes($_POST['v_address_city']);
    $v_address_zipcode = addslashes($_POST['v_address_zipcode']);
    $v_address_country = addslashes($_POST['v_address_country']);
    $v_category_id = addslashes($_POST['v_category_id']);
    $v_active = "Y";
    $sql = "UPDATE vendors SET 
              v_companyname='$v_companyname',
              v_website='$v_website',
              v_tradename='$v_tradename',
              v_contact_lastname='$v_contact_lastname',
              v_contact_firstname='$v_contact_firstname',
              v_contact_email='$v_contact_email',
              v_phone_office='$v_phone_office',
              v_phone_home='$v_phone_home',
              v_phone_mobile='$v_phone_mobile',
              v_address_street='$v_address_street',
              v_address_town='$v_address_town',
              v_address_zipcode='$v_address_zipcode',
              v_address_country='$v_address_country',
              v_category_id='$v_category_id'
          WHERE v_id=".$v_id;
    mysql_query($sql) or die('<div class="message">Error! ' . mysql_error() . '</div>');
} elseif ($_GET['action'] == "delete") { 
    $v_id = addslashes($_GET['id']);   
    $sql = "UPDATE vendors SET v_is_deleted='Y' WHERE v_id=".$v_id;
    mysql_query($sql) or die('<div class="message">Error! ' . mysql_error() . '</div>');
} 
?>  