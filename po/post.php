<?php

include "../includes/config.php";
session_start();

if($_GET['action']=="create") {

} elseif ($_GET['action'] == "deleteitem") {
    $sql = "DELETE FROM po_sub WHERE p_sub_id=".$_GET['id'];   
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} elseif ($_GET['action'] == "addpoitem") {
    $p_id = addslashes($_POST['p_id']);
    $p_product_id = addslashes($_POST['p_product_id']);
    $p_qty = addslashes($_POST['p_qty']);
    $p_itemprice = addslashes($_POST['p_itemprice']);
    $sql = "INSERT INTO po_sub (p_product_id,p_qty,p_itemprice,p_id) VALUES ('$p_product_id','$p_qty','$p_itemprice','$p_id')";
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} elseif ($_GET['action'] == "update") {
    $p_sub_id = addslashes($_POST['p_id']);
    $p_id = addslashes($_POST['p_id']);
    $p_product_id = addslashes($_POST['p_product_id']);
    $p_qty = addslashes($_POST['p_qty']);
    $p_itemprice = addslashes($_POST['p_itemprice']);
    $sql = "UPDATE po_sub SET p_product_id='$p_product_id',p_qty='$p_qty',p_itemprice='$p_itemprice' WHERE p_sub_id=".$_POST['p_sub_id'];
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} elseif ($_GET['action'] == "updatepo") {
    $p_id = addslashes($_POST['p_id']);
    $p_requestdate = date("Y-m-d",strtotime($_POST['p_requestdate']));
    $p_orderdate = date("Y-m-d",strtotime($_POST['p_orderdate'])); 
    $p_branch_id = addslashes($_POST['p_branch_id']);
    $p_vendor_id = addslashes($_POST['p_vendor_id']);
    $p_shippingmethod_id = addslashes($_POST['p_shippingmethod_id']);
    $p_paymentmethod_id = addslashes($_POST['p_paymentmethod_id']);
    $p_invoice = addslashes($_POST['p_invoice']);
    $p_notes = addslashes($_POST['p_notes']);
    $p_charge_tax = addslashes($_POST['p_charge_tax']);
    $p_charge_shipping = addslashes($_POST['p_charge_shipping']);
    $p_charge_others = addslashes($_POST['p_charge_others']);
    $p_discount = addslashes($_POST['p_discount']);
    $sql = "UPDATE po_main SET 
    	p_requestdate='$p_requestdate',
    	p_orderdate='$p_orderdate',
    	p_branch_id='$p_branch_id',
    	p_vendor_id='$p_vendor_id',
    	p_shippingmethod_id='$p_shippingmethod_id',
    	p_paymentmethod_id='$p_paymentmethod_id',
    	p_invoice='$p_invoice',
    	p_notes='$p_notes',
    	p_charge_tax='$p_charge_tax',
    	p_charge_shipping='$p_charge_shipping',
    	p_charge_others='$p_charge_others',
    	p_discount='$p_discount'
     	WHERE p_id=".$p_id;
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} elseif ($_GET['action'] == "decision") {
    $p_id = addslashes($_GET['id']);
    $decision = addslashes($_GET['decision']);
    if($decision=="V") { $sql = "UPDATE po_main SET p_status='$decision',verified_date='".date('Y-m-d')."',p_verifiedby_id='".$_SESSION['user_id']."' WHERE p_id=".$p_id; }
    else if($decision=="A") { $sql = "UPDATE po_main SET p_status='$decision',approval_date='".date('Y-m-d')."',p_approvedby_id='".$_SESSION['user_id']."' WHERE p_id=".$p_id; }
    else if($decision=="D") { $sql = "UPDATE po_main SET p_status='$decision',denied_date='".date('Y-m-d')."',p_verifiedby_id=null p_approvedby_id=null,p_recievedby_id=null,verified_date=null,approval_date=null,recieved_date=nullWHERE p_id=".$p_id; } 
    else if($decision=="R") { $sql = "UPDATE po_main SET p_status='$decision',recieved_date='".date('Y-m-d')."',p_recievedby_id='".$_SESSION['user_id']."' WHERE p_id=".$p_id; }
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} elseif ($_GET['action'] == "deletepo") {
    $p_id = addslashes($_POST['p_id']);
    $sql = "DELETE FROM po_main WHERE p_id=".$_POST['p_id'];      
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');    
    $sql = "DELETE FROM po_sub WHERE p_id=".$_POST['p_id'];     
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');    
} elseif ($_GET['action'] == "createpo") {
    $p_requestdate = date("Y-m-d",strtotime($_POST['p_requestdate']));
    $p_orderdate = date("Y-m-d",strtotime($_POST['p_orderdate'])); 
    $p_branch_id = addslashes($_POST['p_branch_id']);
    $p_vendor_id = addslashes($_POST['p_vendor_id']);
    $p_shippingmethod_id = addslashes($_POST['p_shippingmethod_id']);
    $p_paymentmethod_id = addslashes($_POST['p_paymentmethod_id']);
    $p_requestedby_id = addslashes($_POST['p_requestedby_id']);
    $p_invoice = addslashes($_POST['p_invoice']);
    $sql = "INSERT INTO po_main (p_requestedby_id,p_requestdate,p_orderdate,p_branch_id,p_vendor_id,p_shippingmethod_id,p_paymentmethod_id,p_invoice) VALUES ('$p_requestedby_id','$p_requestdate','$p_orderdate','$p_branch_id','$p_vendor_id','$p_shippingmethod_id','$p_paymentmethod_id','$p_invoice')";
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    echo mysql_insert_id();
} elseif ($_GET['action'] == "markrecieved") {
    $query = "SELECT *,
                    (SELECT p_sellingprice FROM products WHERE po_sub.p_product_id=products.p_id) AS p_selling_price,
                    (SELECT (SELECT i_name FROM inv_category AS b WHERE a.i_parent_id = b.i_id) as i_parent FROM inv_category AS a WHERE i_id=(SELECT p_category_id FROM products WHERE po_sub.p_product_id=products.p_id)) AS p_category,
                    (SELECT p_branch_id FROM po_main WHERE po_main.p_id=po_sub.p_id) AS p_branch_id
                FROM po_sub WHERE p_sub_id=".$_GET['id'];    
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $p_product_id = $row['p_product_id'];
        $p_selling_price = $row['p_selling_price'];
        $p_branch_id = $row['p_branch_id'];
        $p_po_id  = $row['p_id'];
        $p_recieved_date = date("Y-m-d");
        $p_status = "W";
        if($row['p_category']=="Spare Parts") {
            $p_purchase_cost = $row['p_qty'] * $row['p_itemprice'];
            $p_qty = $row['p_qty'];
            $p_reference = str_pad(rand(0,'9'.round(microtime(true))),15,"0",STR_PAD_LEFT); 
            $sql = "INSERT INTO inventory (p_reference,p_product_id,p_recieved_date,p_po_id,p_branch_id,p_selling_price,p_purchase_cost,p_status,p_qty) VALUES ('$p_reference','$p_product_id','$p_recieved_date','$p_po_id','$p_branch_id','$p_selling_price','$p_purchase_cost','$p_status','$p_qty')";
            mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
            $sql = "UPDATE po_sub SET p_is_recieved='Y' WHERE p_sub_id=".$_GET['id'];
            mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
        } else {
            $p_purchase_cost = $row['p_itemprice'];
            for ($x = 1; $x <= $row['p_qty']; $x++) {
                $p_reference = str_pad(rand(0,'9'.round(microtime(true))),15,"0",STR_PAD_LEFT); 
                $p_qty = 1;
                $sql = "INSERT INTO inventory (p_reference,p_product_id,p_recieved_date,p_po_id,p_branch_id,p_selling_price,p_purchase_cost,p_status,p_qty) VALUES ('$p_reference','$p_product_id','$p_recieved_date','$p_po_id','$p_branch_id','$p_selling_price','$p_purchase_cost','$p_status','$p_qty')";
                mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
            }  
            $sql = "UPDATE po_sub SET p_is_recieved='Y' WHERE p_sub_id=".$_GET['id'];
            mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
        }
    }  

    $count = 1;
    $query = "SELECT COUNT(*) AS recieved FROM po_sub WHERE p_is_recieved='N' AND p_id=".$p_po_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $count = $row['recieved']; }
    if($count == 0) {
        $sql = "UPDATE po_main SET p_status='R',recieved_date='".date('Y-m-d')."' WHERE p_id=".$p_po_id;
        mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    }


}

?>  