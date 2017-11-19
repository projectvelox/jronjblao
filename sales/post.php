<?php

include "../includes/config.php";
session_start();

if($_GET['action']=="create_customer") {
    $c_firstname = addslashes($_POST['c_firstname']);
    $c_middlename = addslashes($_POST['c_middlename']);
    $c_lastname = addslashes($_POST['c_lastname']);
    $c_branch_id = addslashes($_POST['c_branch_id']);
    $c_phone_home = addslashes($_POST['c_phone_home']);
    $c_phone_office = addslashes($_POST['c_phone_office']);
    $c_phone_mobile =  addslashes($_POST['c_phone_mobile']);
    $c_email = addslashes($_POST['c_email']);
    $c_gender = addslashes($_POST['c_gender']);
    $c_civil_status = addslashes($_POST['c_civil_status']);
    $c_birthdate = date('Y-m-d',strtotime($_POST['c_birthdate']));
    $c_createdate = date('Y-m-d');
    $c_address_street = addslashes($_POST['c_address_street']);
    $c_address_town = addslashes($_POST['c_address_town']);
    $c_address_city = addslashes($_POST['c_address_city']);
    $c_address_zipcode = addslashes($_POST['c_address_zipcode']);
    $c_address_country = addslashes($_POST['c_address_country']);
    $c_citizenship = addslashes($_POST['c_citizenship']);
    $c_area = addslashes($_POST['c_area']);
    $c_active = "Y";
    $sql = "INSERT INTO customers (
              c_firstname,
              c_middlename,
              c_lastname,
              c_branch_id,
              c_phone_office,
              c_phone_home,
              c_phone_mobile,
              c_email,
              c_gender,
              c_civil_status,
              c_birthdate,
              c_createdate,
              c_address_street,
              c_address_town,
              c_address_city,
              c_address_zipcode,
              c_address_country,
              c_citizenship,
              c_area,
              c_active
          ) VALUES ( 
              '$c_firstname',
              '$c_middlename',
              '$c_lastname',  
              '$c_branch_id',
              '$c_phone_office',
              '$c_phone_home',
              '$c_phone_mobile',
              '$c_email',
              '$c_gender',
              '$c_civil_status',
              '$c_birthdate',
              '$c_createdate',
              '$c_address_street',
              '$c_address_town',
              '$c_address_city',
              '$c_address_zipcode',
              '$c_address_country',
              '$c_citizenship',
              '$c_area',
              '$c_active'
          )";
    mysql_query($sql) or die('<div class="message">Error! ' . mysql_error() . '</div>');
    $id = mysql_insert_id();
    $name = ucwords(strtolower($c_lastname)).', '.ucwords(strtolower($c_firstname));
    echo $id.'|'.$name;
    
} else if($_GET['action']=="additem") {
    $s_id = $_GET['s_id'];
    $s_product_id = $_GET['s_product_id'];
    $s_qty = $_GET['s_qty'];
    $query = "SELECT p_selling_price FROM inventory WHERE p_id=". $s_product_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $s_sold_price = $row['p_selling_price'];
    }
    $sql = "INSERT INTO sales_sub (
                s_sales_id,
                s_product_id,
                s_sold_price,
                s_qty
             ) VALUES (
                '$s_id',
                '$s_product_id',
                '$s_sold_price',
                '1'
            )";
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');   

} else if($_GET['action']=="deleteitem") {
    $sql = "DELETE FROM sales_sub WHERE s_sub_id=".$_GET['id'];      
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} else if($_GET['action']=="deletesales") {
    $sql = "DELETE FROM sales_main WHERE s_id=".$_GET['id'];      
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    $sql = "DELETE FROM sales_sub WHERE s_sales_id=".$_GET['id'];      
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} else if($_GET['action']=="update_sales_nonsp_cash") {
    $s_id = addslashes($_POST['s_id']);
    $s_customer_id = addslashes($_POST['s_customer_id']);
    $s_salesagent_id = addslashes($_POST['s_salesagent_id']);
    $s_orno = addslashes($_POST['s_orno']);
    $s_tax = addslashes($_POST['s_tax']);
    $s_discount = addslashes($_POST['s_discount']);
    $s_othercharges = addslashes($_POST['s_othercharges']);
    $s_product_id = addslashes($_POST['s_product_id']);
    $s_deliverydate = date("Y-m-d",strtotime($_POST['s_deliverydate']));
    $s_sold_price = addslashes($_POST['s_sold_price']);
    $s_notes = addslashes($_POST['s_notes']);
    $sql = "UPDATE sales_main SET 
                s_customer_id='".$s_customer_id."',
                s_salesagent_id='".$s_salesagent_id."',
                s_orno='".$s_orno."',
                s_tax='".$s_tax."',
                s_discount='".$s_discount."',
                s_othercharges='".$s_othercharges."',
                s_deliverydate='".$s_deliverydate."',
                s_notes='".$s_notes."'
            WHERE s_id=".$s_id;
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    $sql = "DELETE FROM sales_sub WHERE s_sales_id=".$s_id;      
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    $sql = "INSERT INTO sales_sub (
                s_sales_id,
                s_product_id,
                s_sold_price,
                s_qty
             ) VALUES (
                '$s_id',
                '$s_product_id',
                '$s_sold_price',
                '1'
            )";
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');       
} else if($_GET['action']=="update_sales_sp_cash") {
    $s_id = addslashes($_POST['s_id']);
    $s_customer_id = addslashes($_POST['s_customer_id']);
    $s_salesagent_id = addslashes($_POST['s_salesagent_id']);
    $s_orno = addslashes($_POST['s_orno']);
    $s_tax = addslashes($_POST['s_tax']);
    $s_discount = addslashes($_POST['s_discount']);
    $s_othercharges = addslashes($_POST['s_othercharges']);
    $s_deliverydate = date("Y-m-d",strtotime($_POST['s_deliverydate']));
    $s_notes = addslashes($_POST['s_notes']);
    $sql = "UPDATE sales_main SET 
                s_customer_id='".$s_customer_id."',
                s_salesagent_id='".$s_salesagent_id."',
                s_orno='".$s_orno."',
                s_tax='".$s_tax."',
                s_discount='".$s_discount."',
                s_othercharges='".$s_othercharges."',
                s_deliverydate='".$s_deliverydate."',
                s_notes='".$s_notes."'
            WHERE s_id=".$s_id;
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');     
} else if($_GET['action']=="update_sales_nonsp_installment") {
    $s_id = addslashes($_POST['s_id']);
    $s_customer_id = addslashes($_POST['s_customer_id']);
    $s_salesagent_id = addslashes($_POST['s_salesagent_id']);
    $s_orno = addslashes($_POST['s_orno']);
    $s_tax = addslashes($_POST['s_tax']);
    $s_discount = addslashes($_POST['s_discount']);
    $s_othercharges = addslashes($_POST['s_othercharges']);
    $s_product_id = addslashes($_POST['s_product_id']);
    $s_deliverydate = date("Y-m-d",strtotime($_POST['s_deliverydate']));
    $s_sold_price = addslashes($_POST['s_sold_price']);
    $s_notes = addslashes($_POST['s_notes']);
    $s_payment_term = addslashes($_POST['s_payment_term']);
    $s_firstmonthlydue_date = date("Y-m-d",strtotime($_POST['s_firstmonthlydue_date']));
    $s_firstdownpayment_date = date("Y-m-d",strtotime($_POST['s_firstdownpayment_date']));
    $s_seconddownpayment_date = date("Y-m-d",strtotime($_POST['s_seconddownpayment_date']));
    $s_firstdownpayment = addslashes($_POST['s_firstdownpayment']);
    $s_seconddownpayment = addslashes($_POST['s_seconddownpayment']);
    $s_downpayment_type = addslashes($_POST['s_downpayment_type']);
    $sql = "UPDATE sales_main SET 
                s_customer_id='".$s_customer_id."',
                s_salesagent_id='".$s_salesagent_id."',
                s_orno='".$s_orno."',
                s_tax='".$s_tax."',
                s_discount='".$s_discount."',
                s_othercharges='".$s_othercharges."',
                s_deliverydate='".$s_deliverydate."',
                s_notes='".$s_notes."',
                s_payment_term='".$s_payment_term."',
                s_firstmonthlydue_date='".$s_firstmonthlydue_date."',
                s_firstdownpayment_date='".$s_firstdownpayment_date."',
                s_seconddownpayment_date='".$s_seconddownpayment_date."',
                s_firstdownpayment='".$s_firstdownpayment."',
                s_seconddownpayment='".$s_seconddownpayment."',
                s_downpayment_type='".$s_downpayment_type."'
            WHERE s_id=".$s_id;
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    $sql = "DELETE FROM sales_sub WHERE s_sales_id=".$s_id;      
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    $sql = "INSERT INTO sales_sub (
                s_sales_id,
                s_product_id,
                s_sold_price,
                s_qty
             ) VALUES (
                '$s_id',
                '$s_product_id',
                '$s_sold_price',
                '1'
            )";
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');   
} else if($_GET['action']=="getprice") {
    $selling_price = 0;
    $query = "SELECT p_selling_price FROM inventory WHERE p_id=".$_GET['id'];
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $selling_price = $row['p_selling_price'];
    }
    echo $selling_price;
} else if($_GET['action']=="create_sales_nonsp_cash") {
    $s_sales_date = date("Y-m-d",strtotime($_POST['s_sales_date']));
    $s_branch_id = addslashes($_POST['s_branch_id']);
    $s_customer_id = addslashes($_POST['s_customer_id']);
    $s_salesagent_id = addslashes($_POST['s_salesagent_id']);
    $s_payment_term = addslashes($_POST['s_payment_term']);
    $s_sales_type = 'C';
    $s_orno = addslashes($_POST['s_orno']);
    $s_tax = addslashes($_POST['s_tax']);
    $s_discount = addslashes($_POST['s_discount']);
    $s_othercharges = addslashes($_POST['s_othercharges']);
    $s_createdby_id = $_SESSION['user_id'];
    $s_notes = addslashes($_POST['s_notes']);
    $s_downpayment_type = "C";
    $s_product_id = addslashes($_POST['s_product_id']);
    $s_deliverydate = date("Y-m-d",strtotime($_POST['s_deliverydate']));
    $s_sold_price = addslashes($_POST['s_sold_price']);
    $s_notes = addslashes($_POST['s_notes']);
    $sql = "INSERT INTO sales_main (
                s_sales_date,
                s_branch_id,
                s_customer_id,
                s_payment_term,
                s_sales_type,
                s_orno,
                s_tax,
                s_discount,
                s_othercharges,
                s_createdby_id,
                s_notes,
                s_downpayment_type,
                s_deliverydate,
                s_salesagent_id,
                s_category
            ) VALUES (
                '$s_sales_date',
                '$s_branch_id',
                '$s_customer_id',
                '$s_payment_term',
                '$s_sales_type',
                '$s_orno',
                '$s_tax',
                '$s_discount',
                '$s_othercharges',
                '$s_createdby_id',
                '$s_notes',
                '$s_downpayment_type',
                '$s_deliverydate',
                '$s_salesagent_id',
                'NONSP'
            )";
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    $s_sales_id = mysql_insert_id();

    $sql = "INSERT INTO sales_sub (
                s_sales_id,
                s_product_id,
                s_sold_price,
                s_qty
            ) VALUES (
                '$s_sales_id',
                '$s_product_id',
                '$s_sold_price',
                '1'
            )";
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>'); 
} else if($_GET['action']=="create_sales_sp_cash") {
    $s_sales_date = date("Y-m-d",strtotime($_POST['s_sales_date']));
    $s_branch_id = addslashes($_POST['s_branch_id']);
    $s_customer_id = addslashes($_POST['s_customer_id']);
    $s_salesagent_id = addslashes($_POST['s_salesagent_id']);
    $s_payment_term = '1';
    $s_sales_type = 'C';
    $s_orno = addslashes($_POST['s_orno']);
    $s_createdby_id = $_SESSION['user_id'];
    $s_notes = addslashes($_POST['s_notes']);
    $s_downpayment_type = "C";
    $sql = "INSERT INTO sales_main (
                s_sales_date,
                s_branch_id,
                s_customer_id,
                s_payment_term,
                s_sales_type,
                s_orno,
                s_createdby_id,
                s_notes,
                s_salesagent_id,
                s_category
            ) VALUES (
                '$s_sales_date',
                '$s_branch_id',
                '$s_customer_id',
                '$s_payment_term',
                '$s_sales_type',
                '$s_orno',
                '$s_createdby_id',
                '$s_notes',
                '$s_salesagent_id',
                'SP'
            )";
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    $s_sales_id = mysql_insert_id();
    echo $s_sales_id;

}
else if($_GET['action']=="create_sales_nonsp_installment") {
    $s_sales_date = date("Y-m-d",strtotime($_POST['s_sales_date']));
    $s_branch_id = addslashes($_POST['s_branch_id']);
    $s_customer_id = addslashes($_POST['s_customer_id']);
    $s_salesagent_id = addslashes($_POST['s_salesagent_id']);
    $s_payment_term = addslashes($_POST['s_payment_term']);
    $s_sales_type = 'I';
    $s_orno = addslashes($_POST['s_orno']);
    $s_tax = addslashes($_POST['s_tax']);
    $s_discount = addslashes($_POST['s_discount']);
    $s_othercharges = addslashes($_POST['s_othercharges']);
    $s_createdby_id = $_SESSION['user_id'];
    $s_notes = addslashes($_POST['s_notes']);
    $s_downpayment_type = addslashes($_POST['s_downpayment_type']);
    $s_product_id = addslashes($_POST['s_product_id']);
    $s_deliverydate = date("Y-m-d",strtotime($_POST['s_deliverydate']));
    $s_sold_price = addslashes($_POST['s_sold_price']);
    $s_notes = addslashes($_POST['s_notes']);
    $s_firstmonthlydue_date = date("Y-m-d",strtotime($_POST['s_firstmonthlydue_date']));
    $s_firstdownpayment_date = date("Y-m-d",strtotime($_POST['s_firstdownpayment_date']));
    $s_seconddownpayment_date = date("Y-m-d",strtotime($_POST['s_seconddownpayment_date']));
    $s_firstdownpayment = addslashes($_POST['s_firstdownpayment']);
    $s_seconddownpayment = addslashes($_POST['s_seconddownpayment']);
    $sql = "INSERT INTO sales_main (
                s_firstmonthlydue_date,
                s_firstdownpayment_date,
                s_seconddownpayment_date,
                s_firstdownpayment,
                s_seconddownpayment,
                s_sales_date,
                s_branch_id,
                s_customer_id,
                s_payment_term,
                s_sales_type,
                s_orno,
                s_tax,
                s_discount,
                s_othercharges,
                s_createdby_id,
                s_notes,
                s_downpayment_type,
                s_deliverydate,
                s_salesagent_id,
                s_category
            ) VALUES (
                '$s_firstmonthlydue_date',
                '$s_firstdownpayment_date',
                '$s_seconddownpayment_date',
                '$s_firstdownpayment',
                '$s_seconddownpayment',
                '$s_sales_date',
                '$s_branch_id',
                '$s_customer_id',
                '$s_payment_term',
                '$s_sales_type',
                '$s_orno',
                '$s_tax',
                '$s_discount',
                '$s_othercharges',
                '$s_createdby_id',
                '$s_notes',
                '$s_downpayment_type',
                '$s_deliverydate',
                '$s_salesagent_id',
                'NONSP'
            )";
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    $s_sales_id = mysql_insert_id();
    $sql = "INSERT INTO sales_sub (
                s_sales_id,
                s_product_id,
                s_sold_price,
                s_qty
            ) VALUES (
                '$s_sales_id',
                '$s_product_id',
                '$s_sold_price',
                '1'
            )";
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>'); 
} else if($_GET['action']=="postsales") {
    $sales_id = $_GET['id'];
    $s_postedby_id = $_SESSION['user_id'];
    $sql = "UPDATE sales_main SET s_confirm='Y',s_postedby_id='$s_postedby_id' WHERE s_id=".$sales_id;
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    $query = "SELECT * FROM sales_sub WHERE s_sales_id=".$sales_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $sql = "UPDATE inventory SET p_status='S' WHERE p_id=".$row['s_product_id'];
        mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    }
    //Posting to Journal
    $particulars = "";
    $j_branch_id = $_SESSION['branch_id'];
    $j_transaction_date = date('Y-m-d');
    $query = "SELECT *,
                    (SELECT CONCAT(c_lastname,', ',c_firstname) FROM customers WHERE customers.c_id=sales_main.s_customer_id) AS s_customer
                    FROM sales_main WHERE s_id=". $sales_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $particulars = "Sales / S".$row['s_id']." / ".$row['s_customer'];
        $tax = $row['s_tax'];
        $s_discount = $row['s_discount'];
        $s_othercharges = $row['s_othercharges'];
        $customer_id = $row['s_customer_id'];
    }
    $sql = "INSERT INTO journal_main (
                j_transaction_date,
                j_branch_id,
                j_status,
                j_origination_category,
                j_origination_id,
                j_particulars,
                j_customer_id
            ) VALUES (
                '$j_transaction_date',
                '$j_branch_id',
                'P',
                'S',
                '$sales_id',
                '$particulars',
                '$customer_id'
            )";
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>'); 
    $journal_id = mysql_insert_id();
    $query = "SELECT *,(SELECT CONCAT(p_code,' / ',p_name) FROM products WHERE products.p_id=(SELECT p_product_id FROM inventory WHERE inventory.p_id=sales_sub.s_product_id)) AS product_code 
                 FROM sales_sub WHERE s_sales_id=".$sales_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $j_amount = $row['s_sold_price']*$row['s_qty'];
        $j_entry_description = get_stockcode($row['s_product_id']).'-'.$row['s_product_id']." / ".$row['product_code'];
        $sql = "INSERT INTO journal_sub (j_parent_id,j_entry_description,j_amount,j_entry) VALUES (
                '$journal_id','$j_entry_description','$j_amount','D')";
        mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    }

    $sql = "INSERT INTO journal_sub (j_parent_id,j_entry_description,j_amount,j_entry) VALUES (
            '$journal_id','Tax','$tax','D')";
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    $sql = "INSERT INTO journal_sub (j_parent_id,j_entry_description,j_amount,j_entry) VALUES (
            '$journal_id','Other Charges','$s_othercharges','D')";
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    $sql = "INSERT INTO journal_sub (j_parent_id,j_entry_description,j_amount,j_entry) VALUES (
            '$journal_id','Discount','$s_discount','C')";
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');

}






?>  