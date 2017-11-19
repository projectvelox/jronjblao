<?php

include "../includes/config.php";
session_start();

if($_GET['action']=="update-collection") {
    $p_or = addslashes($_POST['p_or']);
    $p_sales_id = addslashes($_POST['p_sales_id']);
    $p_collector_id = addslashes($_POST['p_collector_id']);
    $p_amount = addslashes($_POST['p_amount']);
    $p_notes = addslashes($_POST['p_notes']);
    $p_rebate = addslashes($_POST['p_rebate']);
    $p_id = addslashes($_POST['p_id']);
    $sql = "UPDATE payments SET 
                p_or='$p_or',
                p_sales_id='$p_sales_id',
                p_collector_id='$p_collector_id',
                p_amount='$p_amount',
                p_notes='$p_notes',
                p_rebate='$p_rebate'
                WHERE p_id=".$p_id;
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} else if($_GET['action']=="createcollection") {
    $p_or = addslashes($_POST['p_or']);
    $p_sales_id = addslashes($_POST['p_sales_id']);
    $p_collector_id = addslashes($_POST['p_collector_id']);
    $p_amount = addslashes($_POST['p_amount']);
    $p_notes = addslashes($_POST['p_notes']);
    $p_rebate = addslashes($_POST['p_rebate']);
    $p_id = addslashes($_POST['p_id']);
    $p_date = date('Y-m-d');
    $p_branch_id = $_SESSION['branch_id'];
    $sql = "INSERT INTO payments (
                p_date,
                p_sales_id,
                p_or,
                p_collector_id,
                p_amount,
                p_notes,
                p_isposted,
                p_rebate,
                p_branch_id
             ) VALUES (
                '$p_date',
                '$p_sales_id',
                '$p_or',
                '$p_collector_id',
                '$p_amount',
                '$p_notes',
                'N',
                '$p_rebate',
                '$p_branch_id'
            )";
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');   
} else if($_GET['action']=="deletecollection") {
    $sql = "DELETE FROM payments WHERE p_id=".$_GET['id'];      
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} else if($_GET['action']=="postcollection") {
    $id = $_GET['id'];
    $amount = $_GET['amount'];

    $query = "SELECT *,
                (SELECT s_customer_id FROM sales_main WHERE sales_main.s_id=payments.p_sales_id) AS customer_id
                FROM payments WHERE p_id=".$id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $sales_id = $row['p_sales_id'];
        $customer_id = $row['customer_id'];
    }

    $total_payment = 0;
    $query_payments = "SELECT COALESCE(SUM(p_amount),0) AS total_payment FROM payments WHERE p_isposted = 'Y' AND p_sales_id=".$sales_id;
    $recordset_payments = mysql_query($query_payments) or die('Query failed: ' . mysql_error());
    while ($row_payments = mysql_fetch_array($recordset_payments, MYSQL_ASSOC)) {
        $total_payment = $row_payments['total_payment'];
    }

    $total_payable = 0;
    $query_sales = "SELECT *, (SELECT SUM(s_sold_price*s_qty) FROM sales_sub WHERE sales_sub.s_sales_id=sales_main.s_id) AS total_sales FROM sales_main WHERE s_id=".$sales_id;
    $recordset_sales = mysql_query($query_sales) or die('Query failed: ' . mysql_error());
    while ($row_sales = mysql_fetch_array($recordset_sales, MYSQL_ASSOC)) {
        $total_sales = $row_sales['total_sales'];
        $total_payable = ($total_sales+$row_sales['s_othercharges']+$row_sales['s_tax'])-$row_sales['s_discount'];
    }

    $balance = $total_payable - ($total_payment + $amount);
    if($balance > 0) {
        $postedby_id = $_SESSION['user_id'];    
        $sql = "UPDATE payments SET p_isposted='Y',p_postedby_id='$postedby_id' WHERE p_id=".$id;
        mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    } else {
        $postedby_id = $_SESSION['user_id'];    
        $sql = "UPDATE payments SET p_isposted='Y',p_postedby_id='$postedby_id' WHERE p_id=".$id;
        mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
        $sql = "UPDATE sales_main SET s_ispaid='Y' WHERE s_id=".$sales_id;
        mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');    
    }

    //Post to Journal
    $particulars = "";
    $j_branch_id = $_SESSION['branch_id'];
    $j_transaction_date = date('Y-m-d');


    $query = "SELECT *,
                    (SELECT CONCAT(c_lastname,', ',c_firstname) FROM customers WHERE customers.c_id=sales_main.s_customer_id) AS s_customer
                    FROM sales_main WHERE s_id=". $sales_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $particulars = "Collection / S".$row['s_id']." / ".$row['s_customer'];
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
                'CL',
                '$id',
                '$particulars',
                '$customer_id'
            )";
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>'); 
    $journal_id = mysql_insert_id();

    $sql = "INSERT INTO journal_sub (j_parent_id,j_entry_description,j_amount,j_entry) VALUES (
            '$journal_id','Payment','$amount','C')";

    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');

} 

?>  