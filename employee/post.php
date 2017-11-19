<?php

include "../includes/config.php";

if($_GET['action']=="create") {
    $e_firstname = addslashes($_POST['e_firstname']);
    $e_middlename = addslashes($_POST['e_middlename']);
    $e_lastname = addslashes($_POST['e_lastname']);
    $e_branch_id = addslashes($_POST['e_branch_id']);
    $e_designation_id = addslashes($_POST['e_designation_id']);
    $e_department_id = addslashes($_POST['e_department_id']);
    $e_phone_home = addslashes($_POST['e_phone_home']);
    $e_phone_office = addslashes($_POST['e_phone_office']);
    $e_phone_mobile =  addslashes($_POST['e_phone_mobile']);
    $e_gender = addslashes($_POST['e_gender']);
    $e_marital_status_id = addslashes($_POST['e_marital_status_id']);
    $e_birthdate = $_POST['e_birthdate'];
    $e_hiredate =  date('Y-m-d',strtotime($_POST['e_hiredate']));
    $e_createdate = date('Y-m-d');
    $e_passport = addslashes($_POST['e_passport']);
    $e_driverlicense = addslashes($_POST['e_driverlicense']);
    $e_email = addslashes($_POST['e_email']);
    $e_address_street = addslashes($_POST['e_address_street']);
    $e_address_town = addslashes($_POST['e_address_town']);
    $e_address_city = addslashes($_POST['e_address_city']);
    $e_address_zipcode = addslashes($_POST['e_address_zipcode']);
    $e_address_country = addslashes($_POST['e_address_country']);
    $e_citizenship = addslashes($_POST['e_citizenship']);
    $e_username = substr(trim(strtolower($_POST['e_firstname'])),0,1).trim(strtolower($_POST['e_lastname']));
    $e_password = addslashes($_POST['e_password']);
    $e_sssno = addslashes($_POST['e_sssno']);
    $e_tinno = addslashes($_POST['e_tinno']);
    $e_philhealth = addslashes($_POST['e_philhealth']);
    $e_active = "Y";
    $e_is_salesagent = addslashes($_POST['e_is_salesagent']);
    $c_is_collector = addslashes($_POST['e_is_collector']);
    $sql = "INSERT INTO employees (
              e_lastname,
              e_firstname,
              e_middlename,
              e_branch_id,
              e_department_id,
              e_designation_id,
              e_phone_office,
              e_phone_home,
              e_phone_mobile,
              e_gender,
              e_marital_status_id,
              e_birthdate,
              e_hiredate,
              e_createdate,
              e_passport,
              e_driverlicense,
              e_email,
              e_address_street,
              e_address_town,
              e_address_city,
              e_address_zipcode,
              e_address_country,
              e_citizenship,
              e_username,
              e_password,
              e_sssno,
              e_tinno,
              e_philhealth,
              e_active,
              e_is_salesagent,
              e_is_collector
          ) VALUES ( 
              '$e_lastname',
              '$e_firstname',
              '$e_middlename',  
              '$e_branch_id',
              '$e_designation_id',
              '$e_department_id',
              '$e_phone_office',
              '$e_phone_home',
              '$e_phone_mobile',
              '$e_gender',
              '$e_marital_status_id',
              '$e_birthdate',
              '$e_hiredate',
              '$e_createdate',
              '$e_passport',
              '$e_driverlicense',
              '$e_email',
              '$e_address_street',
              '$e_address_town',
              '$e_address_city',
              '$e_address_zipcode',
              '$e_address_country',
              '$e_citizenship',
              '$e_username',
              '$e_password',
              '$e_sssno',
              '$e_tinno',
              '$e_philhealth',
              '$e_active',
              '$e_is_salesagent',
              '$e_is_collector'
          )";
    mysql_query($sql) or die('<div class="message">Error! ' . mysql_error() . '</div>');
    $emp_id = mysql_insert_id();    
    $sql = "INSERT INTO user_accessrights (emp_id) VALUES ('$emp_id')"; 
    mysql_query($sql) or die('<div class="message">Error! ' . mysql_error() . '</div>');      
} else if ($_GET['action']=="update") {
    $e_id = addslashes($_POST['e_id']);
    $e_firstname = addslashes($_POST['e_firstname']);
    $e_middlename = addslashes($_POST['e_middlename']);
    $e_lastname = addslashes($_POST['e_lastname']);
    $e_branch_id = addslashes($_POST['e_branch_id']);
    $e_designation_id = addslashes($_POST['e_designation_id']);
    $e_department_id = addslashes($_POST['e_department_id']);
    $e_phone_home = addslashes($_POST['e_phone_home']);
    $e_phone_office = addslashes($_POST['e_phone_office']);
    $e_phone_mobile =  addslashes($_POST['e_phone_mobile']);
    $e_gender = addslashes($_POST['e_gender']);
    $e_marital_status_id = addslashes($_POST['e_marital_status_id']);
    $e_birthdate = $_POST['e_birthdate'];
    $e_hiredate =  date('Y-m-d',strtotime($_POST['e_hiredate']));
    $e_passport = addslashes($_POST['e_passport']);
    $e_driverlicense = addslashes($_POST['e_driverlicense']);
    $e_email = addslashes($_POST['e_email']);
    $e_address_street = addslashes($_POST['e_address_street']);
    $e_address_town = addslashes($_POST['e_address_town']);
    $e_address_city = addslashes($_POST['e_address_city']);
    $e_address_zipcode = addslashes($_POST['e_address_zipcode']);
    $e_address_country = addslashes($_POST['e_address_country']);
    $e_citizenship = addslashes($_POST['e_citizenship']);
    $e_username = substr(trim(strtolower($_POST['e_firstname'])),0,1).trim(strtolower($_POST['e_lastname']));
    $e_password = addslashes($_POST['e_password']);
    $e_sssno = addslashes($_POST['e_sssno']);
    $e_tinno = addslashes($_POST['e_tinno']);
    $e_philhealth = addslashes($_POST['e_philhealth']);
    $e_active = "Y";
    $e_is_salesagent = addslashes($_POST['e_is_salesagent']);
    $e_password = addslashes($_POST['e_password']);
    $e_is_collector = addslashes($_POST['e_is_collector']);
    $sql = "UPDATE employees SET 
              e_lastname='$e_lastname',
              e_firstname='$e_firstname',
              e_middlename='$e_middlename',
              e_branch_id='$e_branch_id',
              e_department_id='$e_department_id',
              e_designation_id='$e_designation_id',
              e_phone_office='$e_phone_office',
              e_phone_home='$e_phone_home',
              e_phone_mobile='$e_phone_mobile',
              e_gender='$e_gender',
              e_marital_status_id='$e_marital_status_id',
              e_birthdate='$e_birthdate',
              e_hiredate='$e_hiredate',
              e_passport='$e_passport',
              e_driverlicense='$e_driverlicense',
              e_email='$e_email',
              e_address_street='$e_address_street',
              e_address_town='$e_address_town',
              e_address_city='$e_address_city',
              e_address_zipcode='$e_address_zipcode',
              e_address_country='$e_address_country',
              e_citizenship='$e_citizenship',
              e_username='$e_username',
              e_password='$e_password',
              e_sssno='$e_sssno',
              e_tinno='$e_tinno',
              e_philhealth='$e_philhealth',
              e_active='$e_active',
              e_is_salesagent='$e_is_salesagent',
              e_password='$e_password',
              e_is_collector='$e_is_collector'
          WHERE e_id=".$e_id;
    mysql_query($sql) or die('<div class="message">Error! ' . mysql_error() . '</div>');
} elseif ($_GET['action'] == "delete") { 
    $e_id = addslashes($_GET['id']);   
    $sql = "UPDATE employees SET e_is_deleted='Y' WHERE e_id=".$e_id;
    mysql_query($sql) or die('<div class="message">Error! ' . mysql_error() . '</div>');
} 
?>  