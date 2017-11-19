<?php

include "../includes/config.php";

if($_GET['action']=="create") {
    if($_POST['db'] == "colors") {
        $color_name = addslashes($_POST['color_name']);
        $sql = "INSERT INTO options_colors (color_name) VALUES ('$color_name')";
    } else if($_POST['db'] == "inventorycategory") {
        $i_name = addslashes($_POST['i_name']);
        $i_parent_id = addslashes($_POST['i_parent_id']);
        $i_code = addslashes($_POST['i_code']);
        $sql = "INSERT INTO inv_category (i_name,i_parent_id,i_code) VALUES ('$i_name','$i_parent_id','$i_code')";
    } else if($_POST['db'] == "brands") {
        $b_name = addslashes($_POST['b_name']);
        $b_description = addslashes($_POST['b_description']);
        $sql = "INSERT INTO brands (b_name,b_description) VALUES ('$b_name','$b_description')";
    } else if($_POST['db'] == "branches") {
        $b_name = addslashes($_POST['b_name']);
        $b_address_street = addslashes($_POST['b_address_street']);
        $b_address_town = addslashes($_POST['b_address_town']);
        $b_address_city = addslashes($_POST['b_address_city']);
        $b_address_zipcode = addslashes($_POST['b_address_zipcode']);
        $b_phone_office = addslashes($_POST['b_phone_office']);
        $b_email = addslashes($_POST['b_email']);
        $sql = "INSERT INTO branches (b_name,b_address_street,b_address_town,b_address_city,b_address_zipcode,b_phone_office,b_email) VALUES ('$b_name','$b_address_street','$b_address_town','$b_address_city','$b_address_zipcode','$b_phone_office','$b_email')";
    } else if($_POST['db'] == "departments") {
        $d_name = addslashes($_POST['d_name']);
        $d_description = addslashes($_POST['b_description']);
        $sql = "INSERT INTO departments (d_name,d_description) VALUES ('$d_name','$d_description')";          
    } else if($_POST['db'] == "areas") {
        $a_name = addslashes($_POST['a_name']);
        $a_code = addslashes($_POST['a_code']);
        $a_branch_id = addslashes($_POST['a_branch_id']);
        $a_collector_id = addslashes($_POST['a_collector_id']);
        $d_description = addslashes($_POST['d_description']);
        $sql = "INSERT INTO areas (a_name,a_code,a_branch_id,a_collector_id) VALUES ('$a_name','$a_code','$a_branch_id','$a_collector_id')";          
    } else if($_POST['db'] == "districts") {
        $d_name = addslashes($_POST['d_name']);
        $d_officeaddress = addslashes($_POST['d_officeaddress']);
        $d_contactperson = addslashes($_POST['d_contactperson']);
        $d_contactnumber = addslashes($_POST['d_contactnumber']);
        $d_head = addslashes($_POST['d_head']);
        $sql = "INSERT INTO districts (d_name,d_officeaddress,d_contactperson,d_contactnumber,d_head) VALUES ('$d_name','$d_officeaddress','$d_contactperson','$d_contactnumber','$d_head')";          
    } else if($_POST['db'] == "insurance") {
        $i_name = addslashes($_POST['i_name']);
        $i_officeaddress = addslashes($_POST['i_officeaddress']);
        $i_contactperson = addslashes($_POST['i_contactperson']);
        $i_contactnumber = addslashes($_POST['i_contactnumber']);
        $i_head = addslashes($_POST['i_head']);
        $sql = "INSERT INTO insurance (i_name,i_officeaddress,i_contactperson,i_contactnumber,i_head) VALUES ('$i_name','$i_officeaddress','$i_contactperson','$i_contactnumber','$i_head')";          
    } else if($_POST['db'] == "witness") {
        $b_id = addslashes($_POST['b_id']);
        $w_name1 = addslashes($_POST['w_name1']);
        $w_rcno1 = addslashes($_POST['w_rcno1']);
        $w_doi1 = addslashes($_POST['w_doi1']);
        $w_poi1 = addslashes($_POST['w_poi1']);
        $w_name2 = addslashes($_POST['w_name2']);
        $w_rcno2 = addslashes($_POST['w_rcno2']);
        $w_doi2 = addslashes($_POST['w_doi2']);
        $w_poi2 = addslashes($_POST['w_poi2']);   
        $sql = "INSERT INTO witness (b_id,w_name1,w_rcno1,w_doi1,w_poi1,w_name2,w_rcno2,w_doi2,w_poi2) VALUES ('$b_id','$w_name1','$w_rcno1','$w_doi1','$w_poi1','$w_name2','$w_rcno2','$w_doi2','$w_poi2')";          
    } else if($_POST['db'] == "designations") {
        $d_id = addslashes($_POST['d_id']);
        $d_name = addslashes($_POST['d_name']);
        $d_description = addslashes($_POST['d_description']);
        $sql = "INSERT INTO employee_designations (d_name,d_description) VALUES ('$d_name','$d_description')";          
    } else if($_POST['db'] == "vendorcategory") {
        $cat_id = addslashes($_POST['cat_id']);
        $cat_name = addslashes($_POST['cat_name']);
        $cat_desc = addslashes($_POST['cat_desc']);
        $sql = "INSERT INTO vendors_category (cat_name,cat_desc) VALUES ('$cat_name','$cat_desc')";          
    } else if($_POST['db'] == "paymenttype") {
        $c_id = addslashes($_POST['c_id']);
        $c_name = addslashes($_POST['c_name']);
        $c_description = addslashes($_POST['c_description']);
        $sql = "INSERT INTO paymenttype (c_name,c_description) VALUES ('$c_name','$c_description')";          
    } else if($_POST['db'] == "paymentterms") {
        $t_id = addslashes($_POST['t_id']);
        $t_code = addslashes($_POST['t_code']);
        $t_description = addslashes($_POST['t_description']);
        $t_terms = addslashes($_POST['t_terms']);
        $sql = "INSERT INTO paymentterms (t_code,t_description,t_terms) VALUES ('$t_code','$t_description','$t_terms')";          
    } else if($_POST['db'] == "shippingmethod") {
        $c_id = addslashes($_POST['c_id']);
        $c_name = addslashes($_POST['c_name']);
        $c_description = addslashes($_POST['c_description']);
        $sql = "INSERT INTO shippingmethod (c_name,c_description) VALUES ('$c_name','$c_description')";          
    } else if($_POST['db'] == "trancode") {
        $t_id = addslashes($_POST['t_id']);
        $t_name = addslashes($_POST['t_name']);
        $t_description = addslashes($_POST['t_description']);
        $sql = "INSERT INTO trancodes (t_name,t_description) VALUES ('$t_name','$t_description')";          
    } else if($_POST['db'] == "lto") {
        $s_id = addslashes($_POST['s_id']);
        $s_name = addslashes($_POST['s_name']);
        $sql = "INSERT INTO options_ltostatuses (s_name) VALUES ('$s_name')";          
    } 
} else if ($_GET['action']=="update") {
    if($_POST['db'] == "colors") {
        $color_name = addslashes($_POST['color_name']);
        $sql = "UPDATE options_colors SET color_name='$color_name' WHERE color_id=".$_POST['record_id'];
    } else if($_POST['db'] == "inventorycategory") {
        $i_name = addslashes($_POST['i_name']);
        $i_parent_id = addslashes($_POST['i_parent_id']);
        $i_code = addslashes($_POST['i_code']);
        $sql = "UPDATE inv_category SET i_code='$i_code',i_name='$i_name',i_parent_id='$i_parent_id' WHERE i_id=".$_POST['record_id'];
    } else if($_POST['db'] == "brands") {
        $b_name = addslashes($_POST['b_name']);
        $b_description = addslashes($_POST['b_description']);
        $sql = "UPDATE brands SET b_name='$b_name',b_description='$b_description' WHERE b_id=".$_POST['record_id'];
    }  else if($_POST['db'] == "branches") {
        $b_name = addslashes($_POST['b_name']);
        $b_address_street = addslashes($_POST['b_address_street']);
        $b_address_town = addslashes($_POST['b_address_town']);
        $b_address_city = addslashes($_POST['b_address_city']);
        $b_address_zipcode = addslashes($_POST['b_address_zipcode']);
        $b_phone_office = addslashes($_POST['b_phone_office']);
        $b_email = addslashes($_POST['b_email']);
        $sql = "UPDATE branches SET b_name='$b_name',b_address_street='$b_address_street',b_address_town='$b_address_town',b_address_city='$b_address_city',b_address_zipcode='$b_address_zipcode',b_phone_office='$b_phone_office',b_email='$b_email' WHERE b_id=".$_POST['record_id'];
    } else if($_POST['db'] == "departments") {
        $d_name = addslashes($_POST['d_name']);
        $d_description = addslashes($_POST['d_description']);
        $sql = "UPDATE departments SET d_name='$d_name',d_description='$d_description' WHERE d_id=".$_POST['record_id'];
    } else if($_POST['db'] == "areas") {
        $a_name = addslashes($_POST['a_name']);
        $a_code = addslashes($_POST['a_code']);
        $a_branch_id = addslashes($_POST['a_branch_id']);
        $a_collector_id = addslashes($_POST['a_collector_id']);
        $sql = "UPDATE areas SET a_name='$a_name',a_code='$a_code',a_branch_id='$a_branch_id',a_collector_id='$a_collector_id' WHERE a_id=".$_POST['record_id'];
    } else if($_POST['db'] == "districts") {
        $d_name = addslashes($_POST['d_name']);
        $d_officeaddress = addslashes($_POST['d_officeaddress']);
        $d_contactperson = addslashes($_POST['d_contactperson']);
        $d_contactnumber = addslashes($_POST['d_contactnumber']);
        $d_head = addslashes($_POST['d_head']);
        $sql = "UPDATE districts SET d_name='$d_name',d_officeaddress='$d_officeaddress',d_contactperson='$d_contactperson',d_contactnumber='$d_contactnumber',d_head='$d_head' WHERE d_id=".$_POST['record_id'];
    } else if($_POST['db'] == "insurance") {
        $i_name = addslashes($_POST['i_name']);
        $i_officeaddress = addslashes($_POST['i_officeaddress']);
        $i_contactperson = addslashes($_POST['i_contactperson']);
        $i_contactnumber = addslashes($_POST['i_contactnumber']);
        $i_head = addslashes($_POST['i_head']);
        $sql = "UPDATE insurance SET i_name='$i_name',i_officeaddress='$i_officeaddress',i_contactperson='$i_contactperson',i_contactnumber='$i_contactnumber',i_head='$i_head' WHERE i_id=".$_POST['record_id'];
    } else if($_POST['db'] == "witness") {
        $b_id = addslashes($_POST['b_id']);
        $w_name1 = addslashes($_POST['w_name1']);
        $w_rcno1 = addslashes($_POST['w_rcno1']);
        $w_doi1 = addslashes($_POST['w_doi1']);
        $w_poi1 = addslashes($_POST['w_poi1']);
        $w_name2 = addslashes($_POST['w_name2']);
        $w_rcno2 = addslashes($_POST['w_rcno2']);
        $w_doi2 = addslashes($_POST['w_doi2']);
        $w_poi2 = addslashes($_POST['w_poi2']);        
        $sql = "UPDATE witness SET b_id='$b_id',w_name1='$w_name1',w_rcno1='$w_rcno1',w_doi1='$w_doi1',w_poi1='$w_poi1',w_name2='$w_name2',w_rcno2='$w_rcno2',w_doi2='$w_doi2',w_poi2='$w_poi2' WHERE w_id=".$_POST['record_id'];
    } else if($_POST['db'] == "designations") {
        $d_id = addslashes($_POST['d_id']);
        $d_name = addslashes($_POST['d_name']);
        $d_description = addslashes($_POST['d_description']);
        $sql = "UPDATE employee_designations SET d_name='$d_name',d_description='$d_description' WHERE d_id=".$_POST['record_id'];
    } else if($_POST['db'] == "vendorcategory") {
        $cat_id = addslashes($_POST['cat_id']);
        $cat_name = addslashes($_POST['cat_name']);
        $cat_desc = addslashes($_POST['cat_desc']);
        $sql = "UPDATE vendors_category SET cat_name='$cat_name',cat_desc='$cat_desc' WHERE cat_id=".$_POST['record_id'];
    } else if($_POST['db'] == "paymenttype") {
        $c_id = addslashes($_POST['c_id']);
        $c_name = addslashes($_POST['c_name']);
        $c_description = addslashes($_POST['c_description']);
        $sql = "UPDATE paymenttype SET c_name='$c_name',c_description='$c_description' WHERE c_id=".$_POST['record_id'];
    } else if($_POST['db'] == "paymentterms") {
        $t_id = addslashes($_POST['t_id']);
        $t_code = addslashes($_POST['t_code']);
        $t_description = addslashes($_POST['t_description']);
        $t_terms = addslashes($_POST['t_terms']);
        $sql = "UPDATE paymentterms SET t_code='$t_code',t_description='$t_description',t_terms='$t_terms' WHERE t_id=".$_POST['record_id'];
    } else if($_POST['db'] == "shippingmethod") {
        $c_id = addslashes($_POST['c_id']);
        $c_name = addslashes($_POST['c_name']);
        $c_description = addslashes($_POST['c_description']);
        $sql = "UPDATE shippingmethod SET c_name='$c_name',c_description='$c_description' WHERE c_id=".$_POST['record_id'];
    } else if($_POST['db'] == "trancode") {
        $t_id = addslashes($_POST['t_id']);
        $t_name = addslashes($_POST['t_name']);
        $t_description = addslashes($_POST['t_description']);
        $sql = "UPDATE trancodes SET t_name='$t_name',t_description='$t_description' WHERE t_id=".$_POST['record_id'];
    } else if($_POST['db'] == "lto") {
        $s_id = addslashes($_POST['s_id']);
        $s_name = addslashes($_POST['s_name']);
        $sql = "UPDATE options_ltostatuses SET s_name='$s_name' WHERE s_id=".$_POST['record_id'];
    } 
} elseif ($_GET['action'] == "delete") {
    if($_POST['db'] == "colors") {
        $sql = "DELETE FROM options_colors WHERE color_id=".$_POST['record_id'];      
    } else if($_POST['db'] == "inventorycategory") {
        $sql = "DELETE FROM inv_category WHERE i_id=".$_POST['record_id'];      
    } else if($_POST['db'] == "brands") {
        $sql = "DELETE FROM brands WHERE b_id=".$_POST['record_id'];      
    } else if($_POST['db'] == "branches") {
        $sql = "DELETE FROM branches WHERE b_id=".$_POST['record_id'];      
    } else if($_POST['db'] == "departments") {
        $sql = "DELETE FROM departments WHERE d_id=".$_POST['record_id'];      
    } else if($_POST['db'] == "areas") {
        $sql = "DELETE FROM areas WHERE a_id=".$_POST['record_id'];      
    } else if($_POST['db'] == "districts") {
        $sql = "DELETE FROM districts WHERE d_id=".$_POST['record_id'];      
    } else if($_POST['db'] == "insurance") {
        $sql = "DELETE FROM insurance WHERE i_id=".$_POST['record_id'];      
    } else if($_POST['db'] == "witness") {
        $sql = "DELETE FROM witness WHERE w_id=".$_POST['record_id'];      
    } else if($_POST['db'] == "designations") {
        $sql = "DELETE FROM employee_designations WHERE d_id=".$_POST['record_id'];      
    } else if($_POST['db'] == "vendorcategory") {
        $sql = "DELETE FROM vendors_category WHERE cat_id=".$_POST['record_id'];      
    } else if($_POST['db'] == "paymenttype") {
        $sql = "DELETE FROM paymenttype WHERE c_id=".$_POST['record_id'];      
    } else if($_POST['db'] == "paymentterms") {
        $sql = "DELETE FROM paymentterms WHERE t_id=".$_POST['record_id'];      
    } else if($_POST['db'] == "shippingmethod") {
        $sql = "DELETE FROM shippingmethod WHERE c_id=".$_POST['record_id'];      
    } else if($_POST['db'] == "trancode") {
        $sql = "DELETE FROM trancodes WHERE t_id=".$_POST['record_id'];      
    } else if($_POST['db'] == "lto") {
        $sql = "DELETE FROM options_ltostatuses WHERE s_id=".$_POST['record_id'];      
    }   


} 





mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
?>  