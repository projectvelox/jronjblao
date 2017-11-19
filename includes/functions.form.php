<?php

function po_status($status) {
	if($status=="D") { echo "Draft"; } 
	else if ($status=="V") { echo "Verified"; } 
	else if($status=="A") { echo "Approved"; } 
	else if($status=="R") { echo "Recieved"; } 
}

function formatdate($date) {
	if($date=="") { echo ""; } 
	else { echo date("m/d/Y",strtotime($date)); } 
}

function payroll_getbranch_name ($branch_id) {
	$branch_name = '';
	if($branch_id<>'') {
	    $query = "SELECT * FROM tbl_branches WHERE branch_id=$branch_id";
	    $branch = mysql_query($query) or die('Query failed: ' . mysql_error());
	    while ($branchlst= mysql_fetch_array($branch, MYSQL_ASSOC)) {       
	        $branch_name = $branchlst['branch_name'];
	    }  
	} 
    return $branch_name;
}
function origination($code) {
	$code = str_replace(' ', '', $code);
	if(substr($code,0,1)=="V") { $description = "Voucher|V";}
	if(substr($code,0,2)=="MT") { $description = "Merchandise Transfer|MT";}
	if(substr($code,0,2)=="JS") { $description = "Job Order - Service|JOB";}	
	if(substr($code,0,2)=="JP") { $description = "Job Order - Spareparts Sales|JOB";}		
	if(substr($code,0,2)=="S") { $description = "Sales|INV";}
	return $description;
}
function trancode($code) {
	if($code=="PURCHASEORDERS") { $decode = 100; }
    if($code=="COUPONS") { $decode = 101; }
    if($code=="TRANSFEROFMERCHANDISE") { $decode = 102; }	
    if($code=="LOANRECEIVABLES") { $decode = 103; }		
	if($code=="CASHSALES") { $decode = 104; }
    if($code=="COLLECTION") { $decode = 105; }
    if($code=="CUSTOMERPAYMENTINSTALLMENT") { $decode = 106; }	
    if($code=="CUSTOMER_DOWNPAYMENT") { $decode = 107; }		
	if($code=="INSTALLMENTSALES") { $decode = 108; }
    if($code=="INTEREST") { $decode = 109; }
    if($code=="PAYMENTTOSUPPLIER") { $decode = 110; }	
    if($code=="PAYROLLENTRIES") { $decode = 111; }		
	if($code=="PETTYCASHFUND") { $decode = 112; }
    if($code=="PURCHASERETURNS") { $decode = 113; }
    if($code=="REPOFEEDELIVERFYFEE") { $decode = 114; }	
    if($code=="SALESRETURNANDREFUND") { $decode = 115; }	
	if($code=="SALESRETURNREPLACE") { $decode = 116; }
    if($code=="SERVICE_INCOME") { $decode = 117; }
    if($code=="VOUCHERS") { $decode = 118; }	
    if($code=="EMPLOYEELOAN") { $decode = 119; }	
    if($code=="REVERSAL") { $decode = 120; }	
    if($code=="ADJUSTMENT") { $decode = 121; }		
    if($code=="COMISSION") { $decode = 122; }			
    if($code=="TRANSFEROFMERSHANDISE") { $decode = 123; }	
    if($code=="JOBORDERPAYMENT") { $decode = 124; }	
    if($code=="STOCKTRANSFERREVERSAL") { $decode = 125; }	
    if($code=="OTHERCOLLECTIONS") { $decode = 126; }			
    if($code=="PULLOUTFEE") { $decode = 127; }	
    if($code=="BANKDEPOSIT") { $decode = 128; }		
    if($code=="SALESSPRECON") { $decode = 129; }		
    if($code=="REPOUNIT") { $decode = 130; }		
    if($code=="LOANPAYMENTS") { $decode = 131; }	
    if($code=="BANKWITHDRAWAL") { $decode = 132; }		
	return $decode;
}
function decode_defition($code) {
	if($code=="APPLIANCES") { $decode = "Appliances"; }
	else if($code=="REPOUNITS") { $decode = "Repo Units"; }
	else if($code=="POWERPRODUCTS") { $decode = "Power Products"; }
	else if($code=="FURNITURES") { $decode = "Furnitures"; }
	else if($code=="MOTORCYCLE") { $decode = "Motorcycle"; }	
	else if($code=="SPAREPARTS") { $decode = "Spare Parts"; }		
	else if($code=="PAYROLL") { $decode = "Payroll"; }		
	else if($code=="PAYABLES") { $decode = "Payables"; }	
	else if($code=="MOTORCYCLE_KAWASAKI") { $decode = "Motorcyle - Kawasaki"; }		
	else if($code=="SPAREPARTS_KAWASAKI") { $decode = "Spare Parts - Kawasaki"; }			
	else if($code=="MOTORCYCLE_NGM") { $decode = "Motorcycle - NGM"; }	
	else if($code=="ICAACCOUNTS") { $decode = "ICA Account"; }	
	else if($code=="CARLENACCOUNT") { $decode = "Carlen Account"; }	
	else if($code=="NGMACCOUNT") { $decode = "NGM Account"; }	
	else if($code=="VMACCOUNT") { $decode = "VM Account"; }	
	else if($code=="AACACCOUNT") { $decode = "AAC Account"; }	
	else if($code=="JMQAACCOUNT") { $decode = "JMQA Account"; }		
	else if($code=="VMT") { $decode = "VMT Account"; }			
	else if($code=="PURCHASEORDERS") { $decode = "Purchase Order"; }		
	else if($code=="TRANSFEROFMERCHANDISE") { $decode = "Merchandise Transfer"; }		
	else if($code=="LOANRECEIVABLES") { $decode = "Loan Receivables"; }	
	else if($code=="CASHSALES") { $decode = "Cash Sales"; }		
	else if($code=="COLLECTION") { $decode = "Collection"; }		
	else if($code=="COUPONS") { $decode = "Coupons"; }	
	else if($code=="CUSTOMERPAYMENTINSTALLMENT") { $decode = "Customer Payment - Installment"; }		
	else if($code=="CUSTOMER_DOWNPAYMENT") { $decode = "Customer Downpayment"; }		
	else if($code=="INSTALLMENTSALES") { $decode = "Installment Sales"; }	
	else if($code=="INTEREST") { $decode = "Interest"; }		
	else if($code=="PAYMENTTOSUPPLIER") { $decode = "Payment to Supplier"; }		
	else if($code=="PAYROLLENTRIES") { $decode = "Payroll Entries"; }		
	else if($code=="PETTYCASHFUND") { $decode = "Petty Cash Fund"; }	
	else if($code=="PURCHASERETURNS") { $decode = "Purchase Returns"; }		
	else if($code=="REPOFEEDELIVERFYFEE") { $decode = "Repo and Delivery Fees"; }		
	else if($code=="SALESRETURNANDREFUND") { $decode = "Sales Return - Return and Refund"; }	
	else if($code=="SALESRETURNREPLACE") { $decode = "Sales Return - Return and Replace"; }		
	else if($code=="SERVICE_INCOME") { $decode = "Service Income"; }		
	else if($code=="VOUCHERS") { $decode = "Vouchers"; }		
	else if($code=="BANKDEPOSIT") { $decode = "Bank Deposit"; }		
	else if($code=="SALESSPRECON") { $decode = "Spareparts Used - Recon"; }		
	else if($code=="REPOUNIT") { $decode = "Unit Repo"; }			
	return $decode;	
}

function get_ltostatus($id) {
	if($id<>"") {
		$query = "SELECT status_name FROM lto_status WHERE status_id=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $status_name = $row['status_name']; }  
		return $status_name;
	}
}
function get_productsoldto($id) {
	if($id=="") {
		return "";
	} else {	
		$query = "SELECT customer_id FROM tbl_sales WHERE product_id=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $customer_id = $row['customer_id']; }  
		if($customer_id<>"") {	
			return get_customer_tagname($customer_id);
		} else {
			return "Undefined";
		}
	}	
}

function get_productsoldto_customerid($id) {
		$query = "SELECT customer_id FROM tbl_sales WHERE product_id=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $customer_id = $row['customer_id']; }  
		if($customer_id<>"") {	
			return $customer_id;
		} else {
			return 0;
		}
}
function get_productdop($id) {
	if($id<>"") {
		$query = "SELECT sales_date FROM tbl_sales WHERE product_id=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { 
			return date('m/d/Y',strtotime($row['sales_date']));
		}  
	} else { return ""; }	
}
function trancode_ledger_coupon($trancode) {  
    $trancode     = explode("|",$trancode);
	$query = "SELECT * FROM tbl_trancodes WHERE branchid=".$trancode[3]." AND trancode_category='".$trancode[1]."'";    	
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $trancode_ledgerid  =  $row['trancode_ledger']; } 
	if($trancode_ledgerid==0) {$account_name = "MANUAL"; } 
	else if($trancode_ledgerid=="") {$account_name = "UNDEFINED"; } 
	else {
		$query = "SELECT * FROM tbl_chartaccounts WHERE account_id=".$trancode_ledgerid;    
		$recordset	= mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {       
				$account_id   =  $row['account_id'];
				$account_name =  $row['account_code'].' - '.$row['account_name'];
		}    
	}	
	return $account_name;
	//return $query;
}
function trancode_ledger_coupon_action($trancode) {  
    $trancode     = explode("|",$trancode);
	$query = "SELECT * FROM tbl_trancodes WHERE branchid=".$trancode[3]." AND trancode_category='".$trancode[1]."'";    	
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $trancode_action  =  $row['trancode_action']; } 
	if($trancode_action=="C") { return "Credit"; } else { return "Debit"; }
}
function getbranch_id_customer($id) {
    $query = "SELECT cust_branch_id FROM tbl_customers WHERE cust_id=".$id;
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $branch_id = $row['cust_branch_id'];
    }  
	if($branch_id == "") { $branch_id = 0; }
    return $branch_id;
}
function trancode_ledger($trancode,$type) {  
    $trancode     = explode("|",$trancode);
	if($trancode[2]=="NOCONDITION") {
		$query = "SELECT * FROM tbl_trancodes WHERE branchid=".$trancode[3]." AND trancode_category='".$trancode[1]."' AND trancode_action='".$type."'";    	
	} else {
		$query = "SELECT * FROM tbl_trancodes WHERE branchid=".$trancode[3]." AND trancode_category='".$trancode[1]."' AND trancode_identifier='".$trancode[2]."' AND trancode_action='".$type."'";    	
	}
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $trancode_ledgerid  =  $row['trancode_ledger']; }    
	if($trancode_ledgerid==0) {$account_name = "MANUAL"; } 
	else if($trancode_ledgerid=="") {$account_name = "UNDEFINED"; } 
	else {
		$query = "SELECT * FROM tbl_chartaccounts WHERE account_id=".$trancode_ledgerid;    
		$recordset	= mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {       
				$account_id   =  $row['account_id'];
				$account_name =  $row['account_code'].' - '.$row['account_name'];
		}    
	}
	return $account_name;
}
function get_trancodecategory($trancode){
    $query = "SELECT trancode_category FROM tbl_trancodes WHERE trancode_id=".$trancode;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        return $row['trancode_category'];
    }  
}  
function get_productcategory_nonsp($product_id) {
    $query = "SELECT product_codeid,
				(SELECT category_name FROM tbl_category_inventory_parent WHERE tbl_category_inventory_parent.category_id=(SELECT category_parentid 
				FROM tbl_category_inventory 
				WHERE tbl_category_inventory.category_id=(SELECT productcode_categoryid FROM tbl_productcode WHERE tbl_productcode.productcode_id=tbl_products_nonspareparts.product_codeid)) 
				) AS main_category_name              
			FROM tbl_products_nonspareparts WHERE product_id=".$product_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
	while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {$category_name = $row['main_category_name'];}  
	return strtoupper(str_replace(' ','',$category_name));
}

function get_currentbalance($account_id){    
    $query = "SELECT current_balance FROM tbl_balances WHERE account_id=".$account_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    $count = 0;
	while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
		$current_balance 	= $row['current_balance'];
		$count 				= $count + 1;
    }   
	if($count == 0) {
		$current_balance = 0;
        $sql = "INSERT INTO tbl_balances (account_id,current_balance) VALUES ('$account_id','$current_balance')";
        mysql_query($sql) or die('<div class="message">Error! ' . mysql_error() . '</div>');   		
	}
	if($current_balance < 0 ) {
		return "<font color='red'>".number_format($current_balance,2,'.',',')."</font>";
	} else {
		return "<font color='blue'>".number_format($current_balance,2,'.',',')."</font>";
	}
}

function get_ledgerbalance($account_id){    
    $query = "SELECT account_balance FROM tbl_chartaccounts WHERE account_id=".$account_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        return $row['account_balance'];
    }   
}
function trancode_name($trancode,$type) {  
    $trancode     = explode("~",$trancode);
    $query = "SELECT * FROM tbl_trancodes WHERE branchid=".$trancode[3]." AND trancode_category='".$trancode[1]."' AND trancode_identifier='".$trancode[2]."' AND trancode_action='".$type."'";    
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $trancode_description  = $row['trancode_description']; }    
	return $trancode_description;
}
function update_journal($journal_id,$ledger_debit,$ledger_credit) {
	$journal_history = $ledger_debit.'~'.$ledger_credit;
    $query = "UPDATE tbl_transaction_journal SET journal_history='".$journal_history."' WHERE journal_id=".$journal_id;
    mysql_query($query) or  die('<div class="message">Error! ' .  mysql_error() . '</div>');
}
function update_ledgerbalance($ledger_id,$debit,$credit) {
	//Get Normal Balance
    $query = "SELECT account_normalbalance FROM tbl_chartaccounts WHERE account_id=".$ledger_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());	
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $account_normalbalance = $row['account_normalbalance'];}   	
	//Get Current Balance
    $query = "SELECT account_balance FROM tbl_chartaccounts WHERE account_id=".$ledger_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    	
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $current_balance = $row['account_balance'];}   
	//Logical for Debit of Credit (as Influenced by Normal Balance)
    if ($account_normalbalance == "D") {
        $new_balance = $current_balance + $debit;
        $new_balance = $new_balance - $credit;
    }    
    else {
        $new_balance = $current_balance + $credit;
        $new_balance = $new_balance - $debit;        
    }    
	//Update New Balance
    $query = "UPDATE tbl_chartaccounts SET account_balance=".$new_balance." WHERE account_id=".$ledger_id;
    mysql_query($query) or  die('<div class="message">Error! ' .  mysql_error() . '</div>');
}
function ledger_accountname($ledger_id) {  
    $query = "SELECT * FROM tbl_chartaccounts WHERE account_id=".$ledger_id;    
    $recordset	= mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {       
        $account_name =  $row['account_name'];
    }     
	return $account_name;
}
function ledger_accountcode($ledger_id) {  
    $query = "SELECT * FROM tbl_chartaccounts WHERE account_id=".$ledger_id;    
    $recordset	= mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {       
        $account_code =  $row['account_code'];
    }     
	return $account_code;
}
function ledger_account_title($ledger_id) {  
    $query = "SELECT * FROM tbl_chartaccounts WHERE account_id=".$ledger_id;    
    $recordset	= mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {       
        $account_code =  $row['account_code'].' - '.$row['account_name'];
    }     
	return $account_code;
}
function trancode_ledger_journalhistory($ledger_id) {  
	if($ledger_id==0) { $account_name = "<font color=red>MANUAL</font>"; } 
	else {
		$query = "SELECT * FROM tbl_chartaccounts WHERE account_id=".$ledger_id;    
		$recordset	= mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {       
			$account_name =  $row['account_code'].' - '.$row['account_name'];
		}     
	}
	return $account_name;
}
function trancode_ledger_id($trancode,$type) {  
    $trancode     = explode("|",$trancode);
	if($trancode[2]=="NOCONDITION") {
		$query = "SELECT * FROM tbl_trancodes WHERE branchid=".$trancode[3]." AND trancode_category='".$trancode[1]."' AND trancode_action='".$type."'";    	
	}
	else {
	    $query = "SELECT * FROM tbl_trancodes WHERE branchid=".$trancode[3]." AND trancode_category='".$trancode[1]."' AND trancode_identifier='".$trancode[2]."' AND trancode_action='".$type."'";    	
	}
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $trancode_ledgerid  =  $row['trancode_ledger']; }    	
    $query = "SELECT * FROM tbl_chartaccounts WHERE account_id=".$trancode_ledgerid;    
    $recordset	= mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $account_id  = $row['account_id'];}    
	if($account_id=="") { $account_id=0; };	
	return $account_id;	
}
function selecttrancodecategory() {
    $query = "SELECT * FROM tbl_trancodecategory WHERE categoryname!='NULL' ORDER BY category_id";
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    echo '<select class="listboxbranches" id="categoryselected" name="" size="20">';
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
		echo '<option class="" value="'.$row['category_id'].'" selected>'.$row['categoryname'].'</option>';    
    }  
    echo '</select>';
}
function selectbranch() {
    $query = "SELECT * FROM tbl_branches ORDER BY branch_name";
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    echo '<select class="listboxbranches" id="branchselected" name="" size="20">';
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
		echo '<option class="" value="'.$row['branch_id'].'">'.$row['branch_name'].'</option>';    
    }  
    echo '</select>';
}
function selectbranch_singlelist() {
    $query = "SELECT * FROM tbl_branches";
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    echo '<select class="field_input" id="branchselected" name="" size="1">';
	echo '<option class="field_input" value="" selected></option>';    	
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
		echo '<option class="field_input" value="'.$row['branch_id'].'" >'.$row['branch_name'].'</option>';    
    }  
    echo '</select>';
}
function selectbranch_smalllist() {
    $query = "SELECT * FROM tbl_branches";
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    echo '<select class="field_input" id="branchselected" name="" size="1">';
	echo '<option class="field_input" value="all" selected>All Branches</option>';    	
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
		echo '<option class="field_input" value="'.$row['branch_id'].'" >'.$row['branch_name'].'</option>';    
    }  
    echo '</select>';
}
function getbranchname($branch_id) {
	if($branch_id=="") {
		return  "";
	} else {
		$query = "SELECT branch_name FROM tbl_branches WHERE branch_id=".$branch_id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			return $branch_name = $row['branch_name'];
		}   
	}
}
function areaname($area_id) {
	if($area_id<>"") {
		$query = "SELECT * FROM tbl_areacode WHERE area_id=".$area_id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			$area_code = $row['area_code'];
			$area_name = $row['area_name'];
			return $area_code.' - '.$area_name;
		}   
	} else {
		return "";
	}
}
function show_module_closebutton () {
	echo '<a href="dashboard.php"><img src="images/exit.png" class="textbottom" alt="Close"></a>&nbsp;';
}

function branches($id, $class, $array, $type, $selected) {
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="8">';
    foreach ($array as $value) {
        $value = explode(",", $value);
        if ($value[0] == "+") {
            echo "<optgroup label='" . $value[1] . "'>";
        } else if ($value[0] == "-") {
            echo "</optgroup>";
        } else if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}

function payroll_branches($id, $class, $array, $type, $selected) {
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="20">';
    foreach ($array as $value) {
        $value = explode(",", $value);
        if ($value[0] == "+") {
            echo "<optgroup label='" . $value[1] . "'>";
        } else if ($value[0] == "-") {
            echo "</optgroup>";
        } else if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}
function branches_dropdown($id, $class, $array, $type, $selected) {
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="15">';
    foreach ($array as $value) {
        $value = explode(",", $value);
        if ($value[0] == "+") {
            echo "<optgroup label='" . $value[1] . "'>";
        } else if ($value[0] == "-") {
            echo "</optgroup>";
        } else if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}
function payroll_branches_dropdown($id, $class, $array, $type, $selected) {
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="25">';
    foreach ($array as $value) {
        $value = explode(",", $value);
        if ($value[0] == "+") {
            echo "<optgroup label='" . $value[1] . "'>";
        } else if ($value[0] == "-") {
            echo "</optgroup>";
        } else if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}
function getbranch_employee($id) {
	$branch_name = "";
    $query = "SELECT emp_department_id FROM tbl_employees WHERE emp_id=".$id;
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $department_id = $row['emp_department_id'];
    }  
	if($department_id<>0) {
		$query = "SELECT department_branchid FROM tbl_departments WHERE department_id=".$department_id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());	
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			$branch_id = $row['department_branchid'];
		}  
		$query = "SELECT branch_name FROM tbl_branches WHERE branch_id=".$branch_id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			$branch_name = $row['branch_name'];
		}   
	}
    return $branch_name;
}

function get_employee_department($id) {
	$department_name = "1";
    $query = "SELECT emp_department_id FROM tbl_employees WHERE emp_id=".$id;
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $department_id = $row['emp_department_id'];
    }  
	if($department_id<>0) {
		$query = "SELECT department_name FROM tbl_departments WHERE department_id=".$department_id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());	
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			$department_name = $row['department_name'];
		}  
	}
    return $department_name;
}

function payroll_getbranch_id_employee($id) {
	$branch_name = "";
    $query = "SELECT emp_department_id FROM tbl_employees WHERE emp_id=".$id;
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $department_id = $row['emp_department_id'];
    }  
	if($department_id<>0) {
		$query = "SELECT department_branchid FROM tbl_departments WHERE department_id=".$department_id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());	
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			$branch_id = $row['department_branchid'];
		}  
		$query = "SELECT branch_id FROM tbl_branches WHERE branch_id=".$branch_id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			$branch_id = $row['branch_id'];
		}   
	} else { $branch_id=0 ; }
    return $branch_id;
}

function getbranch_id_employee($id) {
	$branch_name = "";
    $query = "SELECT emp_department_id FROM tbl_employees WHERE emp_id=".$id;
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $department_id = $row['emp_department_id'];
    }  
	if($department_id<>0) {
		$query = "SELECT department_branchid FROM tbl_departments WHERE department_id=".$department_id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());	
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			$branch_id = $row['department_branchid'];
		}  
		$query = "SELECT branch_id FROM tbl_branches WHERE branch_id=".$branch_id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			$branch_id = $row['branch_id'];
		}   
	} else { $branch_id=12 ; }
    return $branch_id;
}

function get_trancodedescription($trancode){
    $query = "SELECT trancode_description FROM tbl_trancodes WHERE trancode_id=".$trancode;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        return $row['trancode_description'];
    }  
}  

function getcustomer_payments($id, $class, $type, $selected, $page, $dialog) {
	//Get Customer Listing	
     $dbconn = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD) or die('Could not connect: ' . mysql_error());
     mysql_select_db(DB_DATABASE) or die('Could not select database');         
	 if($page=="0") {$page="'0','1','2','3','4','5','6','7','8','9'";} else { $page = "'".$page."'"; }	 
     $query = "SELECT tbl_customers.cust_id,concat(cust_lastname,', ',cust_firstname) as customer_name  
                  FROM tbl_customers WHERE cust_status='A' AND SUBSTR(cust_lastname,1,1) IN(".$page.") ORDER BY cust_lastname,cust_firstname";
     $customer_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
     $customerlisting = array();
     while ($cuslst = mysql_fetch_array($customer_listing, MYSQL_ASSOC)) {       
              $query = 'SELECT ps_id,ps_amortization FROM tbl_paymentschedules WHERE tbl_paymentschedules.ps_ispaid<>"Y" AND ps_ownerid="'.$cuslst['cust_id'].'" ORDER BY ps_id ASC LIMIT 1';
              $payment_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
              $amount = 0;
              while ($payment_amount = mysql_fetch_array($payment_listing, MYSQL_ASSOC)) {     
                  $amount = $payment_amount['ps_amortization'];                  
              }
             $customerlisting[] = $cuslst['cust_id']."|".$cuslst['customer_name']."|".$amount;
     }  		
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="15">';
    foreach ($customerlisting as $value) {
        $value = explode("|", $value);
        if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '|' . $value[1] . '|'. $value[2]. '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '|' . $value[1] . '|'. $value[2]. '">' . $value[1].'</option>';
        }
    }
    echo '</select>';
	echo '<a id="'.$dialog.'_a" class="abc_button2" href="">A</a>'; 
	echo '<a id="'.$dialog.'_b" class="abc_button2" href="">B</a>';  
	echo '<a id="'.$dialog.'_c" class="abc_button2" href="">C</a>';  
	echo '<a id="'.$dialog.'_d" class="abc_button2" href="">D</a>'; 
	echo '<a id="'.$dialog.'_e" class="abc_button2" href="">E</a>'; 
	echo '<a id="'.$dialog.'_f" class="abc_button2" href="">F</a>'; 
	echo '<a id="'.$dialog.'_g" class="abc_button2" href="">G</a>'; 
	echo '<a id="'.$dialog.'_h" class="abc_button2" href="">H</a>';  
	echo '<a id="'.$dialog.'_i" class="abc_button2" href="">I</a>';  
	echo '<a id="'.$dialog.'_j" class="abc_button2" href="">J</a>'; 
	echo '<a id="'.$dialog.'_k" class="abc_button2" href="">K</a>'; 
	echo '<a id="'.$dialog.'_l" class="abc_button2" href="">L</a>'; 
    echo '<a id="'.$dialog.'_m" class="abc_button2" href="">M</a>';  
	echo '<a id="'.$dialog.'_n" class="abc_button2" href="">N</a>';  
	echo '<a id="'.$dialog.'_o" class="abc_button2" href="">O</a>';  
	echo '<a id="'.$dialog.'_p" class="abc_button2" href="">P</a>'; 
	echo '<a id="'.$dialog.'_q" class="abc_button2" href="">Q</a>'; 
	echo '<a id="'.$dialog.'_r" class="abc_button2" href="">R</a>'; 
	echo '<a id="'.$dialog.'_s" class="abc_button2" href="">S</a>'; 
	echo '<a id="'.$dialog.'_t" class="abc_button2" href="">T</a>';  
	echo '<a id="'.$dialog.'_u" class="abc_button2" href="">U</a>';  
	echo '<a id="'.$dialog.'_v" class="abc_button2" href="">V</a>'; 
	echo '<a id="'.$dialog.'_w" class="abc_button2" href="">W</a>'; 
	echo '<a id="'.$dialog.'_x" class="abc_button2" href="">X</a>'; 										
	echo '<a id="'.$dialog.'_y" class="abc_button2" href="">Y</a>'; 
	echo '<a id="'.$dialog.'_z" class="abc_button2" href="">Z</a>'; 	
	echo '<a id="'.$dialog.'_0" class="abc_button2" href="">#</a>'; 	
	echo '<script type="text/javascript">';
	$x=array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","0");
	foreach ($x as $value)
	  {
		echo '$(function() {';	
		echo '$("#'.$dialog.'_'.$value.'").click(function() {';
		echo '$("#accountlisting").load("sales/customerlisting.php?filter='.strtoupper($value).'&dialog='.$dialog.'");';
		echo 'event.preventDefault();';
		echo '});';		
		echo '});';  	  
	  }     	
	echo '</script>';
}


function getcustomer_payments2($id, $class, $type, $selected, $page, $dialog,$div) {
	//Get Customer Listing	
     $dbconn = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD) or die('Could not connect: ' . mysql_error());
     mysql_select_db(DB_DATABASE) or die('Could not select database');         
	 if($page=="0") {$page="'0','1','2','3','4','5','6','7','8','9'";} else { $page = "'".$page."'"; }	 
     $query = "SELECT tbl_customers.cust_id,concat(cust_lastname,', ',cust_firstname) as customer_name  
                  FROM tbl_customers WHERE cust_status='A' AND SUBSTR(cust_lastname,1,1) IN(".$page.") ORDER BY cust_lastname,cust_firstname";
     $customer_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
     $customerlisting = array();
     while ($cuslst = mysql_fetch_array($customer_listing, MYSQL_ASSOC)) {       
              $query = 'SELECT ps_id,ps_amortization FROM tbl_paymentschedules WHERE tbl_paymentschedules.ps_ispaid<>"Y" AND ps_ownerid="'.$cuslst['cust_id'].'" ORDER BY ps_id ASC LIMIT 1';
              $payment_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
              $amount = 0;
              while ($payment_amount = mysql_fetch_array($payment_listing, MYSQL_ASSOC)) {     
                  $amount = $payment_amount['ps_amortization'];                  
              }
             $customerlisting[] = $cuslst['cust_id']."|".$cuslst['customer_name']."|".$amount;
     }  		
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="15">';
    foreach ($customerlisting as $value) {
        $value = explode("|", $value);
        if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '|' . $value[1] . '|'. $value[2]. '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '|' . $value[1] . '|'. $value[2]. '">' . $value[1].'</option>';
        }
    }
    echo '</select>';
	echo '<a id="'.$dialog.'_a" class="abc_button2" href="">A</a>'; 
	echo '<a id="'.$dialog.'_b" class="abc_button2" href="">B</a>';  
	echo '<a id="'.$dialog.'_c" class="abc_button2" href="">C</a>';  
	echo '<a id="'.$dialog.'_d" class="abc_button2" href="">D</a>'; 
	echo '<a id="'.$dialog.'_e" class="abc_button2" href="">E</a>'; 
	echo '<a id="'.$dialog.'_f" class="abc_button2" href="">F</a>'; 
	echo '<a id="'.$dialog.'_g" class="abc_button2" href="">G</a>'; 
	echo '<a id="'.$dialog.'_h" class="abc_button2" href="">H</a>';  
	echo '<a id="'.$dialog.'_i" class="abc_button2" href="">I</a>';  
	echo '<a id="'.$dialog.'_j" class="abc_button2" href="">J</a>'; 
	echo '<a id="'.$dialog.'_k" class="abc_button2" href="">K</a>'; 
	echo '<a id="'.$dialog.'_l" class="abc_button2" href="">L</a>'; 
    echo '<a id="'.$dialog.'_m" class="abc_button2" href="">M</a>';  
	echo '<a id="'.$dialog.'_n" class="abc_button2" href="">N</a>';  
	echo '<a id="'.$dialog.'_o" class="abc_button2" href="">O</a>';  
	echo '<a id="'.$dialog.'_p" class="abc_button2" href="">P</a>'; 
	echo '<a id="'.$dialog.'_q" class="abc_button2" href="">Q</a>'; 
	echo '<a id="'.$dialog.'_r" class="abc_button2" href="">R</a>'; 
	echo '<a id="'.$dialog.'_s" class="abc_button2" href="">S</a>'; 
	echo '<a id="'.$dialog.'_t" class="abc_button2" href="">T</a>';  
	echo '<a id="'.$dialog.'_u" class="abc_button2" href="">U</a>';  
	echo '<a id="'.$dialog.'_v" class="abc_button2" href="">V</a>'; 
	echo '<a id="'.$dialog.'_w" class="abc_button2" href="">W</a>'; 
	echo '<a id="'.$dialog.'_x" class="abc_button2" href="">X</a>'; 										
	echo '<a id="'.$dialog.'_y" class="abc_button2" href="">Y</a>'; 
	echo '<a id="'.$dialog.'_z" class="abc_button2" href="">Z</a>'; 	
	echo '<a id="'.$dialog.'_0" class="abc_button2" href="">#</a>'; 	
	echo '<script type="text/javascript">';
	$x=array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","0");
	foreach ($x as $value)
	  {
		echo '$(function() {';	
		echo '$("#'.$dialog.'_'.$value.'").click(function() {';
		echo '$("#'.$div.'").load("sales/customerlisting.php?filter='.strtoupper($value).'&dialog='.$dialog.'");';
		echo 'event.preventDefault();';
		echo '});';		
		echo '});';  	  
	  }     	
	echo '</script>';
}

function getcustomer_abcbutton($id, $class, $type, $selected, $page, $dialog) {
	//Get Customer Listing
	$dbconn = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_DATABASE) or die('Could not select database');	
	if($page=="0") {$page="'0','1','2','3','4','5','6','7','8','9'";} else { $page = "'".$page."'"; }		
	$query = "SELECT tbl_customers.cust_id,cust_lastname,cust_firstname
				  FROM tbl_customers WHERE cust_status='A' AND SUBSTR(cust_lastname,1,1) IN(".$page.") ORDER BY cust_lastname,cust_firstname";
	$customer_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
	$customerlisting = array();
	while ($cuslst = mysql_fetch_array($customer_listing, MYSQL_ASSOC)) {    
		  $customer_name = ucwords(strtolower($cuslst['cust_lastname'])).', '.ucwords(strtolower($cuslst['cust_firstname']));
		  $customerlisting[] = $cuslst['cust_id']."|".$customer_name;
	}
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="15">';
    foreach ($customerlisting as $value) {
        $value = explode("|", $value);
        if ($value[0] == "+") {
            echo "<optgroup label='" . $value[1] . "'>";
        } else if ($value[0] == "-") {
            echo "</optgroup>";
        } else if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '|' . $value[1] . '|'. $value[2]. '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '|' . $value[1] . '|'. $value[2]. '">' . $value[1].'</option>';
        }
    }
    echo '</select>';
	echo '<a id="'.$dialog.'_a" class="abc_button2" href="">A</a>'; 
	echo '<a id="'.$dialog.'_b" class="abc_button2" href="">B</a>';  
	echo '<a id="'.$dialog.'_c" class="abc_button2" href="">C</a>';  
	echo '<a id="'.$dialog.'_d" class="abc_button2" href="">D</a>'; 
	echo '<a id="'.$dialog.'_e" class="abc_button2" href="">E</a>'; 
	echo '<a id="'.$dialog.'_f" class="abc_button2" href="">F</a>'; 
	echo '<a id="'.$dialog.'_g" class="abc_button2" href="">G</a>'; 
	echo '<a id="'.$dialog.'_h" class="abc_button2" href="">H</a>';  
	echo '<a id="'.$dialog.'_i" class="abc_button2" href="">I</a>';  
	echo '<a id="'.$dialog.'_j" class="abc_button2" href="">J</a>'; 
	echo '<a id="'.$dialog.'_k" class="abc_button2" href="">K</a>'; 
	echo '<a id="'.$dialog.'_l" class="abc_button2" href="">L</a>'; 
    echo '<a id="'.$dialog.'_m" class="abc_button2" href="">M</a>';  
	echo '<a id="'.$dialog.'_n" class="abc_button2" href="">N</a>';  
	echo '<a id="'.$dialog.'_o" class="abc_button2" href="">O</a>';  
	echo '<a id="'.$dialog.'_p" class="abc_button2" href="">P</a>'; 
	echo '<a id="'.$dialog.'_q" class="abc_button2" href="">Q</a>'; 
	echo '<a id="'.$dialog.'_r" class="abc_button2" href="">R</a>'; 
	echo '<a id="'.$dialog.'_s" class="abc_button2" href="">S</a>'; 
	echo '<a id="'.$dialog.'_t" class="abc_button2" href="">T</a>';  
	echo '<a id="'.$dialog.'_u" class="abc_button2" href="">U</a>';  
	echo '<a id="'.$dialog.'_v" class="abc_button2" href="">V</a>'; 
	echo '<a id="'.$dialog.'_w" class="abc_button2" href="">W</a>'; 
	echo '<a id="'.$dialog.'_x" class="abc_button2" href="">X</a>'; 										
	echo '<a id="'.$dialog.'_y" class="abc_button2" href="">Y</a>'; 
	echo '<a id="'.$dialog.'_z" class="abc_button2" href="">Z</a>'; 	
	echo '<a id="'.$dialog.'_0" class="abc_button2" href="">#</a>'; 		
	echo '<script type="text/javascript">';
	$x=array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","0");
	foreach ($x as $value)
	  {
		echo '$(function() {';	
		echo '$("#'.$dialog.'_'.$value.'").click(function() {';
		echo '$("#'.$dialog.'").load("sales/customerlisting.php?filter='.strtoupper($value).'&dialog='.$dialog.'");';
		echo 'event.preventDefault();';
		echo '});';		
		echo '});';  	  
	  }     	
	echo '</script>';
}
function get_ledgeraccounts_nobank($id, $class, $type, $selected) {
    //Get Products
    echo '<select class="'.$class.'" id="'.$id.'" name="'.$id.'" size="1">';
    echo '<option class="'.$class.'" value=""></option>';	
    $query = "SELECT * FROM tbl_chartaccounts WHERE is_subsidiary<>'Y' AND account_name NOT LIKE 'CIB%' ORDER BY account_name";
    $recordset  = mysql_query($query) or die('Query failed: ' . mysql_error());
    $category = '';
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {  
            $account_code   = $row['account_code'];   
            $account_id     = $row['account_id'];   
			$account_name   = $row['account_name'];                  
            echo '<option class="'.$class.'" value="'.$account_id.'">'.$account_name.'</option>';
    }        
    echo '</select>';
}

function get_voucherparticulars($id, $class, $type, $selected) {
    //Get Products
    echo '<select class="'.$class.'" id="'.$id.'" name="'.$id.'" size="1">';
    echo '<option class="'.$class.'" value=""></option>';	
    $query = "SELECT * FROM tbl_chartaccounts WHERE is_subsidiary<>'Y' ORDER BY account_name";
    $recordset  = mysql_query($query) or die('Query failed: ' . mysql_error());
    $category = '';
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {  
            $account_code   = $row['account_code'];   
            $account_id     = $row['account_id'];   
			$account_name   = $row['account_name'];                  
            echo '<option class="'.$class.'" value="'.$account_id.'">'.$account_name.'</option>';
    }        
    echo '</select>';
}

function get_subsidiaryledger($id, $class, $type, $selected,$ledger_id) {
    //Get Products
    echo '<select class="'.$class.'" id="'.$id.'" name="'.$id.'" size="1">';
    echo '<option class="'.$class.'" value=""></option>';	
    $query = "SELECT account_parentid FROM tbl_chartaccounts WHERE is_subsidiary='Y' GROUP BY account_parentid";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {	
            $account_id = $row['account_parentid']; 
			$query1 = "SELECT account_name, account_code FROM tbl_chartaccounts WHERE account_id=".$account_id;
		    $recordset1 = mysql_query($query1) or die('Query failed: ' . mysql_error());    
			while ($row1 = mysql_fetch_array($recordset1, MYSQL_ASSOC)) {	
				$account_name  = $row1['account_name']; 
				$account_code  = $row1['account_code']; 
				echo '<optgroup class="'.$class.'" label="'.$account_name.'">';
				//Sub
				$query2 = "SELECT * FROM tbl_chartaccounts WHERE is_subsidiary='Y' AND account_parentid=".$account_id." ORDER BY account_name";
				$recordset2  = mysql_query($query2) or die('Query failed: ' . mysql_error());
				$category = '';
				while ($row2 = mysql_fetch_array($recordset2, MYSQL_ASSOC)) {  
						$account_code2   = $row2['account_code'];   
						$account_id2     = $row2['account_id'];   
						$account_name2   = $row2['account_name'];  
						$space = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if($ledger_id==$account_id2) {
						} else {
							if($selected==$account_id2) {		
								echo '<option class="'.$class.'" value="'.$account_id2.'" selected>'.$space.$account_name2.'</option>';
							} else {
								echo '<option class="'.$class.'" value="'.$account_id2.'">'.$space.$account_name2.'</option>';
							}
						}	
				} 
				echo '</optgroup>';	
			}
	}       
    echo '</select>';
} 

function get_subsidiaryledger_readonly($id, $class, $type, $selected) {
    //Get Products
    echo '<select class="'.$class.'" id="'.$id.'" name="'.$id.'" size="1" disabled>';
    echo '<option class="'.$class.'" value=""></option>';	
    $query = "SELECT account_parentid FROM tbl_chartaccounts WHERE is_subsidiary='Y' GROUP BY account_parentid";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {	
            $account_id = $row['account_parentid']; 
			$query1 = "SELECT account_name, account_code FROM tbl_chartaccounts WHERE account_id=".$account_id;
		    $recordset1 = mysql_query($query1) or die('Query failed: ' . mysql_error());    
			while ($row1 = mysql_fetch_array($recordset1, MYSQL_ASSOC)) {	
				$account_name  = $row1['account_name']; 
				$account_code  = $row1['account_code']; 
				if($selected==$account_id) {		
					echo '<option class="'.$class.'" value="'.$account_id.'" selected><b>'.$account_name.'</b></option>';
				} else {
					echo '<option class="'.$class.'" value="'.$account_id.'"><b>'.$account_name.'</b></option>';
				}		
				//Sub
				$query2 = "SELECT * FROM tbl_chartaccounts WHERE is_subsidiary='Y' AND account_parentid=".$account_id." ORDER BY account_name";
				$recordset2  = mysql_query($query2) or die('Query failed: ' . mysql_error());
				$category = '';
				while ($row2 = mysql_fetch_array($recordset2, MYSQL_ASSOC)) {  
						$account_code2   = $row2['account_code'];   
						$account_id2     = $row2['account_id'];   
						$account_name2   = $row2['account_name'];  
						$space = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if($selected==$account_id2) {		
							echo '<option class="'.$class.'" value="'.$account_id2.'" selected>'.$space.$account_name2.'</option>';
						} else {
							echo '<option class="'.$class.'" value="'.$account_id2.'">'.$space.$account_name2.'</option>';
						}
				} 	
			}
	}       
    echo '</select>';
} 
function get_pettycashparticulars_listbox($id, $class, $type, $selected) {
    //Get Products
    echo '<select class="'.$class.'" id="'.$id.'" name="'.$id.'" size="26">';
    echo '<option class="'.$class.'" value=""></option>';	
    $query = "SELECT * FROM tbl_chartaccounts ORDER BY account_code";
    //$query = "SELECT *,(SELECT categoryname FROM tbl_pettycashcategory WHERE tbl_pettycashcategory.id=tbl_pettycashdefinitions.definition_category) AS category FROM tbl_pettycashdefinitions ORDER BY definition";
    $recordset  = mysql_query($query) or die('Query failed: ' . mysql_error());
    $category = '';
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {  
            $account_code   = $row['account_code'];   
            $account_id     = $row['account_id'];   
			$account_name   = $row['account_name'];                  
            echo '<option class="'.$class.'" value="'.$account_id.'">'.$account_code.' - '.$account_name.'</option>';
    }        
    echo '</select>';
}
function get_pettycashparticulars($id, $class, $type, $selected) {
    //Get Products
    echo '<select class="'.$class.'" id="'.$id.'" name="'.$id.'" size="1">';
    echo '<option class="'.$class.'" value=""></option>';	
    $query = "SELECT * FROM tbl_chartaccounts ORDER BY account_code";
    //$query = "SELECT *,(SELECT categoryname FROM tbl_pettycashcategory WHERE tbl_pettycashcategory.id=tbl_pettycashdefinitions.definition_category) AS category FROM tbl_pettycashdefinitions ORDER BY definition";
    $recordset  = mysql_query($query) or die('Query failed: ' . mysql_error());
    $category = '';
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {  
            $account_code   = $row['account_code'];   
            $account_id     = $row['account_id'];   
			$account_name   = $row['account_name'];                  
            echo '<option class="'.$class.'" value="'.$account_id.'">'.$account_code.' - '.$account_name.'</option>';
    }        
    echo '</select>';
}
function pettycash_category($id, $class, $array, $type, $selected) {
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '">';
    if ($selected[0] == "") {
        echo '<option class="' . $class . '" value="" selected></option>';
    }
    foreach ($array as $value) {
        $value = explode("|", $value);
        if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}
function showbutton($emp_id,$module,$level,$id,$class,$buttontext) {  
	$query = "SELECT ".$module." FROM user_accessrights WHERE emp_id = '$emp_id';";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());	
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $granted_level = $row[$module];}
	if($granted_level>=$level) { 
		echo '<a id="'.$id.'" class="'.$class.'" href="">'.$buttontext.'</a>';
	} else { 
		echo '<span class="'.$class.'_disabled">'.$buttontext.'</span>';	
	}
}
function checkuser_menuoptions_lock($emp_id,$menu_options) {  
	$dbconn = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_DATABASE) or die('Could not select database');
    $query = "SELECT * FROM user_accessrights WHERE emp_id=".$emp_id;
	$allowed = 0;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {       
		$allowed = $row[$menu_options]; 
    }
	if($allowed==0) { header('Location: index.php'); }
}
function dropdown_joborderservices($id, $class,$selected) {  
    echo '<select class="'.$class. '" id="'.$id.'" name="'.$id.'" size="1">';    
    echo '<option class="'.$class.'" value=""></option>';    
    $query = "SELECT * FROM tbl_particulars_joborder ORDER BY particular_name";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {       
              if ($row['particular_name'] == $selected) {
                echo '<option class="' . $class . '" value="'.$row['particular_name'].'|'.$row['particular_jobcost'].'" selected>' . $row['particular_name'] . '</option>';
              } else {
                echo '<option class="' . $class . '" value="'.$row['particular_name'].'|'.$row['particular_jobcost'].'" >' . $row['particular_name']. '</option>';
              }
    }
}
//function trancodes_selected_query($trancodes,$type) {  
//    if($trancodes<>"") {
//        $query0 = "SELECT * FROM tbl_trancodes WHERE trancode_id=".$trancodes;    
//        $account_listing0 = mysql_query($query0) or die('Query failed: ' . mysql_error());
//        while ($accountlst0 = mysql_fetch_array($account_listing0, MYSQL_ASSOC)) {      
//            if($type=="C")  {$trancodes  =  $accountlst0['trancode_credit'];}
//            else            {$trancodes  =  $accountlst0['trancode_debit'];}   
//       }    
//        $trancodes_values  = explode("~",$trancodes);
//        $trancodes_listing = "";
//        $query1 = "SELECT *,
//                        (SELECT account_code FROM tbl_chartaccounts_parent WHERE
//                            tbl_chartaccounts_parent.account_id=tbl_chartaccounts.account_parentid) AS parent_accountnumber
//                       FROM tbl_chartaccounts ORDER BY account_id";    
//        $account_listing1 = mysql_query($query1) or die('Query failed: ' . mysql_error());
//        while ($accountlst1 = mysql_fetch_array($account_listing1, MYSQL_ASSOC)) {       
//            $account_id   =  $accountlst1['account_id'];
//            $account_name =  $accountlst1['account_code'].' - '.$accountlst1['account_name'];
//            if (in_array($account_id,$trancodes_values)) {
//               $trancodes_listing = $trancodes_listing.$account_name.'<br/>';
//            }
//        }     
//        $trancodes_listing = substr($trancodes_listing,0,strlen($trancodes_listing)-5);
//    }
//    else {$trancodes_listing = ""; }
//    return $trancodes_listing;
//    
//}


function get_collector_name($id) {  
		if($id=="") {
			return "";
		} else {
			$query = "SELECT tbl_employees.emp_id,emp_lastname,emp_firstname FROM tbl_employees WHERE emp_id=".$id;
			$employee_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
			while ($emplst = mysql_fetch_array($employee_listing, MYSQL_ASSOC)) {
				return ucwords(strtolower($emplst['emp_lastname'])).", ".ucwords(strtolower($emplst['emp_firstname']));            
			}
		}
}

function trancodes_selected($trancodes) {  
    $trancodes_values  = explode("~",$trancodes);
    $trancodes_listing = "";
    $query1 = "SELECT *,
                    (SELECT account_code FROM tbl_chartaccounts_parent WHERE
                        tbl_chartaccounts_parent.account_id=tbl_chartaccounts.account_parentid) AS parent_accountnumber
                    FROM tbl_chartaccounts ORDER BY account_id";    
    $account_listing1 = mysql_query($query1) or die('Query failed: ' . mysql_error());
    while ($accountlst1 = mysql_fetch_array($account_listing1, MYSQL_ASSOC)) {       
        $account_id   =  $accountlst1['account_id'];
        $account_name =  $accountlst1['account_code'];
        if (in_array($account_id,$trancodes_values)) {
           $trancodes_listing = $trancodes_listing.$account_name.'<br>';
        }
    }     
    return $trancodes_listing;
}
function iif($tst,$cmp,$bad) {
    return(($tst == $cmp)?$cmp:$bad);
}
function listbox_employees($id, $class, $selected) {  			
    echo '<select class="'.$class. '" id="'.$id.'" name="'.$id.'" size="19">';    
    echo '<option class="'.$class.'" value=""></option>';    
    $query = "SELECT tbl_employees.emp_id,tbl_employees.emp_lastname,tbl_employees.emp_firstname
              FROM tbl_employees ORDER BY tbl_employees.emp_lastname,tbl_employees.emp_firstname";
    $employee_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($emplst = mysql_fetch_array($employee_listing, MYSQL_ASSOC)) {       
  	          $employee_name = ucwords(strtolower($emplst['emp_lastname'])).", ".ucwords(strtolower($emplst['emp_firstname']));
              echo '<option class="' . $class . '" value='.$emplst['emp_id'].'>' . $employee_name. '</option>';
    }
}
function dropdown_employees($id, $class, $selected) {  
    echo '<select class="'.$class. '" id="'.$id.'" name="'.$id.'" size="1" >';    
    echo '<option class="'.$class.'" value=""></option>';    
    $query = "SELECT tbl_employees.emp_id,tbl_employees.emp_lastname,tbl_employees.emp_firstname
              FROM tbl_employees ORDER BY tbl_employees.emp_lastname,tbl_employees.emp_firstname";
    $employee_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($emplst = mysql_fetch_array($employee_listing, MYSQL_ASSOC)) {       
  	          $employee_name = ucwords(strtolower($emplst['emp_lastname'])).", ".ucwords(strtolower($emplst['emp_firstname']));
              if ($emplst['emp_id'] == $selected[0]) {
                echo '<option class="' . $class . '" value='.$emplst['emp_id'].' selected>' . $employee_name. '</option>';
              } else {
                echo '<option class="' . $class . '" value='.$emplst['emp_id'].'>' . $employee_name. '</option>';
              }
    }
}
function dropdown_employees_disabled($id, $class, $selected) {  
    echo '<select class="'.$class. '" id="'.$id.'" name="'.$id.'" size="1" disabled >';    
    echo '<option class="'.$class.'" value=""></option>';    
    $query = "SELECT tbl_employees.emp_id,tbl_employees.emp_lastname,tbl_employees.emp_firstname
              FROM tbl_employees ORDER BY tbl_employees.emp_lastname,tbl_employees.emp_firstname";
    $employee_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($emplst = mysql_fetch_array($employee_listing, MYSQL_ASSOC)) {       
  	          $employee_name = ucwords(strtolower($emplst['emp_lastname'])).", ".ucwords(strtolower($emplst['emp_firstname']));
              if ($emplst['emp_id'] == $selected[0]) {
                echo '<option class="' . $class . '" value='.$emplst['emp_id'].' selected>' . $employee_name. '</option>';
              } else {
                echo '<option class="' . $class . '" value='.$emplst['emp_id'].'>' . $employee_name. '</option>';
              }
    }
}

function dropdown_customers($id, $class, $selected) {  
    echo '<select class="'.$class. '" id="'.$id.'" name="'.$id.'" size="1">';    
    echo '<option class="'.$class.'" value=""></option>';    
        $query = "SELECT tbl_customers.cust_id,concat(cust_lastname,', ',cust_firstname) as customer_name  
                        FROM tbl_customers ORDER BY cust_lastname,cust_firstname";
        $customer_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
        while ($cuslst = mysql_fetch_array($customer_listing, MYSQL_ASSOC)) {       
              if ($cuslst['cust_id'] == $selected[0]) {
                echo '<option class="' . $class . '" value='.$cuslst['cust_id'].' selected>' . $cuslst['customer_name'] . '</option>';
              } else {
                echo '<option class="' . $class . '" value='.$cuslst['cust_id'].'>' .$cuslst['customer_name']. '</option>';
              }        
        }    
}


function dropdown_customers2($id, $class, $selected) {  
    echo '<select class="'.$class. '" id="'.$id.'" name="'.$id.'" size="1">';    
    echo '<option class="'.$class.'" value=""></option>';    
        $query = "SELECT tbl_customers.cust_id,concat(cust_lastname,', ',cust_firstname) as customer_name  
                        FROM tbl_customers ORDER BY cust_lastname,cust_firstname";
        $customer_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
        while ($cuslst = mysql_fetch_array($customer_listing, MYSQL_ASSOC)) {       
              if ($cuslst['cust_id'] == $selected) {
                echo '<option class="' . $class . '" value='.$cuslst['cust_id'].' selected>' . $cuslst['customer_name'] . '</option>';
              } else {
                echo '<option class="' . $class . '" value='.$cuslst['cust_id'].'>' .$cuslst['customer_name']. '</option>';
              }        
        }    
}

function updatepaymentschedule($owner_id,$amount,$or_number,$sales_id) {   
   //$query1 = "SELECT * FROM tbl_paymentschedules WHERE ps_ownerid=".$owner_id." AND ps_ispaid='N'";
   $query1 = "SELECT * FROM tbl_paymentschedules WHERE ps_salesid=".$sales_id." AND ps_ispaid='N'";
   $recordset1 = mysql_query($query1) or die('Query failed: ' . mysql_error());
   while ($row1 = mysql_fetch_array($recordset1, MYSQL_ASSOC)) {
       //if amount is > amortization
       if($amount>$row1['ps_balance']) {
          $ps_id        = $row1['ps_id']; 
          $amount       = $amount - $row1['ps_balance'];    
          if($row1['ps_refno']=="" OR $row1['ps_refno']==NULL) { $ps_refno = 'OR'.$or_number; } else { $ps_refno = $row1['ps_refno'].','.'OR'.$or_number; }             
          $sql = "UPDATE tbl_paymentschedules SET ps_ispaid='Y',ps_refno='$ps_refno'  WHERE ps_id=".$ps_id;
          mysql_query($sql) or die('<div class="message">Error! ' . mysql_error() . '</div>');          
       } 
       else {
           //if amount is equal to amortization
           if($amount==$row1['ps_balance']) {
              $ps_id = $row1['ps_id']; 
              if($row1['ps_refno']=="" OR $row1['ps_refno']==NULL) { $ps_refno = 'OR'.$or_number; } else { $ps_refno = $row1['ps_refno'].','.'OR'.$or_number; }             
              $sql = "UPDATE tbl_paymentschedules SET ps_ispaid='Y',ps_refno='$ps_refno' WHERE ps_id=".$ps_id;
              mysql_query($sql) or die('<div class="message">Error! ' . mysql_error() . '</div>');          
              break;
           }
           //if amount is less than to amortization
           if($amount<$row1['ps_balance']) {
              $ps_id        = $row1['ps_id']; 
              $new_amount   = $row1['ps_balance'] - $amount;    
              if($row1['ps_refno']=="" OR $row1['ps_refno']==NULL) { $ps_refno = 'OR'.$or_number; } else { $ps_refno = $row1['ps_refno'].','.'OR'.$or_number; }             
              $sql = "UPDATE tbl_paymentschedules SET ps_balance='$new_amount',ps_refno='$ps_refno' WHERE ps_id=".$ps_id;
              mysql_query($sql) or die('<div class="message">Error! ' . mysql_error() . '</div>');          
              break;
           }            
       }
         
   }        
}

function postcommission($trans_journal_id) {    
   $query1 = "SELECT * FROM tbl_transaction_journal WHERE journal_id=".$trans_journal_id;
   $recordset1 = mysql_query($query1) or die('Query failed: ' . mysql_error());
   while ($row1 = mysql_fetch_array($recordset1, MYSQL_ASSOC)) {
        $transaction_ref = $row1['transaction_ref'];
        $sales_amount    = $row1['amount'];
        $pointer         = $row1['pointer'];        
        $query2 = "SELECT sales_agent_id FROM tbl_sales WHERE sales_id=".$pointer;
        $recordset2 = mysql_query($query2) or die('Query failed: ' . mysql_error());
        while ($row2 = mysql_fetch_array($recordset2, MYSQL_ASSOC)) {
            $sales_agent_id  = $row2['sales_agent_id']; 
        }     
   }        
   $voucher_date            = date('Y-m-d');
   $voucher_payee           = 'E'.$sales_agent_id;
   $voucher_particulars     = "Sales Comission - ".substr($transaction_ref,0,17);
   $voucher_amount          = $sales_amount * (SALES_COMMISSION_PERCENT/100);
   $voucher_preparedby      = $_SESSION['userid'];
   $voucher_checkedby       = 0;
   $voucher_approvedby      = 0;
   $voucher_trancode        = NULL;
   $voucher_note1           = "";
   $voucher_note2           = "";
   $voucher_note3           = "";
   $voucher_datereceived    = NULL;
   $voucher_receivedby      = NULL;
   $voucher_type            = "AP2"; //Accounts Payable - Commission
   post2voucher($voucher_date,$voucher_payee,$voucher_particulars,$voucher_amount,$voucher_preparedby,$voucher_checkedby,$voucher_approvedby,$voucher_trancode,$voucher_note1,$voucher_note2,$voucher_note3,$voucher_datereceived,$voucher_receivedby,$voucher_type);
}
function dropdown_couponevents($class, $id, $selected) {
    echo '<select class="'.$class.'" id="'.$id.'" name="'.$id.'" size="1">';    
    echo '<option class="'.$class.'" value=""></option>';   
    $query = "SELECT * FROM tbl_couponevents ORDER BY event_id";
    $event_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($eventlst = mysql_fetch_array($event_listing, MYSQL_ASSOC)) {       
          if ($eventlst['event_id'] == $selected) {
              echo '<option class="'.$class.'" value='.$eventlst['event_id'].' selected>'.$eventlst['event_name'] . '</option>';
          } else {
              echo '<option class="'.$class.'" value='.$eventlst['event_id'].'>'.$eventlst['event_name']. '</option>';
          }
    }       
}
function getadjustmentaction($id){
    $query = "SELECT a_action FROM tbl_adjustments WHERE a_id=".$id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        return $row['a_action'];
    }  
}  

function get_voucher_type($voucher_id){
    //get voucher type
    $query = "SELECT voucher_type FROM tbl_vouchers WHERE voucher_id=".$voucher_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        return $row['voucher_type'];
    }  
}    

function get_trancode_category($journal_id){
    //get Journal trancode_id
    $query = "SELECT category FROM tbl_transaction_journal WHERE journal_id=".$journal_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        return $row['category'];
    }   
}

function getbranch_name($id) {
	if($id<>"") {
		$query = "SELECT branch_name FROM tbl_branches WHERE branch_id=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			$branch_name = $row['branch_name'];
		}   
		return $branch_name;
	}
}
function getbranch_id($id,$category) {
    if($category=="C") {
        $query = "SELECT cust_branch_id as branch_id FROM tbl_customers WHERE cust_id=".$id;
    } 
    if($category=="V") {
        $query = "SELECT ven_branchid as branch_id FROM tbl_vendor WHERE ven_id=".$id;
    }     
    if($category=="E") {
        $query = "SELECT department_branchid as branch_id FROM tbl_departments 
                        WHERE tbl_departments.department_id=
                        (SELECT emp_department_id FROM tbl_employees WHERE tbl_employees.emp_id=".$id.")";
    }        
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $branch_id = $row['branch_id'];
    }   
    return $branch_id;
}
function post2journal($posted_date,$trancode_id,$amount,$particulars,$posted_by,$branch_owner,$category,$trancode_data,$pointer,$journal_history) {
    $isposted = 'N';
    $sql = "INSERT INTO tbl_transaction_journal 
                    (
                        posted_date,
                        trancode_id,
                        amount,
                        particulars,
                        posted_by,
                        branch_owner,
                        isposted,
                        category,
						trancode_data,
						pointer,
						journal_history
                     )
                     VALUES 
                     (
                        '$posted_date',
                        '$trancode_id',
                        '$amount',
                        '$particulars',
                        '$posted_by',
                        '$branch_owner',
                        '$isposted',
                        '$category',
						'$trancode_data',
						'$pointer',
						'$journal_history'
                      )";    
    mysql_query($sql) or  die('<div class="message">Error! ' . $sql . '</div>'); 
}
function get_pettycashnumber() {
  $dbconn = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD) or die('Could not connect: ' . mysql_error());
  mysql_select_db(DB_DATABASE) or die('Could not select database');
  $query = "SELECT MAX(voucher_id) AS last_id FROM tbl_pettycash";
  $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
  $id = mysql_insert_id();
  while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {return 'REF-'.number_pad($row['last_id']+1,5);}
}
function get_vouchernumber() {
  $dbconn = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD) or die('Could not connect: ' . mysql_error());
  mysql_select_db(DB_DATABASE) or die('Could not select database');
  $query = "SELECT MAX(voucher_id) AS last_id FROM tbl_vouchers";
  $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
  $id = mysql_insert_id();
  while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {return 'V'.number_pad($row['last_id']+1,5);}
}
function get_newvoucher() {
  $query = "SELECT Auto_increment as voucher_id FROM information_schema.tables WHERE table_name='tbl_vouchers';";
  $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
  while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {echo 'V'.number_pad($row['voucher_id'],5);}
}
function vouchertrancodes($id, $class, $type, $selected, $trancode_category) {  
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="1">';    
    $query = "SELECT * FROM tbl_trancodes WHERE trancode_category='".$trancode_category."' ORDER BY trancode_id";    
    $trancode_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
    echo '<option class="'.$class.'" value=""></option>';    
    while ($trancodelst = mysql_fetch_array($trancode_listing, MYSQL_ASSOC)) {       
          if ($trancodelst['trancode_id'] == $selected[0]) {
              echo '<option class="' . $class . '" value='.$trancodelst['trancode_id'].' selected>' . $trancodelst['trancode_id'].' - '.$trancodelst['trancode_description'] . '</option>';
          } else {
              echo '<option class="' . $class . '" value='.$trancodelst['trancode_id'].'>' . $trancodelst['trancode_id'].' - '.$trancodelst['trancode_description']. '</option>';
          }
    }
    echo '</select>';
}
function get_new_trancode() {
  $query = "SELECT Auto_increment as trancode_id FROM information_schema.tables WHERE table_name='tbl_trancodes';";
  $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
  while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {echo $row['trancode_id'];}
}
function dropdown_trancode_category($id,$selected) {
    $query = "SELECT * from tbl_trancode_categories ORDER BY category_name ASC";
    $categories = mysql_query($query) or die('Query failed: ' . mysql_error());
    echo '<select class="dropdown_ledger1" id="'.$id.'" name="'.$id.'" size="1" >';   
    echo '<option class="dropdown_ledger1" value=""></option>';
    while ($categorylst = mysql_fetch_array($categories, MYSQL_ASSOC)) {
        if($categorylst['category_name']==$selected) {
            echo '<option class="dropdown_ledger1" value="'.$categorylst['category_name'].'" selected>'.$categorylst['category_name'].'</option>';        
        }
        else {
            echo '<option class="dropdown_ledger1" value="'.$categorylst['category_name'].'">'.$categorylst['category_name'].'</option>';                 
        }
    }       
    echo '</select>';
}
function getaccountname($id,$category) {  
	if($id=="") { return "UNDEFINED"; }
	else {
		if($category=="E") {
			$query = "SELECT tbl_employees.emp_id, tbl_employees.emp_lastname, tbl_employees.emp_firstname FROM tbl_employees WHERE emp_id=".$id;
			$employee_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
			while ($emplst = mysql_fetch_array($employee_listing, MYSQL_ASSOC)) {
				return ucwords(strtolower($emplst['emp_lastname'])).", ".ucwords(strtolower($emplst['emp_firstname']));  
			}	
		}
		if($category=="C") {    
			$query = "SELECT tbl_customers.cust_id,concat(cust_lastname,', ',cust_firstname) as customer_name FROM tbl_customers WHERE cust_id=".$id;
			$customer_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
			while ($cuslst = mysql_fetch_array($customer_listing, MYSQL_ASSOC)) {return $cuslst['customer_name'];}    
		}
		if($category=="V") {     
			$query = "SELECT tbl_vendor.ven_id, ven_companyname as vendor_name FROM tbl_vendor WHERE ven_id=".$id;
			$customer_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
			while ($cuslst = mysql_fetch_array($customer_listing, MYSQL_ASSOC)) {return $cuslst['vendor_name'];}  
		}
	}
}
function get_employee_accountname_short($id) {  
		if($id<>"") {
			$query = "SELECT tbl_employees.emp_id,emp_lastname,emp_firstname FROM tbl_employees WHERE emp_id=".$id;
			$employee_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
			while ($emplst = mysql_fetch_array($employee_listing, MYSQL_ASSOC)) {
				return ucwords(strtolower($emplst['emp_lastname'])).", ".ucwords(strtolower($emplst['emp_firstname']));            
			}
		} else { return ""; }
}
function get_employee_number($id) {  
        $query = "SELECT tbl_employees.emp_id,emp_lastname,emp_firstname FROM tbl_employees WHERE emp_id=".$id;
        $employee_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
        while ($emplst = mysql_fetch_array($employee_listing, MYSQL_ASSOC)) {
		    $emp_no = "E".$id.strtoupper(substr($emplst['emp_lastname'],0,1)).strtoupper(substr($emplst['emp_firstname'],0,1));
            return $emp_no;           
        }
}
function get_employee_tagname($id) {  
        $query = "SELECT tbl_employees.emp_id,emp_lastname,emp_firstname FROM tbl_employees WHERE emp_id=".$id;
        $employee_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
        while ($emplst = mysql_fetch_array($employee_listing, MYSQL_ASSOC)) {
		    $emp_no = "E".$id.strtoupper(substr($emplst['emp_lastname'],0,1)).strtoupper(substr($emplst['emp_firstname'],0,1));
            return ucwords(strtolower($emplst['emp_lastname'])).", ".ucwords(strtolower($emplst['emp_firstname'])).' ('.$emp_no.')';            
        }
}
function get_customer_tagname($id){    
    $query = "SELECT cust_lastname,cust_firstname FROM tbl_customers WHERE cust_id=".$id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
	    $cust_no = "C".$id.strtoupper(substr($emplst['cust_lastname'],0,1)).strtoupper(substr($emplst['cust_firstname'],0,1));
	    $customer_name  = ucwords(strtolower($row['cust_lastname'])).", ".ucwords(strtolower($row['cust_firstname'])).' ('.$cust_no.')'; 
        return $customer_name;
    }   
}
function get_vendor_tagname($id){    
	if($id=="") {
		return "";
	} else {
		$query = "SELECT ven_lastname, ven_firstname, ven_companyname FROM tbl_vendor WHERE ven_id=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			$vendor_name = ucwords(strtolower($row['ven_companyname']));
			$ven_no = "V".$id.strtoupper(substr($emplst['ven_lastname'],0,1)).strtoupper(substr($emplst['ven_firstname'],0,1));
			return $vendor_name.' ('.$ven_no.')'; 
		}   
	}
}
function getaccountslisting_all($id, $class, $selected) {  
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="1">';    
    echo '<option class="'.$class.'" value=""></option>';    
    echo "<optgroup label='Employee'>";
        $query = "SELECT tbl_employees.emp_id,concat(tbl_employees.emp_lastname,', ',tbl_employees.emp_firstname) as employee_name  
                    FROM tbl_employees ORDER BY tbl_employees.emp_lastname,tbl_employees.emp_firstname";
        $employee_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
        while ($emplst = mysql_fetch_array($employee_listing, MYSQL_ASSOC)) {       
              if ($emplst['emp_id'] == $selected[0]) {
                echo '<option class="' . $class . '" value=E'.$emplst['emp_id'].' selected>' . $emplst['employee_name'] . '</option>';
              } else {
                echo '<option class="' . $class . '" value=E'.$emplst['emp_id'].'>' . $emplst['employee_name']. '</option>';
              }
        }
    echo "</optgroup>";
    echo "<optgroup label='Customers'>";
        $query = "SELECT tbl_customers.cust_id,concat(cust_lastname,', ',cust_firstname) as customer_name  
                        FROM tbl_customers ORDER BY cust_lastname,cust_firstname";
        $customer_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
        while ($cuslst = mysql_fetch_array($customer_listing, MYSQL_ASSOC)) {       
              if ($cuslst['cust_id'] == $selected[0]) {
                echo '<option class="' . $class . '" value=C'.$cuslst['cust_id'].' selected>' . $cuslst['customer_name'] . '</option>';
              } else {
                echo '<option class="' . $class . '" value=C'.$cuslst['cust_id'].'>' .$cuslst['customer_name']. '</option>';
              }        
        }    
    echo "</optgroup>";
    echo "<optgroup label='Vendors'>";    
        $query = "SELECT tbl_vendor.ven_id, ven_companyname as vendor_name 
                        FROM tbl_vendor ORDER BY ven_companyname";
        $customer_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
        while ($cuslst = mysql_fetch_array($customer_listing, MYSQL_ASSOC)) {       
              if ($cuslst['ven_id'] == $selected[0]) {
                echo '<option class="' . $class . '" value=V'.$cuslst['ven_id'].' selected>' .$cuslst['vendor_name'] . '</option>';
              } else {
                echo '<option class="' . $class . '" value=V'.$cuslst['ven_id'].'>' .$cuslst['vendor_name']. '</option>';
              }           
        }  
    echo "</optgroup>";
    echo '</select>';
}
function post2voucher($voucher_date,$voucher_payee,$voucher_particulars,$voucher_amount,$voucher_preparedby,$voucher_checkedby,$voucher_approvedby,$voucher_trancode,$voucher_note1,$voucher_note2,$voucher_note3,$voucher_datereceived,$voucher_receivedby,$voucher_type,$voucher_branch) {
         $sql = "INSERT INTO tbl_vouchers
                (                  
                  voucher_date,
                  voucher_payee,
                  voucher_particulars,
                  voucher_amount,
                  voucher_preparedby,
                  voucher_checkedby,
                  voucher_approvedby,
                  voucher_status,
                  voucher_note1,
                  voucher_note2,
                  voucher_note3,
                  voucher_receivedby,        
                  voucher_type,
				  voucher_branch,
				  voucher_trancode
                )
            VALUES 
                ( 
                  '$voucher_date',
                  '$voucher_payee',
                  '$voucher_particulars',
                  '$voucher_amount',
                  '$voucher_preparedby',
                  '$voucher_checkedby',
                  '$voucher_approvedby',
                  'R',
                  '$voucher_note1',
                  '$voucher_note2',
                  '$voucher_note3',
                  '$voucher_receivedby',
                  '$voucher_type',
				  '$voucher_branch',
				  '$voucher_trancode'
                )";           
    mysql_query($sql) or  die('<div class="message">Error! ' . mysql_error() . '</div>');         
}
function getaccountslisting($id, $class, $type, $selected,$category) {  
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="1">';    
    if($category=="E") {
        $query = "SELECT tbl_employees.emp_id,concat(tbl_employees.emp_lastname,', ',tbl_employees.emp_firstname) as employee_name  
                    FROM tbl_employees ORDER BY tbl_employees.emp_lastname,tbl_employees.emp_firstname";
        $employee_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
        echo '<option class="'.$class.'" value=""></option>';    
        while ($emplst = mysql_fetch_array($employee_listing, MYSQL_ASSOC)) {       
              if ($emplst['emp_id'] == $selected[0]) {
                echo '<option class="' . $class . '" value='.$emplst['emp_id'].' selected>' . $emplst['employee_name'] . '</option>';
              } else {
                echo '<option class="' . $class . '" value='.$emplst['emp_id'].'>' . $emplst['employee_name']. '</option>';
              }
        }
    }
    if($category=="C") {    
    //Get Customer Listing
        $query = "SELECT tbl_customers.cust_id,concat(cust_lastname,', ',cust_firstname) as customer_name  
                        FROM tbl_customers ORDER BY cust_lastname,cust_firstname";
        $customer_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
        echo '<option class="'.$class.'" value=""></option>';            
        while ($cuslst = mysql_fetch_array($customer_listing, MYSQL_ASSOC)) {       
              if ($cuslst['cust_id'] == $selected[0]) {
                echo '<option class="' . $class . '" value='.$cuslst['cust_id'].' selected>' . $cuslst['customer_name'] . '</option>';
              } else {
                echo '<option class="' . $class . '" value='.$cuslst['cust_id'].'>' .$cuslst['customer_name']. '</option>';
              }        
        }    
    }
    if($category=="V") {     
        //Get Vendors Listing
        $query = "SELECT tbl_vendor.ven_id, ven_companyname as vendor_name 
                        FROM tbl_vendor ORDER BY ven_companyname";
        $customer_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
        echo '<option class="'.$class.'" value=""></option>';            
        while ($cuslst = mysql_fetch_array($customer_listing, MYSQL_ASSOC)) {       
              if ($cuslst['ven_id'] == $selected[0]) {
                echo '<option class="' . $class . '" value='.$cuslst['ven_id'].' selected>' .$cuslst['vendor_name'] . '</option>';
              } else {
                echo '<option class="' . $class . '" value='.$cuslst['ven_id'].'>' .$cuslst['vendor_name']. '</option>';
              }           
        }  
    }
    echo '</select>';
}

function getaccounts($id, $class, $type, $selected) {    
    //Get Employee Listing
    $query = "SELECT tbl_employees.emp_id,concat(tbl_employees.emp_lastname,', ',tbl_employees.emp_firstname) as employee_name  
                FROM tbl_employees ORDER BY tbl_employees.emp_lastname,tbl_employees.emp_firstname";
    $employee_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="1">';    
    echo '<option class="'.$class.'" value=""></option>';    
    echo '<optgroup label="Employees">';
    while ($emplst = mysql_fetch_array($employee_listing, MYSQL_ASSOC)) {       
          if ($emplst['emp_id'] == $selected[0]) {
            echo '<option class="' . $class . '" value=E'.$emplst['emp_id'].' selected>' . $emplst['employee_name'] . '</option>';
          } else {
            echo '<option class="' . $class . '" value=E'.$emplst['emp_id'].'>' . $emplst['employee_name']. '</option>';
          }
    }
    echo '</optgroup>';    
    //Get Customer Listing
    $query = "SELECT tbl_customers.cust_id,concat(cust_lastname,', ',cust_firstname) as customer_name  
                    FROM tbl_customers ORDER BY cust_lastname,cust_firstname";
    $customer_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
    echo '<optgroup label="Customers">';
    while ($cuslst = mysql_fetch_array($customer_listing, MYSQL_ASSOC)) {       
          if ($cuslst['cust_id'] == $selected[0]) {
            echo '<option class="' . $class . '" value=C'.$cuslst['cust_id'].' selected>' . $cuslst['customer_name'] . '</option>';
          } else {
            echo '<option class="' . $class . '" value=C'.$cuslst['cust_id'].'>' .$cuslst['customer_name']. '</option>';
          }        
    }    
    echo '</optgroup>';    
    echo '<optgroup label="Vendors">';    
    //Get Vendors Listing
    $query = "SELECT tbl_vendor.ven_id, ven_companyname as vendor_name 
                    FROM tbl_vendor ORDER BY ven_companyname";
    $customer_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($cuslst = mysql_fetch_array($customer_listing, MYSQL_ASSOC)) {       
          if ($cuslst['ven_id'] == $selected[0]) {
            echo '<option class="' . $class . '" value=V'.$cuslst['ven_id'].' selected>' .$cuslst['vendor_name'] . '</option>';
          } else {
            echo '<option class="' . $class . '" value=V'.$cuslst['ven_id'].'>' .$cuslst['vendor_name']. '</option>';
          }           
    }    
    echo '</optgroup>';    
    echo '</select>';
}

function get_trancode_debit_account ($trancode_id){    
    $query = "SELECT trancode_debit,
                (SELECT account_number FROM tbl_chartaccounts_parent WHERE account_id=
                    (SELECT account_parentid FROM tbl_chartaccounts WHERE tbl_chartaccounts.account_id=tbl_trancodes.trancode_debit)) AS parent_account_number,
                (SELECT account_number FROM tbl_chartaccounts WHERE account_id=tbl_trancodes.trancode_debit) AS account_number,
                (SELECT account_name FROM tbl_chartaccounts WHERE account_id=tbl_trancodes.trancode_debit) AS account_name
                FROM tbl_trancodes WHERE trancode_id=".$trancode_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        return $row['parent_account_number'].'-'.number_pad($row['account_number'],4).' - '.$row['account_name'];
    }   
}
function get_trancode_credit_account ($trancode_id){    
    $query = "SELECT trancode_credit,
                (SELECT account_number FROM tbl_chartaccounts_parent WHERE account_id=
                    (SELECT account_parentid FROM tbl_chartaccounts WHERE tbl_chartaccounts.account_id=tbl_trancodes.trancode_credit)) AS parent_account_number,
                (SELECT account_number FROM tbl_chartaccounts WHERE account_id=tbl_trancodes.trancode_credit) AS account_number,
                (SELECT account_name FROM tbl_chartaccounts WHERE account_id=tbl_trancodes.trancode_credit) AS account_name
                FROM tbl_trancodes WHERE trancode_id=".$trancode_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        return $row['parent_account_number'].'-'.number_pad($row['account_number'],4).' - '.$row['account_name'];
    }   
}
function get_trancode_credit1($trancode_id){    
    $query = "SELECT trancode_credit FROM tbl_trancodes WHERE trancode_id=".$trancode_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        return $row['trancode_credit'];
    }   
}
function get_vendorname($id){    
    $query = "SELECT ven_companyname FROM tbl_vendor WHERE ven_id=".$id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $vendor_name = ucwords(strtolower($row['ven_companyname']));
        return $vendor_name;
    }   
}
function get_employeename($id){   
    if($id<>"") {
        $query = "SELECT emp_lastname,emp_firstname FROM tbl_employees WHERE emp_id=".$id;
        $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
        while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
            $employee_name   = ucwords(strtolower($row['emp_lastname'])).", ".ucwords(strtolower($row['emp_firstname']));
            return $employee_name;
        }  
    }
}
function get_customername($id){    
    $query = "SELECT cust_lastname,cust_firstname FROM tbl_customers WHERE cust_id=".$id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $customer_name  = ucwords(strtolower($row['cust_lastname'])).", ".ucwords(strtolower($row['cust_firstname']));
        return $customer_name;
    }   
}
function get_ledgerytd($account_id){    
    $query = "SELECT account_ytd FROM tbl_chartaccounts WHERE account_id=".$account_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        return $row['account_ytd'];
    }   
}

function get_currentpayroll_salarygrade_rate($id) {
	$sg_rate = 0;
	$query = "SELECT sg_rate FROM tbl_salarygrade 
				WHERE (SELECT salary_grade_id 
							FROM tbl_employees 
							WHERE tbl_employees.emp_id=".$id.")=tbl_salarygrade.sg_id"; 
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $sg_rate = $row['sg_rate'];
    }   
    return $sg_rate;
}

function get_employee_address($id){    
    $query = "SELECT emp_address_street,emp_address_town,emp_address_city,emp_address_zipcode,emp_address_country FROM tbl_employees WHERE emp_id=".$id." LIMIT 1";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $address_street = $row['emp_address_street'];
        $address_town = $row['emp_address_town'];
        $address_city = $row['emp_address_city'];
        $address_zipcode = $row['emp_address_zipcode'];
        $address_country = $row['emp_address_country'];
		$employee_address = "<b>".$address_street.'<br/>'.$address_town." ".$address_city." ".$address_zipcode."</b>";
    }   
    return $employee_address;
}
function get_trancode_debit($trancode_id){    
    $query = "SELECT trancode_debit FROM tbl_trancodes WHERE trancode_id=".$trancode_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        return $row['trancode_debit'];
    }   
}
function get_trancode_credit($trancode_id){    
    $query = "SELECT trancode_credit FROM tbl_trancodes WHERE trancode_id=".$trancode_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        return $row['trancode_credit'];
    }   
}

function get_trancode($journal_id){
    //get Journal trancode_id
    $query = "SELECT trancode_id FROM tbl_transaction_journal WHERE journal_id=".$journal_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        return $trancode_id = $row['trancode_id'];
    }   
}
function listbox_inventorycategory($selected) {
    echo '<select class="listbox2" id="account_groupid" name="account_groupid" size="1">';
    echo '<option class="" value=""></option>';
    $query    = "SELECT * from tbl_category_inventory_parent ORDER BY category_name ASC";
    $category = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($categorylst = mysql_fetch_array($category, MYSQL_ASSOC)) { 
          echo '<option value="'.$categorylst['category_id'].'">'.$categorylst['category_name'].'</option>';                        
    }     
    echo '</select>';  
}
function dropdown_parentcategory1($selected) {
    $query = "SELECT * from tbl_chartaccounts_parent ORDER BY account_name ASC";
    $categories = mysql_query($query) or die('Query failed: ' . mysql_error());
    echo '<select class="dropdown_ledger" id="sub_parent_id" name="sub_parent_id" size="1" >';   
    echo '<option class="dropdown_ledger" value="" selected></option>';
    while ($categorylst = mysql_fetch_array($categories, MYSQL_ASSOC)) {
        if($categorylst['account_id']==$selected) {
            echo '<option class="dropdown_ledger" value="'.$categorylst['account_id'].'" selected>'.$categorylst['account_number'].' - '.$categorylst['account_name'].'</option>';        
        }
        else {
            echo '<option class="dropdown_ledger" value="'.$categorylst['account_id'].'">'.$categorylst['account_number'].' - '.$categorylst['account_name'].'</option>';                 
        }
    }       
    echo '</select>';
}
function dropdown_coa($selected) {
    $query = "SELECT *,
                (SELECT account_number FROM tbl_chartaccounts_parent WHERE tbl_chartaccounts_parent.account_id=account_parentid) AS parent_account_number,
                (SELECT account_name FROM tbl_chartaccounts_parent WHERE tbl_chartaccounts_parent.account_id=account_parentid) AS parent_account_name
              FROM tbl_chartaccounts ORDER BY account_parentid ASC";
    $categories = mysql_query($query) or die('Query failed: ' . mysql_error());
    echo '<select class="listbox3" id="coa_id" name="coa_id" size="1" >';
    echo '<option class="listbox3" value=""></option>';        
    $parent_account_name_group = "";
    while ($categorylst = mysql_fetch_array($categories, MYSQL_ASSOC)) {
        $parent_account_name    = $categorylst['parent_account_name'];
        $parent_account_number  = number_pad($categorylst['parent_account_number'], 4);
        $sub_account_number     = number_pad($categorylst['account_number'], 4);
        $description = $parent_account_number.'-'.$sub_account_number.' - '.$categorylst['account_name'];
        if($parent_account_name_group<>$categorylst['parent_account_name']) {
            echo '</optgroup>';
            echo '<optgroup label="'.$parent_account_name.'">';            
            if($categorylst['account_id']==$selected) {
                echo '<option class="listbox3" value="'.$categorylst['account_id'].'" selected>'.$description.'</option>';        
            }
            else {
                echo '<option class="listbox3" value="'.$categorylst['account_id'].'">'.$description.'</option>';                 
            }               
        }
        else {    
            if($categorylst['account_id']==$selected) {
                echo '<option class="listbox3" value="'.$categorylst['account_id'].'" selected>'.$description.'</option>';        
            }
            else {
                echo '<option class="listbox3" value="'.$categorylst['account_id'].'">'.$description.'</option>';                 
            }
        }
        $parent_account_name_group = $parent_account_name;
    }       
    echo '</select>';
}
function get_coamaincategory_lastvalue() { 
        $query1 = "SELECT coa_main_category FROM tbl_current_values";
        $recordset1 = mysql_query($query1) or die('Query failed: ' . mysql_error());
        while ($row1 = mysql_fetch_array($recordset1, MYSQL_ASSOC)) { return $row1['coa_main_category']+1000; } 
}
function post2journal2($posted_date, $trancode_id, $amount, $transaction_ref, $posted_by, $owner_id, $branch_id,$pointer, $category) {
    $isposted = 'N';
    $sql = "INSERT INTO tbl_transaction_journal 
                    (
                        posted_date,
                        trancode_id,
                        amount,
                        transaction_ref,
                        posted_by,
                        branch_id,
                        isposted,
                        owner_id,
                        pointer,
                        category
                     )
                     VALUES 
                     (
                        '$posted_date',
                        '$trancode_id',
                        '$amount',
                        '$transaction_ref',
                        '$posted_by',
                        '$branch_id',
                        '$isposted',
                        '$owner_id',
                        '$pointer',
                        '$category'
                      )";        
    mysql_query($sql) or  die('<div class="message">Error! ' . $sql . '</div>');  
    $journal_id = mysql_insert_id();
    postjournal($journal_id);        
    return $journal_id;
}    
function post2transhistory($trans_date,$trans_debit,$trans_credit,$trans_balance,$trans_owner_id,$trans_journal_id,$trans_type) {         
    $sql = "INSERT INTO tbl_transaction_history
                    (
                        trans_date,
                        trans_debit,
                        trans_credit,
                        trans_balance,
                        trans_owner_id,
                        trans_journal_id,
                        trans_type
                     )
                     VALUES 
                     (
                        '$trans_date',
                        '$trans_debit',
                        '$trans_credit',
                        '$trans_balance',
                        '$trans_owner_id',
                        '$trans_journal_id',
                        '$trans_type'
                      )";       
    mysql_query($sql) or  die('<div class="message">Error! ' . mysql_error() . '</div>');     
}

function postnewbalance_customer($customer_id,$new_balance) {
    $sql = "UPDATE tbl_balances SET current_balance='$new_balance' WHERE account_id=".$customer_id;      
    mysql_query($sql) or  die('<div class="message">Error! ' . mysql_error() . '</div>');                  
}
function postnewbalance_employee($employee_id,$new_balance) {
    $sql = "UPDATE tbl_balances SET current_balance='$new_balance' WHERE account_id=".$employee_id;      
    mysql_query($sql) or  die('<div class="message">Error! ' . mysql_error() . '</div>');                  
}
function postnewbalance_vendor($vendor_id,$new_balance) {
    $sql = "UPDATE tbl_balances SET current_balance='$new_balance' WHERE account_id=".$vendor_id;      
    mysql_query($sql) or  die('<div class="message">Error! ' . mysql_error() . '</div>');                  
}
function getjournalid($transaction_ref) {
    //get Journal ID
    $query = "SELECT journal_id FROM tbl_transaction_journal WHERE transaction_ref='".$transaction_ref."'";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $journal_id = $row['journal_id'];
    }   
    return $journal_id;
}

function getbranch($owner_id) {
    $query = "SELECT cust_branch_id FROM tbl_customers WHERE cust_id=".$owner_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $branch_id = $row['cust_branch_id'];
    }   
    return $branch_id;
}

function dropdown_ledger($id, $class, $array, $type, $selected) {
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" sze="1" >';
    if ($selected[0] == "") {
        echo '<option class="' . $class . '" value="" selected></option>';
    }
    foreach ($array as $value) {
        $value = explode(",", $value);
        if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}

function get_customer_balance($id) {
    $dbconn = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD) or die('Could not connect: ' . mysql_error());
    mysql_select_db(DB_DATABASE) or die('Could not select database');
    $subquery = "SELECT cust_id,cust_lastname,cust_firstname,cust_middlename,
                    (SELECT current_balance FROM tbl_balances WHERE tbl_balances.account_id=cust_id) AS cust_balance
                    FROM tbl_customers WHERE cust_id=".$id;
    $subrecordset = mysql_query($subquery) or die('Query failed: ' . mysql_error());
    while ($subrow = mysql_fetch_array($subrecordset, MYSQL_ASSOC)) {
        $cust_id = $subrow['cust_id'];
        $customer_name = ucwords(strtolower($subrow['cust_lastname'])). ", ".ucwords(strtolower($subrow['cust_firstname']));
        $account_number = "C" . $cust_id . strtoupper(substr($subrow['cust_lastname'], 0, 1)) . strtoupper(substr($subrow['cust_firstname'], 0, 1));
        return $customer_name . ' (' . $account_number . ') - Balance: ' . number_format($subrow['cust_balance'], 2, '.', ',');
    }
}

function get_customer_balance_number($id) {
    $dbconn = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD) or die('Could not connect: ' . mysql_error());
    mysql_select_db(DB_DATABASE) or die('Could not select database');
    $subquery = "SELECT current_balance FROM tbl_balances WHERE account_id=".$id;
    $subrecordset = mysql_query($subquery) or die('Query failed: ' . mysql_error());
    while ($subrow = mysql_fetch_array($subrecordset, MYSQL_ASSOC)) {
        return $subrow['current_balance'];
    }
}
function get_employee_balance($id) {
    $dbconn = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD) or die('Could not connect: ' . mysql_error());
    mysql_select_db(DB_DATABASE) or die('Could not select database');
    $subquery = "SELECT emp_id, emp_lastname,emp_firstname,emp_middlename,
                        (SELECT current_balance FROM tbl_balances WHERE tbl_balances.account_id=emp_id) AS emp_balance
                FROM tbl_employees WHERE emp_id=".$id;
    $subrecordset = mysql_query($subquery) or die('Query failed: ' . mysql_error());
    while ($subrow = mysql_fetch_array($subrecordset, MYSQL_ASSOC)) {
        $employee_name = ucwords(strtolower($subrow['emp_lastname'])).", ".ucwords(strtolower($subrow['emp_firstname']));
        $emp_id = $subrow['emp_id'];
        $account_number = "E" . $emp_id . strtoupper(substr($subrow['emp_lastname'], 0, 1)) . strtoupper(substr($subrow['emp_firstname'], 0, 1));
        return $employee_name . ' (' . $account_number . ') - Balance: ' . number_format($subrow['emp_balance'], 2, '.', ',');
    }
}

function get_vendor_balance($id) {
    $dbconn = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD) or die('Could not connect: ' . mysql_error());
    mysql_select_db(DB_DATABASE) or die('Could not select database');
    $subquery = "SELECT ven_id,ven_lastname,ven_firstname,ven_companyname,
                        (SELECT current_balance FROM tbl_balances WHERE tbl_balances.account_id=ven_id) AS ven_balance
                FROM tbl_vendor WHERE ven_id=".$id;
    $subrecordset = mysql_query($subquery) or die('Query failed: ' . mysql_error());
    while ($subrow = mysql_fetch_array($subrecordset, MYSQL_ASSOC)) {
        $ven_id = $subrow['ven_id'];
        $vendor_name = $subrow['ven_companyname'];
        $lastname = $subrow['ven_lastname'];
        $firstname = $subrow['ven_firstname'];
        $account_number = $ven_no = "V" . $ven_id . strtoupper(substr($lastname, 0, 1)) . strtoupper(substr($firstname, 0, 1));
        return $vendor_name . ' (' . $account_number . ') - Balance: ' . number_format($subrow['ven_balance'], 2, '.', ',');
    }
}

function textbox($id, $class, $size, $value, $type, $input_type, $min, $max) {
    $param = '<input name="' . $id . '" class="' . $class . '" id="' . $id . '" size="' . $size . '" type="' . $input_type . '"';
    if ($type == "RW") {
        $param = $param;
    } elseif ($type == "R") {
        $param = $param . '" disabled readonly="readonly"';
    }
    if ($input_type == "text") {
        $param = $param . ' min="' . $min . '" maxlength="' . $max . '"';
	}
    elseif ($input_type == "password") {
        $param = $param . ' min="' . $min . '" maxlength="' . $max . '"';		
    } 
	elseif ($input_type == "number") {
        $param = $param . ' min="' . $min . '" max="' . $max . '"';
    } 
	elseif ($input_type == "date") {
        $param = '<input readonly="readonly" name="' . $id . '" id="' . $id . '" class="field_input" size="' . $size . '"';
    }    
    echo $param . ' value="' . $value . '" />';
}

function labelbox($class, $value) {
    echo '<span class="'.$class.'">'.$value.'</span>';
}
function getstock2($id, $class, $type, $selected) {
    //Get Products
    echo '<select class="'.$class.'" id="'.$id.'" name="'.$id.'" size="21">';
    $query = "SELECT product_id, branchid,
                  (SELECT branch_name FROM tbl_branches WHERE tbl_products_spareparts.branchid=tbl_branches.branch_id) AS branch_name,
                  (SELECT productcode_code FROM tbl_productcode WHERE tbl_productcode.productcode_id=tbl_products_spareparts.product_codeid) AS product_code,
                  (SELECT productcode_name FROM tbl_productcode WHERE tbl_productcode.productcode_id=tbl_products_spareparts.product_codeid) AS productcode_name,
                  (SELECT category_name FROM tbl_category_inventory_parent WHERE tbl_category_inventory_parent.category_id=
                        (SELECT category_parentid FROM tbl_category_inventory WHERE tbl_category_inventory.category_id=
                        (SELECT productcode_categoryid FROM tbl_productcode WHERE productcode_id=tbl_products_spareparts.product_codeid))) AS product_category       
                  FROM tbl_products_spareparts  ORDER BY product_id";
    $products_listing  = mysql_query($query) or die('Query failed: ' . mysql_error());
    $stocklisting = array();
    while ($productslst = mysql_fetch_array($products_listing, MYSQL_ASSOC)) {  
        if($productslst['product_category']=="Spare Parts") {
                  //$branch_id = $productslst['branchid']; 
                  $product_id_name    = 'SN'.number_pad($productslst['product_id'], 6);
                  $product_id         = $productslst['product_id'];   
                  $product_name       = $product_id_name.' - '.$productslst['productcode_name'].' ('.$productslst['product_code'].')';
                  echo '<option class="'.$class.'" value="'.$product_id.'|'.$product_id_name.' - '.$productslst['product_code'].'">'.$product_name.'</option>';
        }
    }        
    echo '</select>';
}
function getcustomer($id, $class, $array, $type, $selected) {
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="21">';
    foreach ($array as $value) {
        $value = explode("|", $value);
        if ($value[0] == "+") {
            echo "<optgroup label='" . $value[1] . "'>";
        } else if ($value[0] == "-") {
            echo "</optgroup>";
        } else if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '|' . $value[1] . '|'. $value[2]. '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '|' . $value[1] . '|'. $value[2]. '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}

function getsalesagent($id, $class, $array, $type, $selected) {
	//Get Employee Listing
	$query = "SELECT tbl_salesagent.emp_id,tbl_salesagent.emp_lastname,tbl_salesagent.emp_firstname
			  FROM tbl_salesagent WHERE emp_current_status='Active' ORDER BY tbl_salesagent.emp_lastname,tbl_salesagent.emp_firstname";
	$employee_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
	$salesagentlisting = array();
	while ($emplst = mysql_fetch_array($employee_listing, MYSQL_ASSOC)) {    
		  $employee_name = ucfirst(strtolower($emplst['emp_lastname'])).", ".ucfirst(strtolower($emplst['emp_firstname']));	
		  $salesagentlisting[] = $emplst['emp_id']."|".$employee_name;
	}	
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="21">';
	echo '<option class="' . $class . '" value="|" ></option>';

    foreach ($salesagentlisting as $value) {
        $value = explode("|", $value);
        if ($value[0] == "+") {
            echo "<optgroup label='" . $value[1] . "'>";
        } else if ($value[0] == "-") {
            echo "</optgroup>";
        } else if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '|' . $value[1] . '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '|' . $value[1] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}

function partsman($id, $class, $array, $type, $selected) {	
    $query = "SELECT *,
				(SELECT emp_lastname 
				FROM tbl_employees WHERE tbl_employees.emp_id=tbl_mechanics.mechanic_id) 
				AS emp_lastname,
				(SELECT emp_firstname 
				FROM tbl_employees WHERE tbl_employees.emp_id=tbl_mechanics.mechanic_id) 
				AS emp_firstname				
				FROM tbl_mechanics ORDER BY emp_lastname";   		
	$employee_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
	$salesagentlisting = array();
	while ($emplst = mysql_fetch_array($employee_listing, MYSQL_ASSOC)) {    
		  $employee_name = ucfirst(strtolower($emplst['emp_lastname'])).", ".ucfirst(strtolower($emplst['emp_firstname']));
		  $salesagentlisting[] = $emplst['mechanic_id']."|".$employee_name;
	}	
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="21">';
	echo '<option class="' . $class . '" value="|" ></option>';	
    foreach ($salesagentlisting as $value) {
        $value = explode("|", $value);
        if ($value[0] == "+") {
            echo "<optgroup label='" . $value[1] . "'>";
        } else if ($value[0] == "-") {
            echo "</optgroup>";
        } else if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '|' . $value[1] . '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '|' . $value[1] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}

function dropdown($id, $class, $array, $type, $selected) {
	if($type=="R") { echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="1" disabled >'; }
	else { echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="1">'; }
    if ($selected[0] == "" OR $selected[0] == "undefined") {
        echo '<option class="' . $class . '" value="" selected></option>';
    }
    foreach ($array as $value) {
        $value = explode(",", $value);
        if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}

function dropdownEditPayment($id, $class, $array, $type, $selected) {
	if($type=="R") { $html =  '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="1" disabled >'; }
	else { $html =  '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="1">'; }
    if ($selected[0] == "" OR $selected[0] == "undefined") {
        $html .= '<option class="' . $class . '" value="" selected></option>';
    }
    foreach ($array as $value) {
        $value = explode(",", $value);
        if ($value[0] == $selected[0]) {
            $html .= '<option class="' . $class . '" value="' . $value[0] . '" selected>' . $value[1] . '</option>';
        } else {
            $html .= '<option class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
        }
    }
    $html .= '</select>';
    return $html;
}

function dropdown_collection_account($id, $class, $array, $type, $selected) {
	if($type=="R") { echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="1" disabled >'; }
	else { echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="1">'; }
    if ($selected[0] == "" OR $selected[0] == "undefined") {
        echo '<option class="' . $class . '" value="" selected></option>';
    }
    foreach ($array as $value) {
        $value = explode(",", $value);
        if ($value[0] == $selected) {
            echo '<option class="' . $class . '" value="' . $value[0] . '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}
function dropdown2($id, $class, $array, $type, $selected) {
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="1">';
    if ($selected[0] == "" OR $selected[0] == "undefined") {
        echo '<option class="' . $class . '" value="" selected></option>';
    }
    foreach ($array as $value) {
        $value = explode("|", $value);
        if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}
function dropdown3($id, $class, $array, $type, $selected) {
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="1">';
    foreach ($array as $value) {
        $value = explode(",", $value);
        if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}
function listbox_chartofaccounts($id, $class, $array, $type, $selected) {
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="1">';
    foreach ($array as $value) {
        $value = explode(",", $value);
        if ($value[0] == "+") {
            echo "<optgroup label='" . $value[1] . "'>";
        } else if ($value[0] == "-") {
            echo "</optgroup>";
        } else if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}

function branches_listbox($id, $class, $array, $type, $selected) {
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="8">';
    foreach ($array as $value) {		
        $value = explode(",", $value);
		$current = (int)$value[0];
		$selected = (int)$selected;		
		if($selected==$current) {
			echo '<option class="' . $class . '" value="' . $value[0] . '" selected>' . $value[1] . '</option>';	
		} else {
			echo '<option class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
		}
    }
    echo '</select>';
}

function listbox($id, $class, $array, $type, $selected) {
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="8">';
    echo '<option class="' . $class . '" value="All" selected>Select All</option>';
    foreach ($array as $value) {
        $value = explode(",", $value);
        if ($value[0] == "+") {
            echo "<optgroup label='" . $value[1] . "'>";
        } else if ($value[0] == "-") {
            echo "</optgroup>";
        } else if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}
function listbox2($id, $class, $array, $type, $selected) {
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="8">';
    foreach ($array as $value) {
        $value = explode(",", $value);
        if ($value[0] == "+") {
            echo "<optgroup label='" . $value[1] . "'>";
        } else if ($value[0] == "-") {
            echo "</optgroup>";
        } else if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}
function employeelist_dropdown($id, $class, $array, $type, $selected) {
	if($type=="R") {
		echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" disabled>';
	} else {
		echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" >';	
	}
    if ($selected == "") {
        echo '<option class="' . $class . '" value="" selected></option>';
    } else {
        echo '<option class="' . $class . '" value="" ></option>';
	}
    foreach ($array as $value) {
        $value = explode("|", $value);
        if ($value[0] == $selected) {
            echo '<option class="' . $class . '" value="' . $value[0] . '" selected>' .$value[1]. '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}
function employeelist_dropdown_multiple ($id, $class, $array, $type, $selected) {
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '"  multiple="multiple" size="8">';
    if ($selected[0] == "") {
        echo '<option class="' . $class . '" value="" selected></option>';
    }
    foreach ($array as $value) {
        $value = explode("|", $value);
        if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}
function employeelist_dropdown_multiple1 ($id, $class, $array, $type, $selected) {
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '"  multiple="multiple" size="8">';
    foreach ($array as $value) {
        $value = explode("|", $value);
        if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}
function customerlist_dropdown($id, $class, $array, $type, $selected) {
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '">';
    if ($selected[0] == "") {
        echo '<option class="' . $class . '" value="" selected></option>';
    }
    foreach ($array as $value) {
        $value = explode("|", $value);
        if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}

function supplierlist_dropdown($id, $class, $array, $type, $selected) {
	if($type=="R") { echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" disabled >'; }
	else { echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '">'; }
    if ($selected[0] == "") {
        echo '<option class="' . $class . '" value="" selected></option>';
    }
    foreach ($array as $value) {
        $value = explode("|", $value);
        if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}

function dropdown_salarygrade($id, $class, $array, $type, $selected) {
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '">';
    if ($selected[0] == "") {
        echo '<option class="' . $class . '" value="" selected></option>';
    }
    foreach ($array as $value) {
        $value = explode(",", $value);
        if ($value[0] == $selected[0]) {
            echo '<option sgrate="' . $value[2] . '" class="' . $class . '" value="' . $value[0] . '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option sgrate="' . $value[2] . '" class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}

function dropdown_deductiontable($id, $class, $array, $type, $selected) {
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '">';
    if ($selected[0] == "") {
        echo '<option class="' . $class . '" value="" selected></option>';
    }
    foreach ($array as $value) {
        $value = explode(",", $value);
        if ($value[0] == $selected[0]) {
            echo '<option description="' . $value[2] . '" class="' . $class . '" value="' . $value[0] . '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option description="' . $value[2] . '" class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}

function dropdown_incentivetable($id, $class, $array, $type, $selected) {
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '">';
    if ($selected[0] == "") {
        echo '<option class="' . $class . '" value="" selected></option>';
    }
    foreach ($array as $value) {
        $value = explode(",", $value);
        if ($value[0] == $selected[0]) {
            echo '<option description="' . $value[2] . '" class="' . $class . '" value="' . $value[0] . '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option description="' . $value[2] . '" class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}

function dropdown_single($id, $class, $array, $type, $selected) {
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '">';
    if ($selected[0] == "") {
        echo '<option class="' . $class . '" value="" selected></option>';
    }
    foreach ($array as $value) {
        $value = explode(",", $value);
        if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '" selected>' . $value[0] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '">' . $value[0] . '</option>';
        }
    }
    echo '</select>';
}
function get_ledgeracounttitle($account_id){    
	if($account_id=="") {
		return "<font color=red>MANUAL</font>";
	} else if ($account_id==0) {
		return "<font color=red>MANUAL</font>";
	} else {
		$query = "SELECT account_name,account_number,
						(SELECT account_number FROM tbl_chartaccounts_parent WHERE account_id=tbl_chartaccounts.account_parentid) AS account_parent_number
						FROM tbl_chartaccounts WHERE account_id=".$account_id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			return number_pad($row['account_parent_number'],4).'-'.number_pad($row['account_number'],4).' - '.$row['account_name'];
		}   
	} 
}
function get_ledgeraccountname($account_id){  
	if($account_id=="") {
		return "<font color=red>MANUAL</font>";
	} else if ($account_id==0) {
		return "<font color=red>MANUAL</font>";
	} else {
		$query = "SELECT account_code,account_name FROM tbl_chartaccounts WHERE account_id=".$account_id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			return $row['account_code'].' - '.$row['account_name'];
		}   
	} 
}
function get_ledgeraccounttitle($account_id){  
	if($account_id=="") {
		return "<font color=red>MANUAL</font>";
	} else if ($account_id==0) {
		return "<font color=red>MANUAL</font>";
	} else  {
		$query = "SELECT account_code,account_name FROM tbl_chartaccounts WHERE account_id=".$account_id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			return $row['account_name'];
		}   
	} 
}
function get_ledgeraccountcode($account_id){  
	if($account_id=="") {
		return "<font color=red>MANUAL</font>";
	} else if ($account_id==0) {
		return "<font color=red>MANUAL</font>";
	} else  {
		$query = "SELECT account_code,account_name FROM tbl_chartaccounts WHERE account_id=".$account_id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			return $row['account_code'];
		}   
	} 
}
function showbutton_datagrid($emp_id,$module,$level,$id,$class,$buttontext,$value1,$value2,$message,$actionmode) {  
	$query = "SELECT $module FROM user_accessrights WHERE emp_id = '$emp_id'";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());	
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $granted_level = $row[$module];}
	if($granted_level>=$level) { 
		return "<a id='".$id."' class='".$class."' href='' value='".$value1."' value2='".$value2."' message='".$message."' actionmode='".$actionmode."' >".$buttontext."</a>";
	} else { 
		return "<span class='".$class."_disabled'>".$buttontext."</span>";	
	}	
}
function showbutton_voucher($emp_id,$module,$level,$id,$class,$buttontext,$value1,$value2,$message,$actionmode,$vouchertype) {  
	$query = "SELECT ".$module." FROM user_accessrights WHERE emp_id = '$emp_id';";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());	
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $granted_level = $row[$module];}
	if($granted_level>=$level) { 
		return "<a id='".$id."' class='".$class."' href='' vouchertype='".$vouchertype."' value='".$value1."' value2='".$value2."' message='".$message."' actionmode='".$actionmode."' >".$buttontext."</a>";
	} else { 
		return "<span class='".$class."_disabled'>".$buttontext."</span>";	
	}
}
function get_vouchertotalamount($id){    
    $query = "SELECT voucher_amount,voucher_ledgeraction FROM tbl_vouchersub WHERE voucher_parentid=".$id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    $debit = 0;
	$credit = 0;
	while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { 
		if($row['voucher_ledgeraction']=="C") {
				$credit	= $credit + $row['voucher_amount'];
		} else {
				$debit	= $debit + $row['voucher_amount'];
		}
	}   
	if($credit <> $debit) { return ""; } else { return number_format($credit,2,'.',','); }
}
function get_disbursementtotalamount($id){    
    $query = "SELECT disbursement_amount,disbursement_ledgeraction FROM tbl_disbursementsub WHERE disbursement_parentid=".$id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    $debit = 0;
	$credit = 0;
	while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { 
		if($row['voucher_ledgeraction']=="C") {
				$credit	= $credit + $row['voucher_amount'];
		} else {
				$debit	= $debit + $row['voucher_amount'];
		}
	}   
	if($credit <> $debit) { return ""; } else { return number_format($credit,2,'.',','); }
}
function validate_voucher_amount($id){    
    $query = "SELECT voucher_amount,voucher_ledgeraction FROM tbl_vouchersub WHERE voucher_parentid=".$id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    $debit = 0;
	$credit = 0;
	while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { 
		if($row['voucher_ledgeraction']=="C") {
				$credit	= $credit + $row['voucher_amount'];
		} else {
				$debit	= $debit + $row['voucher_amount'];
		}
	}   
	if ($credit <> $debit) { return "F"; } else { return "T"; } 
}

function get_pettycashamount($id){    
    $query = "SELECT pettycash_amount FROM tbl_pettycashsub WHERE pettycash_parentid=".$id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    $amount = 0;
	while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $amount	= $amount + $row['pettycash_amount'];}   
	return $amount;
}
function get_pettycashpercent($id){    
    $query = "SELECT branch_pettycashpercent FROM tbl_branches WHERE branch_id=".$id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    $amount = 0;
	while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { 
		$amount	= $row['branch_pettycashpercent'];
		$amount = $amount/100;
	}   
	return $amount;
}
function get_pettycashallocationamount($id){    
    $query = "SELECT branch_pettycashallocation FROM tbl_branches WHERE branch_id=".$id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    $amount = 0;
	while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { 
		$amount	= $row['branch_pettycashallocation'];
		$amount = $amount;
	}   
	return $amount;
}
function get_pettycashbranch($id){    
    $query = "SELECT voucher_branch FROM tbl_pettycash WHERE voucher_id=".$id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
	while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $branch	= $row['voucher_branch'];}   
	return $branch;
}

function textbox_insurance($insurance_id) {  
    $query = "SELECT * FROM tbl_insurance WHERE insurance_id=$insurance_id LIMIT 1";
    if($insurance_id=="") { $insurance_name= ""; } 
    else {
    	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    	while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {  $insurance_name = $row['insurance_name']; }      
	}
	echo '<input type="text" name="" id="" class="field_readonly_block" size="70" value="'.$insurance_name.'" readonly />';
}
function textbox_districts($district_id) {  
    $query = "SELECT * FROM tbl_districts WHERE district_id=$district_id LIMIT 1";
    if($district_id=="") { $insurance_name= ""; } 
    else {
    	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    	while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {  $district_name = $row['district_name']; }      
	}
	echo '<input type="text" name="" id="" class="field_readonly_block" size="70" value="'.$district_name.'" readonly />';
}

function dropdown_insurance($id,$class,$selected) {  
    echo '<select class="'.$class. '" id="'.$id.'" name="'.$id.'" size="1">';    
    echo '<option class="'.$class.'" value=""></option>';    
    $query = "SELECT * FROM tbl_insurance ORDER BY insurance_name";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {       
              if ($row['insurance_id'] == $selected) {
                echo '<option class="' . $class . '" value="'.$row['insurance_id'].'" selected>' . $row['insurance_name'] . '</option>';
              } else {
                echo '<option class="' . $class . '" value="'.$row['insurance_id'].'">'. $row['insurance_name']. '</option>';
              }
    }
}
function dropdown_district($id,$class,$selected) {  
    echo '<select class="'.$class. '" id="'.$id.'" name="'.$id.'" size="1">';    
    echo '<option class="'.$class.'" value=""></option>';    
    $query = "SELECT * FROM tbl_districts ORDER BY district_name";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {       
              if ($row['district_id'] == $selected) {
                echo '<option class="' . $class . '" value="'.$row['district_id'].'" selected>' . $row['district_name'] . '</option>';
              } else {
                echo '<option class="' . $class . '" value="'.$row['district_id'].'">'. $row['district_name']. '</option>';
              }
    }
}
function dropdown_vendor($id,$class,$selected) {  
    echo '<select class="'.$class. '" id="'.$id.'" name="'.$id.'" size="1">';    
    echo '<option class="'.$class.'" value=""></option>';    
    $query = "SELECT ven_id,ven_companyname FROM tbl_vendor ORDER BY ven_companyname";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {       
  	          $ven_id 			= $row['ven_id'];
			  $ven_companyname  = $row['ven_companyname'];
              if ($row['ven_id'] == $selected) {
                echo '<option class="'.$class.'" value='.$row['ven_id'].' selected>'.$ven_companyname.'</option>';
              } else {
                echo '<option class="'.$class.'" value='.$row['ven_id'].'>' . $ven_companyname. '</option>';
              }
    }
}

function validate_disbursement($id){    
    $query = "SELECT * FROM tbl_disbursements WHERE disbursement_id=".$id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());   
	$string = "TRUE";
	while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { 	
		$disbursement_amount = $row['disbursement_amount'];
		if($row['disbursement_note1']=="") {
				$string	= "Check Number is required.";
		} 			
		else if($row['disbursement_note3']=="") {
				$string	= "Check Date is required.";
		} 		
	} 
	if($string=="TRUE") {
		$query = "SELECT * FROM tbl_disbusermentsub WHERE disbursement_ledgeraction='C' AND disbursement_parentid=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());   
		$amount = 0;
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { 		
			$amount = $amount + $row['disbursement_amount'];
		} 	
		if($disbursement_amount <> $amount) {
			$string	= "Total <b>Credit Entries</b> must be equal to <b>".number_format($disbursement_amount, 2, '.', ',')."</b>.";
		}
	}
	return $string;	
}

function get_ledgerforbankaccounts($id, $class, $type, $selected) {
    //Get Products
    echo '<select onchange="getval(1);" class="'.$class.'" id="'.$id.'" name="'.$id.'" size="1">';
    echo '<option class="'.$class.'" value=""></option>';	
    $query = "SELECT * FROM tbl_chartaccounts WHERE LEFT(account_name,3)='CIB' ORDER BY account_name";
    $recordset  = mysql_query($query) or die('Query failed: ' . mysql_error());
    $category = '';
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {  
            $account_code   = $row['account_code'];   
            $account_id     = $row['account_id'];   
			$account_name   = $row['account_name'];                  
            echo '<option class="'.$class.'" value="'.$account_id.'">'.$account_name.'</option>';
    }        
    echo '</select>';
}
function get_ledgerforbankaccounts2($id, $class, $type, $selected) {
    //Get Products
    echo '<select onchange="getval2(this.value);" class="'.$class.'" id="'.$id.'" name="'.$id.'" size="1">';
    echo '<option class="'.$class.'" value=""></option>';	
    $query = "SELECT * FROM tbl_chartaccounts WHERE LEFT(account_name,3)='CIB' ORDER BY account_name";
    $recordset  = mysql_query($query) or die('Query failed: ' . mysql_error());
    $category = '';
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {  
            $account_code   = $row['account_code'];   
            $account_id     = $row['account_id'];   
			$account_name   = $row['account_name'];                  
            echo '<option class="'.$class.'" value="'.$account_id.'">'.$account_name.'</option>';
    }        
    echo '</select>';
}
function get_salesagent_tagname($id){    
    $query = "SELECT emp_lastname,emp_firstname FROM tbl_salesagent WHERE emp_id=".$id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
	    $emp_no = "S".$id.strtoupper(substr($emplst['emp_lastname'],0,1)).strtoupper(substr($emplst['emp_firstname'],0,1));
	    $salesagent_name  = ucwords(strtolower($row['emp_lastname'])).", ".ucwords(strtolower($row['emp_firstname'])).' ('.$emp_no.')'; 
        return $salesagent_name;
    }   
}
function get_salesagentbalance($account_id){    
    $query = "SELECT current_balance FROM tbl_salesagentbalances WHERE account_id=".$account_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    $count = 0;
	while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
		$current_balance 	= $row['current_balance'];
		$count 				= $count + 1;
    }   
	if($count == 0) {
		$current_balance = 0;
        $sql = "INSERT INTO tbl_salesagentbalances (account_id,current_balance) VALUES ('$account_id','$current_balance')";
        mysql_query($sql) or die('<div class="message">Error! ' . mysql_error() . '</div>');   		
	}
	if($current_balance < 0 ) {
		return "<font color='red'>".number_format($current_balance,2,'.',',')."</font>";
	} else {
		return "<font color='blue'>".number_format($current_balance,2,'.',',')."</font>";
	}
}
function get_vouchertotalamount_int($id){    
    $query = "SELECT voucher_amount,voucher_ledgeraction FROM tbl_vouchersub WHERE voucher_parentid=".$id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    $debit = 0;
	$credit = 0;
	while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { 
		if($row['voucher_ledgeraction']=="C") {
				$credit	= $credit + $row['voucher_amount'];
		} else {
				$debit	= $debit + $row['voucher_amount'];
		}
	}   
	if($credit <> $debit) { return 0; } else { return $credit; }
}

function getcustomer_abcbutton2($id, $class, $type, $selected, $page, $dialog) {
	//Get Customer Listing
	$dbconn = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_DATABASE) or die('Could not select database');	
	if($page=="0") {$page="'0','1','2','3','4','5','6','7','8','9'";} else { $page = "'".$page."'"; }		
	$query = "SELECT *
				  FROM tbl_customers WHERE cust_status='A' AND SUBSTR(cust_lastname,1,1) IN(".$page.") ORDER BY cust_lastname,cust_firstname";
	$customer_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
	$customerlisting = array();
	while ($cuslst = mysql_fetch_array($customer_listing, MYSQL_ASSOC)) {    
		  $customer_name = ucwords(strtolower($cuslst['cust_lastname'])).', '.ucwords(strtolower($cuslst['cust_firstname']));
          $address_street = $cuslst['cust_address_street'];
          $address_town = $cuslst['cust_address_town'];
          $address_city = $cuslst['cust_address_city'];
          $address_zipcode = $cuslst['cust_address_zipcode'];
          $address_country = $cuslst['cust_address_country'];			
          $address_street2 = $cuslst['cust_address_street2'];
          $address_town2 = $cuslst['cust_address_town2'];
          $address_city2 = $cuslst['cust_address_city2'];
          $address_zipcode2 = $cuslst['cust_address_zipcode2'];
          $address_country2 = $cuslst['cust_address_country2'];			  		  
		  $cust_phone1 = $cuslst['cust_phone1'];	
		  $address1 = $address_street.' '.$address_town.' '.$address_city.' '.$address_zipcode.' '.$address_country;
		  $address2 = $address_street.' '.$address_town.' '.$address_city.' '.$address_zipcode.' '.$address_country;
		  if(str_replace(" ","",$address2)<>"") { $address= $address2; } else { $address= $address1; }
		  $customerlisting[] = $cuslst['cust_id']."|".$customer_name.'|'.$address.'|'.$cust_phone1;
	}
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="15">';
    foreach ($customerlisting as $value) {
        $value = explode("|", $value);
        if ($value[0] == "+") {
            echo "<optgroup label='" . $value[1] . "'>";
        } else if ($value[0] == "-") {
            echo "</optgroup>";
        } else if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '|' . $value[1] . '|'. $value[2].'|'. $value[3].'" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '|' . $value[1] . '|'. $value[2].'|'. $value[3]. '">' . $value[1].'</option>';
        }
    }
    echo '</select>';
	echo '<a id="'.$dialog.'_a" class="abc_button2" href="">A</a>'; 
	echo '<a id="'.$dialog.'_b" class="abc_button2" href="">B</a>';  
	echo '<a id="'.$dialog.'_c" class="abc_button2" href="">C</a>';  
	echo '<a id="'.$dialog.'_d" class="abc_button2" href="">D</a>'; 
	echo '<a id="'.$dialog.'_e" class="abc_button2" href="">E</a>'; 
	echo '<a id="'.$dialog.'_f" class="abc_button2" href="">F</a>'; 
	echo '<a id="'.$dialog.'_g" class="abc_button2" href="">G</a>'; 
	echo '<a id="'.$dialog.'_h" class="abc_button2" href="">H</a>';  
	echo '<a id="'.$dialog.'_i" class="abc_button2" href="">I</a>';  
	echo '<a id="'.$dialog.'_j" class="abc_button2" href="">J</a>'; 
	echo '<a id="'.$dialog.'_k" class="abc_button2" href="">K</a>'; 
	echo '<a id="'.$dialog.'_l" class="abc_button2" href="">L</a>'; 
    echo '<a id="'.$dialog.'_m" class="abc_button2" href="">M</a>';  
	echo '<a id="'.$dialog.'_n" class="abc_button2" href="">N</a>';  
	echo '<a id="'.$dialog.'_o" class="abc_button2" href="">O</a>';  
	echo '<a id="'.$dialog.'_p" class="abc_button2" href="">P</a>'; 
	echo '<a id="'.$dialog.'_q" class="abc_button2" href="">Q</a>'; 
	echo '<a id="'.$dialog.'_r" class="abc_button2" href="">R</a>'; 
	echo '<a id="'.$dialog.'_s" class="abc_button2" href="">S</a>'; 
	echo '<a id="'.$dialog.'_t" class="abc_button2" href="">T</a>';  
	echo '<a id="'.$dialog.'_u" class="abc_button2" href="">U</a>';  
	echo '<a id="'.$dialog.'_v" class="abc_button2" href="">V</a>'; 
	echo '<a id="'.$dialog.'_w" class="abc_button2" href="">W</a>'; 
	echo '<a id="'.$dialog.'_x" class="abc_button2" href="">X</a>'; 										
	echo '<a id="'.$dialog.'_y" class="abc_button2" href="">Y</a>'; 
	echo '<a id="'.$dialog.'_z" class="abc_button2" href="">Z</a>'; 	
	echo '<a id="'.$dialog.'_0" class="abc_button2" href="">#</a>'; 		
	echo '<script type="text/javascript">';
	$x=array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","0");
	foreach ($x as $value)
	  {
		echo '$(function() {';	
		echo '$("#'.$dialog.'_'.$value.'").click(function() {';
		echo '$("#'.$dialog.'").load("sales/customerlisting.php?filter='.strtoupper($value).'&dialog='.$dialog.'");';
		echo 'event.preventDefault();';
		echo '});';		
		echo '});';  	  
	  }     	
	echo '</script>';
}

function get_joborderamount($job_id,$category){    
    $query = "SELECT * FROM tbl_joborder_sub WHERE job_sub_parentid=$job_id AND job_sub_category='$category'";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
    $amount = 0;
	while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $amount = $amount +  $row['job_sub_amount'];}   
	return $amount;
}

function get_payeeabc($id, $class, $type, $selected, $page, $dialog) {
	//Get Customer Listing
	$dbconn = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_DATABASE) or die('Could not select database');	
	if($page=="0") {$page="'0','1','2','3','4','5','6','7','8','9'";} else { $page = "'".$page."'"; }		
	$query = "SELECT *
				  FROM tbl_vendor WHERE ven_status='A' AND SUBSTR(ven_companyname,1,1) IN(".$page.") ORDER BY ven_companyname";
	$customer_listing = mysql_query($query) or die('Query failed: ' . mysql_error());
	$venlisting = array();
	while ($cuslst = mysql_fetch_array($customer_listing, MYSQL_ASSOC)) {    
          $ven_id 			= $cuslst['ven_id'];
          $ven_companyname 	= ucwords(strtolower($cuslst['ven_companyname']));
		  $venlisting[] = $cuslst['ven_id']."|".$ven_companyname;
	}
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="15">';
    foreach ($venlisting as $value) {
        $value = explode("|", $value);
        if ($value[0] == "+") {
            echo "<optgroup label='" . $value[1] . "'>";
        } else if ($value[0] == "-") {
            echo "</optgroup>";
        } else if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '|' . $value[1].'" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '|' . $value[1]. '">' . $value[1].'</option>';
        }
    }
    echo '</select>';
	echo '<a id="'.$dialog.'_a" class="abc_button2" href="">A</a>'; 
	echo '<a id="'.$dialog.'_b" class="abc_button2" href="">B</a>';  
	echo '<a id="'.$dialog.'_c" class="abc_button2" href="">C</a>';  
	echo '<a id="'.$dialog.'_d" class="abc_button2" href="">D</a>'; 
	echo '<a id="'.$dialog.'_e" class="abc_button2" href="">E</a>'; 
	echo '<a id="'.$dialog.'_f" class="abc_button2" href="">F</a>'; 
	echo '<a id="'.$dialog.'_g" class="abc_button2" href="">G</a>'; 
	echo '<a id="'.$dialog.'_h" class="abc_button2" href="">H</a>';  
	echo '<a id="'.$dialog.'_i" class="abc_button2" href="">I</a>';  
	echo '<a id="'.$dialog.'_j" class="abc_button2" href="">J</a>'; 
	echo '<a id="'.$dialog.'_k" class="abc_button2" href="">K</a>'; 
	echo '<a id="'.$dialog.'_l" class="abc_button2" href="">L</a>'; 
    echo '<a id="'.$dialog.'_m" class="abc_button2" href="">M</a>';  
	echo '<a id="'.$dialog.'_n" class="abc_button2" href="">N</a>';  
	echo '<a id="'.$dialog.'_o" class="abc_button2" href="">O</a>';  
	echo '<a id="'.$dialog.'_p" class="abc_button2" href="">P</a>'; 
	echo '<a id="'.$dialog.'_q" class="abc_button2" href="">Q</a>'; 
	echo '<a id="'.$dialog.'_r" class="abc_button2" href="">R</a>'; 
	echo '<a id="'.$dialog.'_s" class="abc_button2" href="">S</a>'; 
	echo '<a id="'.$dialog.'_t" class="abc_button2" href="">T</a>';  
	echo '<a id="'.$dialog.'_u" class="abc_button2" href="">U</a>';  
	echo '<a id="'.$dialog.'_v" class="abc_button2" href="">V</a>'; 
	echo '<a id="'.$dialog.'_w" class="abc_button2" href="">W</a>'; 
	echo '<a id="'.$dialog.'_x" class="abc_button2" href="">X</a>'; 										
	echo '<a id="'.$dialog.'_y" class="abc_button2" href="">Y</a>'; 
	echo '<a id="'.$dialog.'_z" class="abc_button2" href="">Z</a>'; 	
	echo '<a id="'.$dialog.'_0" class="abc_button2" href="">#</a>'; 		
	echo '<script type="text/javascript">';
	$x=array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","0");
	foreach ($x as $value)
	  {
		echo '$(function() {';	
		echo '$("#'.$dialog.'_'.$value.'").click(function() {';
		echo '$("#'.$dialog.'").load("vouchers/payeelisting.php?filter='.strtoupper($value).'&dialog='.$dialog.'");';
		echo 'event.preventDefault();';
		echo '});';		
		echo '});';  	  
	  }     	
	echo '</script>';
}
function grouplistbox($id, $class, $type, $selected,$category) {
	if($category=="vendor") {
		$group_id[] = "+".","."Category";
		$query = "SELECT * FROM tbl_vendor_category ORDER BY ven_category_name ASC";
		$group = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($grouplst= mysql_fetch_array($group, MYSQL_ASSOC)) {       
			 $group_id[] = 'C'.$grouplst['ven_category_id'].",".$grouplst['ven_category_name'];
		}  
		$group_id[] = "-".","."";	  
	} 
	else if ($category=="employee") {
		$group_id[] = "+".","."Category";
		$query = "SELECT * FROM tbl_branches ORDER BY branch_name ASC";
		$group = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($grouplst= mysql_fetch_array($group, MYSQL_ASSOC)) {       
			 $group_id[] = 'C'.$grouplst['branch_id'].",".$grouplst['branch_name'];
		}  
		$group_id[] = "-".","."";		
	} 
	else if ($category=="salesagent") {
		$group_id[] = "+".","."Category";
		$query = "SELECT * FROM tbl_branches ORDER BY branch_name ASC";
		$group = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($grouplst= mysql_fetch_array($group, MYSQL_ASSOC)) {       
			 $group_id[] = 'C'.$grouplst['branch_id'].",".$grouplst['branch_name'];
		}  
		$group_id[] = "-".","."";		
	}
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="8">';
    //echo '<option class="' . $class . '" value="All" selected>Select All</option>';
    foreach ($group_id as $value) {
        $value = explode(",", $value);
        if ($value[0] == "+") {
            echo "<optgroup label='" . $value[1] . "'>";
        } else if ($value[0] == "-") {
            echo "</optgroup>";
        } else if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}

function get_brand($id) {
	if($id<>"") {
		$query = "SELECT code_name,code_description FROM tbl_brands WHERE code_id=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			$brand = $row['code_name'].' - '.$row['code_description'];
		}  
		return $brand;
	} else {
		return "";
	}
}

function get_product_brand($id) {
	if($id<>"") {
		$query = "SELECT code_name,code_description FROM tbl_brands WHERE code_id=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			$brand = $row['code_name'];
		}  
		return $brand;
	} else {
		return "";
	}
}

function get_product_description($id) {
	if($id<>"") {
		$query = "SELECT code_name,code_description FROM tbl_brands WHERE code_id=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			$brand = $row['code_description'];
		}  
		return $brand;
	} else {
		return "";
	}
}

function get_color($id) {
	if($id=="") {
		$color = "";
	} else {
		$query = "SELECT color_name FROM tbl_colors WHERE color_id=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			$color = $row['color_name'];
		}  
	}
    return $color;
}
function get_productcode($id) {
	if($id<>"") {
		$query = "SELECT productcode_code,productcode_name FROM tbl_productcode WHERE productcode_id=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			$product_code = $row['productcode_code'].' - '.$row['productcode_name'];
		}  
		return $product_code;
	} else {
		return "";
	}
}

function get_productcode_short($id) {
	if($id<>"") {
		$query = "SELECT productcode_code,productcode_name FROM tbl_productcode WHERE productcode_id=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			$product_code = $row['productcode_code'];
		}  
		return $product_code;
	} else {
		return "";
	}
}
function get_productcategoryname($id) {
	$query = "SELECT
                *,
                (SELECT category_name FROM tbl_category_inventory WHERE tbl_productcode.productcode_categoryid=tbl_category_inventory.category_id) AS name_subcategory,
                (SELECT category_name FROM tbl_category_inventory_parent
                WHERE tbl_category_inventory_parent.category_id=(SELECT category_parentid FROM tbl_category_inventory WHERE tbl_category_inventory.category_id=tbl_productcode.productcode_categoryid)) AS name_parent
          FROM tbl_productcode WHERE productcode_id=".$id;	
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $product_category = $row['name_parent'].'&#92;'.$row['name_subcategory'];
    }  
    return $product_category;
}
function get_productcategoryidentifier($id) {
	$query = "SELECT
                *,
                (SELECT category_name FROM tbl_category_inventory WHERE tbl_productcode.productcode_categoryid=tbl_category_inventory.category_id) AS name_subcategory,
                (SELECT category_name FROM tbl_category_inventory_parent
                WHERE tbl_category_inventory_parent.category_id=(SELECT category_parentid FROM tbl_category_inventory WHERE tbl_category_inventory.category_id=tbl_productcode.productcode_categoryid)) AS name_parent
          FROM tbl_productcode WHERE productcode_id=".$id;	
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $product_category = $row['name_parent'];
    }  
    return $product_category;
}
function get_suppliername($id){   
	if($id<>"") {
		$query = "SELECT po_supplierid FROM tbl_purchaseorders_main WHERE po_id=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    	
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			$id = $row['po_supplierid'];
		}  	
		$query = "SELECT ven_lastname, ven_firstname, ven_companyname FROM tbl_vendor WHERE ven_id=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			$vendor_name = ucwords(strtolower($row['ven_companyname']));
			return $vendor_name;
		}   
	} else {
		return "";
	}
}
function get_supplierid($id){   
	if($id<>"") {
		$query = "SELECT po_supplierid FROM tbl_purchaseorders_main WHERE po_id=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    	
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			$id = $row['po_supplierid'];
		}  	
		$query = "SELECT ven_id, ven_lastname, ven_firstname, ven_companyname FROM tbl_vendor WHERE ven_id=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			$ven_id = $row['ven_id'];
			return $ven_id;
		}   
	} else {
		return "";
	}
}

function get_productproperty($id,$property_number){   
	if($id<>"") {
		$query = "SELECT * FROM tbl_productcode WHERE productcode_id=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    	
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			if($property_number==1) 	 { $property = $row['property1']; }
			else if($property_number==2) { $property = $row['property2']; }
			else if($property_number==3) { $property = $row['property3']; }
			else if($property_number==4) { $property = $row['property4']; }
			else if($property_number==5) { $property = $row['property5']; }
			else if($property_number==6) { $property = $row['property6']; }
			else if($property_number==7) { $property = $row['property7']; }
			else if($property_number==8) { $property = $row['property8']; }	
		}	
		return $property;	
	} else {
		return "";
	}	
}

function countstock($id,$selectedbranch) {
	if($selectedbranch==0) {
		$query = "SELECT sum(onhand) AS total_count FROM tbl_spareparts_pricing WHERE productcode_id=$id";	
	} else {
		$query = "SELECT sum(onhand) AS total_count FROM tbl_spareparts_pricing WHERE productcode_id=$id AND branch_id=$selectedbranch";
	}
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
	$total_count = 0;		
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $total_count = $row['total_count'];
    }  
    return $total_count;
}

function stockonhand_bybranch($product_id,$branch_id) {
    $query = "SELECT sum(qty) AS total_count FROM tbl_products_spareparts WHERE product_codeid=$product_id AND branchid=$branch_id";
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
	$total_count = 0;	
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $total_count = $row['total_count'];
    }  
    return $total_count;
}
function stocksold_bybranch($product_id,$branch_id) {
    $query = "SELECT sum(qty) AS total_count FROM tbl_products_spareparts WHERE product_codeid=$product_id AND branchid=$branch_id";
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
	$total_count = 0;	
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $total_count = $row['total_count'];
    }  
    return "";
}
function stockonoder_bybranch($product_id,$branch_id) {
    $query = "SELECT sum(qty) AS total_count FROM tbl_products_spareparts WHERE product_codeid=$product_id AND branchid=$branch_id";
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
	$total_count = 0;	
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $total_count = $row['total_count'];
    }  
    return "";
}


function getbranchlisting($id, $class, $type, $selected) {
	$group_id[] = "+".","."Category";
	$query = "SELECT * FROM tbl_branches ORDER BY branch_name ASC";
	$group = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($grouplst= mysql_fetch_array($group, MYSQL_ASSOC)) {       
		$group_id[] = $grouplst['branch_id'].",".$grouplst['branch_name'];
	}  
	$group_id[] = "-".","."";		
    echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="8">';
    echo '<option class="' . $class . '" value="0" selected>Select All</option>';
    foreach ($group_id as $value) {
        $value = explode(",", $value);
        if ($value[0] == "+") {
            echo "<optgroup label='" . $value[1] . "'>";
        } else if ($value[0] == "-") {
            echo "</optgroup>";
        } else if ($value[0] == $selected[0]) {
            echo '<option class="' . $class . '" value="' . $value[0] . '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}

function dropdown_expanded($id, $class, $array, $type, $selected, $expanded) {
	if($type=="R") { echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="'.$expanded.'" disabled >'; }
	else { echo '<select class="' . $class . '" id="' . $id . '" name="' . $id . '" size="'.$expanded.'">'; }
    if ($selected == "" OR $selected == "undefined") {
        echo '<option class="' . $class . '" value="" selected></option>';
    }
    foreach ($array as $value) {
        $value = explode(",", $value);
        if ($value[0] == $selected) {
            echo '<option class="' . $class . '" value="' . $value[0] . '" selected>' . $value[1] . '</option>';
        } else {
            echo '<option class="' . $class . '" value="' . $value[0] . '">' . $value[1] . '</option>';
        }
    }
    echo '</select>';
}

function inventorycount($id,$category,$branch) {
	if($category=="onorder") {
		$query = "SELECT sum(po_qty) as total_count FROM tbl_purchaseorders_main AS a INNER JOIN tbl_purchaseorders_sub AS b ON a.po_id=b.po_id WHERE po_item_status='W' AND po_product_id=$id AND po_branchid=$branch";
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $total_count = $row['total_count'];}  
		if($total_count==0) { return ""; } else { return $total_count; }				
	}
	else {
		$query = "SELECT sum($category) AS total_count FROM tbl_spareparts_pricing WHERE branch_id=$branch AND id=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $total_count = $row['total_count'];}  
		if($total_count==0) { return ""; } else { return $total_count; }
	}	
}


function inventorycount_productcode($id,$category,$branch) {
	if($category=="onorder") {
		$query = "SELECT sum(po_qty) as total_count FROM tbl_purchaseorders_main AS a INNER JOIN tbl_purchaseorders_sub AS b ON a.po_id=b.po_id WHERE po_item_status='W' AND po_product_id=$id AND po_branchid=$branch";
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $total_count = $row['total_count'];}  
		if($total_count==0) { return ""; } else { return $total_count; }				
	}
	else {
		$query = "SELECT sum($category) AS total_count FROM tbl_spareparts_pricing WHERE branch_id=$branch AND productcode_id=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $total_count = $row['total_count'];}  
		if($total_count==0) { return ""; } else { return $total_count; }
	}	
}

function suminventorycount_spareparts($id,$category) {
	if($category=="onorder") {
		$query = "SELECT sum(po_qty) as total_count FROM tbl_purchaseorders_main AS a INNER JOIN tbl_purchaseorders_sub AS b ON a.po_id=b.po_id WHERE po_item_status='W' AND po_product_id=$id";
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $total_count = $row['total_count'];}  
		if($total_count==0) { return ""; } else { return $total_count; }				
	}
	else {
		$query = "SELECT sum($category) AS total_count FROM tbl_spareparts_pricing WHERE productcode_id=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $total_count = $row['total_count'];}  
		if($total_count==0) { return ""; } else { return $total_count; }
	}	
}
function suminventorycount_nonspareparts($id,$category) {
	if($category=="onorder") {
		$query = "SELECT count(po_product_id) as total_count FROM tbl_purchaseorders_main AS a INNER JOIN tbl_purchaseorders_sub AS b ON a.po_id=b.po_id WHERE po_item_status='W' AND po_product_id=$id";
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $total_count = $row['total_count'];}  
		if($total_count==0) { return ""; } else { return $total_count; }				
	}
	else if ($category=="sold") {
		$query = "SELECT count(product_id) AS total_count FROM tbl_products_nonspareparts WHERE product_status='S' AND product_codeid=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $total_count = $row['total_count'];}  
		if($total_count==0) { return ""; } else { return $total_count; }
	}	
	else if ($category=="onhand") {
		$query = "SELECT count(product_id) AS total_count FROM tbl_products_nonspareparts WHERE product_status<>'S' AND product_codeid=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $total_count = $row['total_count'];}  
		if($total_count==0) { return ""; } else { return $total_count; }
	}	
}
//--------------------------------------------------------------------------
function suminventorycount_nonspareparts_bybranch($id,$category,$branch) {
	if($category=="onorder") {
		$query = "SELECT count(po_product_id) as total_count FROM tbl_purchaseorders_main AS a INNER JOIN tbl_purchaseorders_sub AS b ON a.po_id=b.po_id WHERE po_item_status='W' AND po_product_id=$id AND po_branchid=$branch";
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $total_count = $row['total_count'];}  
		if($total_count==0) { return ""; } else { return $total_count; }				
	}
	else if ($category=="sold") {
		$query = "SELECT count(product_id) AS total_count FROM tbl_products_nonspareparts WHERE product_status='S'  AND branchid=$branch AND product_codeid=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $total_count = $row['total_count'];}  
		if($total_count==0) { return ""; } else { return $total_count; }
	}	
	else if ($category=="onhand") {
		$query = "SELECT count(product_id) AS total_count FROM tbl_products_nonspareparts WHERE product_status<>'S' AND branchid=$branch AND product_codeid=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $total_count = $row['total_count'];}  
		if($total_count==0) { return ""; } else { return $total_count; }
	}	
}

function suminventorycount_spareparts_bybranch($id,$category,$branch) {
	if($category=="onorder") {
		$query = "SELECT sum(po_qty) as total_count,po_branchid FROM tbl_purchaseorders_main AS a INNER JOIN tbl_purchaseorders_sub AS b ON a.po_id=b.po_id WHERE po_item_status='W' AND po_product_id=$id AND po_branchid=$branch";
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $total_count = $row['total_count'];}  
		if($total_count==0) { return ""; } else { return $total_count; }				
	}
	else {
		$query = "SELECT sum($category) AS total_count FROM tbl_spareparts_pricing WHERE branch_id=$branch AND productcode_id=".$id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $total_count = $row['total_count'];}  
		if($total_count==0) { return ""; } else { return $total_count; }
	}	
}


function get_spdetail($id,$output) {
	$query = "SELECT
                *,
                (SELECT category_name FROM tbl_category_inventory WHERE tbl_productcode.productcode_categoryid=tbl_category_inventory.category_id) AS name_subcategory,
                (SELECT category_name FROM tbl_category_inventory_parent
                WHERE tbl_category_inventory_parent.category_id=(SELECT category_parentid FROM tbl_category_inventory WHERE tbl_category_inventory.category_id=tbl_productcode.productcode_categoryid)) AS name_parent
          FROM tbl_productcode WHERE productcode_id=".$id;	
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
		if($output == "printer" ) {
			$product_category = $row['name_parent'].' \ '.$row['name_subcategory'].' \ '.$row['productcode_name'];
		} else {
			$product_category = $row['name_parent'].'&#92;'.$row['name_subcategory'].'&#92;'.$row['productcode_name'];
		}	
    }  
    return $product_category;
}


function get_nonspdetail($id,$output) {
	$query = "SELECT product_id, product_codeid FROM tbl_products_nonspareparts WHERE product_id=$id";
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
		$product_codeid = $row['product_codeid'];	
		$query2 = "SELECT
					*,
						(SELECT category_name FROM tbl_category_inventory WHERE tbl_productcode.productcode_categoryid=tbl_category_inventory.category_id) AS name_subcategory,
						(SELECT category_name FROM tbl_category_inventory_parent
						WHERE tbl_category_inventory_parent.category_id=(SELECT category_parentid FROM tbl_category_inventory WHERE tbl_category_inventory.category_id=tbl_productcode.productcode_categoryid)) AS name_parent
					FROM tbl_productcode WHERE productcode_id=".$product_codeid;	
		$recordset2 = mysql_query($query2) or die('Query failed: ' . mysql_error());
		while ($row2 = mysql_fetch_array($recordset2, MYSQL_ASSOC)) {
			if($output == "printer" ) {
				$product_category = $row2['name_parent'].' \ '.$row2['name_subcategory'].' \ '.$row2['productcode_name'];
			} else {
				$product_category = $row2['name_parent'].'&#92;'.$row2['name_subcategory'].'&#92;'.$row2['productcode_name'];			
			}
		}  	
		$product_category = $product_category;
    }  
    return $product_category;
}

function get_productcodeid($id) {
	$query = "SELECT product_id, product_codeid FROM tbl_products_nonspareparts WHERE product_id=$id LIMIT 1";
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
		$product_codeid = $row['product_codeid'];	
	}  
    return $product_codeid;
}

function get_property($id,$property) {
	$query = "SELECT
					*,
					(SELECT category_name FROM tbl_category_inventory WHERE tbl_productcode.productcode_categoryid=tbl_category_inventory.category_id) AS name_subcategory,
					(SELECT category_name FROM tbl_category_inventory_parent
					WHERE tbl_category_inventory_parent.category_id=(SELECT category_parentid FROM tbl_category_inventory WHERE tbl_category_inventory.category_id=tbl_productcode.productcode_categoryid)) AS name_parent
			  FROM tbl_productcode WHERE productcode_id=$id";
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
		if($property==1) { return $row['property1']; }
		else if($property==2) { return $row['property2']; }
		else if($property==3) { return $row['property3']; }
		else if($property==4) { return $row['property4']; }
		else if($property==5) { return $row['property5']; }
		else if($property==6) { return $row['property6']; }
		else if($property==7) { return $row['property7']; }
		else if($property==8) { return $row['property8']; }
	}
}


function gridbutton($emp_id,$module,$level,$id,$class,$buttontext,$value,$message,$actionmode) {  
	$query = "SELECT $module FROM user_accessrights WHERE emp_id = '$emp_id'";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());	
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $granted_level = $row[$module];}
	if($granted_level>=$level) { 
		return "<a id='".$id."' class='".$class."' href='' value='".$value."' message='".$message."' actionmode='".$actionmode."' >".$buttontext."</a>";
	} else { 
		return "<span class='".$class."_disabled'></span>";	
	}	
}

function stocktransfer_selectbranch() {
    $query = "SELECT * FROM tbl_branches ORDER BY branch_name";
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    echo '<select class="listboxbranches" id="branchselected" name="" size="15">';
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
		echo '<option class="" value="'.$row['branch_id'].'|'.$row['branch_name'].'">'.$row['branch_name'].'</option>';    
    }  
    echo '</select>';
}

function getstock_nonsp($id, $class, $type, $selected) {
    echo '<select class="'.$class.'" id="'.$id.'" name="'.$id.'" size="21">';
    $query = "SELECT * FROM tbl_products_nonspareparts WHERE product_status='A' ORDER BY product_id";
    $products_listing  = mysql_query($query) or die('Query failed: ' . mysql_error());
    $stocklisting = array();
    $product_category = '';
    while ($productslst = mysql_fetch_array($products_listing, MYSQL_ASSOC)) {  
	    $product_id		    = $productslst['product_id'];
		$product_name 		= 'SN'.number_pad($product_id,5);
		$product_category	= get_nonspdetail($product_id,"screen");
		$branch_id			= $productslst['branchid'];
		$branch_name		= getbranchname($productslst['branchid']);		
		$supplier_name		= get_suppliername($productslst['po_id']);
		$supplier_id		= get_supplierid($productslst['po_id']);				
		$selling_price		= $productslst['selling_price'];		
        echo '<option class="'.$class.'" value="'.$product_id.'|'.$product_name.'|'.$product_category.'|'.$branch_id.'|'.$branch_name.'|'.$supplier_id.'|'.$selling_price.'">'.$product_name.' - '.$product_category.'</option>';      
    }        
    echo '</select>';
}

function getstock_sp($id, $class, $type, $selected) {
    echo '<select class="'.$class.'" id="'.$id.'" name="'.$id.'" size="21">';
	$query = "SELECT *,c.category_name AS parent_category
				FROM tbl_productcode AS a
				INNER JOIN tbl_category_inventory AS b
				INNER JOIN tbl_category_inventory_parent AS c
				ON a.productcode_categoryid=b.category_id AND
				c.category_id=b.category_parentid WHERE c.category_name='Spare Parts'";				
    $products_listing  = mysql_query($query) or die('Query failed: ' . mysql_error());
    $stocklisting = array();
    $product_category = '';
    while ($row = mysql_fetch_array($products_listing, MYSQL_ASSOC)) {  
		$productcode_id         = $row['productcode_id'];
		$part_number			= $row['property2'];
		$productcode_code       = $row['productcode_code'];
		$productcode_name       = $row['productcode_name'];
		$product_name			= $productcode_code.'&#92;'.$productcode_name;
        echo '<option class="'.$class.'" value="'.$productcode_id.'|'.$part_number.'|'.$product_name.'|'.$branch_id.'|'.$branch_name.'">'.$part_number.' - '.$product_name.'</option>';      
    }        
    echo '</select>';
}

function dropdown_showbranches ($class,$id,$selected) {
	$query = "SELECT * FROM tbl_branches ORDER BY branch_name";
    $recordset  = mysql_query($query) or die('Query failed: ' . mysql_error());
    echo '<select class="'.$class.'" id="'.$id.'" name="'.$id.'" size="1">';    
    echo '<option class="'.$class.'" value="ALL" >All Branches</option>';	
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {       
          if ($row['branch_id'] == $selected) {
              echo '<option class="'.$class.'" value='.$row['branch_id'].' selected>'.$row['branch_name'] . '</option>';
          } else {
              echo '<option class="'.$class.'" value='.$row['branch_id'].'>'.$row['branch_name']. '</option>';
          }
    }  
   echo '</select>';    
}
function stocklocation($class,$id,$selected) {
	$query = "SELECT * FROM tbl_branches ORDER BY branch_name";
    $recordset  = mysql_query($query) or die('Query failed: ' . mysql_error());
    echo '<select class="'.$class.'" id="'.$id.'" name="'.$id.'" size="1" disabled>';   
    echo '<option class="'.$class.'" value="" ></option>';		
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {       
          if ($row['branch_id'] == $selected) {
              echo '<option class="'.$class.'" value='.$row['branch_id'].' selected>'.$row['branch_name'] . '</option>';
          } else {
              echo '<option class="'.$class.'" value='.$row['branch_id'].'>'.$row['branch_name']. '</option>';
          }
    }  
   echo '</select>';    
}

function get_product_classification($id,$category) {
	if($category=="NONSP") {
		$query = "SELECT product_codeid FROM tbl_products_nonspareparts WHERE product_id=$id";
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $productcode_id = $row['product_codeid'];}  	
		$query = "SELECT
					*,
					(SELECT category_name FROM tbl_category_inventory WHERE tbl_productcode.productcode_categoryid=tbl_category_inventory.category_id) AS name_subcategory,
					(SELECT category_name FROM tbl_category_inventory_parent
					WHERE tbl_category_inventory_parent.category_id=(SELECT category_parentid FROM tbl_category_inventory WHERE tbl_category_inventory.category_id=tbl_productcode.productcode_categoryid)) AS name_parent
			  FROM tbl_productcode WHERE productcode_id=".$productcode_id;			
	} else {
		$query = "SELECT
					*,
					(SELECT category_name FROM tbl_category_inventory WHERE tbl_productcode.productcode_categoryid=tbl_category_inventory.category_id) AS name_subcategory,
					(SELECT category_name FROM tbl_category_inventory_parent
					WHERE tbl_category_inventory_parent.category_id=(SELECT category_parentid FROM tbl_category_inventory WHERE tbl_category_inventory.category_id=tbl_productcode.productcode_categoryid)) AS name_parent
			  FROM tbl_productcode WHERE productcode_id=".$id;		
	}
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $product_category = $row['name_parent'];
    }  
    return strtoupper(str_replace(' ','',$product_category));
}


function get_salesagentname($sales_agent_id) {
	$query = "SELECT * FROM tbl_salesagent WHERE emp_id=$sales_agent_id";
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {    
		  $sales_agent_name = ucfirst(strtolower($row['emp_lastname'])).", ".ucfirst(strtolower($row['emp_firstname']));
	}
	return $sales_agent_name;
}

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function postpaymentschedule($sales_id,$trans_owner_id,$amount,$type) { 
	$selling_price = $amount;
    $pointer = $sales_id;
    $query = "SELECT *,
                    (SELECT term_terms FROM tbl_paymentterms WHERE tbl_paymentterms.term_id=tbl_sales.payment_terms_id) AS term_terms,
                   (SELECT term_downpayment FROM tbl_paymentterms WHERE tbl_paymentterms.term_id=tbl_sales.payment_terms_id) AS term_downpayment
             FROM tbl_sales WHERE sales_id=".$pointer;    
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $sales_date         	 = date('Y-m-d', strtotime($row['sales_date']));
        $downpayment        	 = $row['downpayment'];
        $downpaymentsecond  	 = $row['downpaymentsecond'];		
        $term               	 = $row['term_terms']; 	
		$graceperiod			 = $row['graceperiod'];		
        $ps_amortization   		 = $row['monthly']; 
		$seconddownpaymentdate   = date('Y-m-d', strtotime($row['seconddownpaymentdate']));		
    }       
    if($type=="FULL") { //Insert Downpayment (FULL)
			$sql = "INSERT INTO tbl_paymentschedules
                                (
                                    ps_salesid,
                                    ps_date,
                                    ps_amortization,
                                    ps_ispaid,
                                    ps_particulars,
                                    ps_ownerid,
                                    ps_balance    
                                 )
                                 VALUES 
                                 (
                                    '$pointer',
                                    '$sales_date',
                                    '$downpayment',
                                    'N',
                                    'Downpayment',
                                    '$trans_owner_id',
                                    '$downpayment'
                                  )";           
            mysql_query($sql) or  die('<div class="message">Error! ' . mysql_error() . '</div>');       
			//First Month Payment
			if($graceperiod==0) {
				$newdate = date("Y-m-d", strtotime("$sales_date +1 months"));	
			} else {
				$newdate = date("Y-m-d", strtotime("$sales_date +$graceperiod days"));	
			}
            $sql = "INSERT INTO tbl_paymentschedules
                                    (
                                        ps_salesid,
                                        ps_date,
                                        ps_amortization,
                                        ps_ispaid,
                                        ps_particulars,
                                        ps_ownerid,
                                        ps_balance    
                                     )
                                     VALUES 
                                     (
                                        '$pointer',
                                        '$newdate',
                                        '$ps_amortization',
                                        'N',
                                        'Monthly Amortization',
                                        '$trans_owner_id',
                                        '$ps_amortization'
                                      )";                    
            mysql_query($sql) or  die('<div class="message">Error! ' . mysql_error() . '</div>');   			
			//Montly Amortizations						
			$getfirstdayofthemonth = date("Y",strtotime($newdate)).'-'.date("m",strtotime($newdate)).'-1';	
			$current_date = date('Y-m-d', strtotime($getfirstdayofthemonth)); 			
			$getproposedday = date('d', strtotime($newdate));			
            for ($i = 1; $i < $term; $i++) {
                $amount = 0;        
				$current_date 	= date("Y-m-d",strtotime("$current_date +1 months"));
				$month 			= date("m",strtotime($current_date));
				$year 			= date("Y",strtotime($current_date));
				$day 			= date("d",strtotime($current_date));				
				$new_date		= $year.'-'.$month.'-'.$day;
				$new_date		= date("Y-m-d",strtotime($new_date));				
				$lastday 		= date('t',strtotime($new_date));
				if($getproposedday>$lastday) { $new_date		= $year.'-'.$month.'-'.$lastday; } 
				else { $new_date		= $year.'-'.$month.'-'.$getproposedday; }
				$new_date		= date("Y-m-d",strtotime($new_date));	
				$payment_date 	= $new_date;
				
                $sql = "INSERT INTO tbl_paymentschedules
                                    (
                                        ps_salesid,
                                        ps_date,
                                        ps_amortization,
                                        ps_ispaid,
                                        ps_particulars,
                                        ps_ownerid,
                                        ps_balance    
                                     )
                                     VALUES 
                                     (
                                        '$pointer',
                                        '$payment_date',
                                        '$ps_amortization',
                                        'N',
                                        'Monthly Amortization',
                                        '$trans_owner_id',
                                        '$ps_amortization'
                                      )";                    
                mysql_query($sql) or  die('<div class="message">Error! ' . mysql_error() . '</div>');                  
            }
    } 
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
    else if ($type=="SPLIT") { //Insert Downpayment (SPLIT)
			//First Downpayment		
			$sql = "INSERT INTO tbl_paymentschedules
                                (
                                    ps_salesid,
                                    ps_date,
                                    ps_amortization,
                                    ps_ispaid,
                                    ps_particulars,
                                    ps_ownerid,
                                    ps_balance    
                                 )
                                 VALUES 
                                 (
                                    '$pointer',
                                    '$sales_date',
                                    '$downpayment',
                                    'N',
                                    'Downpayment',
                                    '$trans_owner_id',
                                    '$downpayment'
                                  )";           
            mysql_query($sql) or  die('<div class="message">Error! ' . mysql_error() . '</div>');     
			//Second Downpayment
			$sql = "INSERT INTO tbl_paymentschedules
                                (
                                    ps_salesid,
                                    ps_date,
                                    ps_amortization,
                                    ps_ispaid,
                                    ps_particulars,
                                    ps_ownerid,
                                    ps_balance    
                                 )
                                 VALUES 
                                 (
                                    '$pointer',
                                    '$seconddownpaymentdate',
                                    '$downpaymentsecond',
                                    'N',
                                    'Downpayment',
                                    '$trans_owner_id',
                                    '$downpaymentsecond'
                                  )";           
            mysql_query($sql) or  die('<div class="message">Error! ' . mysql_error() . '</div>');     			
			//First Month Payment
			$first_month_date 	= date("Y-m-d",strtotime("$sales_date +1 months"));
			$newdate = date("Y-m-d", strtotime("$first_month_date +$graceperiod days"));	
            $sql = "INSERT INTO tbl_paymentschedules
                                    (
                                        ps_salesid,
                                        ps_date,
                                        ps_amortization,
                                        ps_ispaid,
                                        ps_particulars,
                                        ps_ownerid,
                                        ps_balance    
                                     )
                                     VALUES 
                                     (
                                        '$pointer',
                                        '$newdate',
                                        '$ps_amortization',
                                        'N',
                                        'Monthly Amortization',
                                        '$trans_owner_id',
                                        '$ps_amortization'
                                      )";                    
            mysql_query($sql) or  die('<div class="message">Error! ' . mysql_error() . '</div>');   			
			//Montly Amortizations			
			$getfirstdayofthemonth = date("Y",strtotime($newdate)).'-'.date("m",strtotime($newdate)).'-1';	
			$current_date = date('Y-m-d', strtotime($getfirstdayofthemonth)); 			
			$getproposedday = date('d', strtotime($newdate));
            for ($i = 1; $i < $term; $i++) {
                $amount = 0;        
				
				$current_date 	= date("Y-m-d",strtotime("$current_date +1 months"));
				$month 			= date("m",strtotime($current_date));
				$year 			= date("Y",strtotime($current_date));
				$day 			= date("d",strtotime($current_date));				
				$new_date		= $year.'-'.$month.'-'.$day;
				$new_date		= date("Y-m-d",strtotime($new_date));				
				$lastday 		= date('t',strtotime($new_date));
				
				if($getproposedday>$lastday) { $new_date = $year.'-'.$month.'-'.$lastday; } 
				else { $new_date		= $year.'-'.$month.'-'.$getproposedday; }
				
				$new_date		= date("Y-m-d",strtotime($new_date));	
				$payment_date 	= $new_date;
				
                $sql = "INSERT INTO tbl_paymentschedules
                                    (
                                        ps_salesid,
                                        ps_date,
                                        ps_amortization,
                                        ps_ispaid,
                                        ps_particulars,
                                        ps_ownerid,
                                        ps_balance    
                                     )
                                     VALUES 
                                     (
                                        '$pointer',
                                        '$payment_date',
                                        '$ps_amortization',
                                        'N',
                                        'Monthly Amortization',
                                        '$trans_owner_id',
                                        '$ps_amortization'
                                      )";                    
                mysql_query($sql) or  die('<div class="message">Error! ' . mysql_error() . '</div>');       
            }			
    }
    else {
        //Insert Cash Payment Schedule
        $sql = "INSERT INTO tbl_paymentschedules
                            (
                                ps_salesid,
                                ps_date,
                                ps_amortization,
                                ps_ispaid,
                                ps_particulars,
                                ps_ownerid,
                                ps_balance
                             )
                             VALUES 
                             (
                                '$pointer',
                                '$sales_date',
                                '$amount',
                                'N',
                                'Cash Payment',
                                '$trans_owner_id',
                                '$selling_price'       
                              )";           
        mysql_query($sql) or  die('<div class="message">Error! ' . mysql_error() . '</div>');          
    }      
}

function get_jobordercustomername($id) {
    $query = "SELECT * FROM tbl_joborder_main WHERE job_id=$id";
    $recordset  = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $job_customerid = $row['job_customerid']; }   
    return $job_customerid;	
}

function get_jobordercustomeraddress($id) {
    $query = "SELECT * FROM tbl_joborder_main WHERE job_id=$id";
    $recordset  = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $job_customeraddress = $row['job_customeraddress']; }   
    return $job_customeraddress;	
}

function getstock_sold($id, $class, $type, $selected) {
/*
    //Get Products
    echo '<select class="'.$class.'" id="'.$id.'" name="'.$id.'" size="21">';
    $query = "SELECT product_id, branchid,
                  (SELECT branch_name FROM tbl_branches WHERE tbl_products_nonspareparts.branchid=tbl_branches.branch_id) AS branch_name,
                  (SELECT productcode_code FROM tbl_productcode WHERE tbl_productcode.productcode_id=tbl_products_nonspareparts.product_codeid) AS product_code,
                  (SELECT productcode_name FROM tbl_productcode WHERE tbl_productcode.productcode_id=tbl_products_nonspareparts.product_codeid) AS productcode_name,
                  (SELECT category_name FROM tbl_category_inventory_parent WHERE tbl_category_inventory_parent.category_id=
                        (SELECT category_parentid FROM tbl_category_inventory WHERE tbl_category_inventory.category_id=
                        (SELECT productcode_categoryid FROM tbl_productcode WHERE productcode_id=tbl_products_nonspareparts.product_codeid))) AS product_category       
                  FROM tbl_products_nonspareparts WHERE product_status='S' ORDER BY product_id";
    $products_listing  = mysql_query($query) or die('Query failed: ' . mysql_error());
    $stocklisting = array();
    $product_category = '';
    while ($productslst = mysql_fetch_array($products_listing, MYSQL_ASSOC)) {  
        if($productslst['product_category']<>"Spare Parts") {
              if($product_category<>$productslst['product_category']) {
                echo "</optgroup>"; 
                echo "<optgroup label='".$product_category   = $productslst['product_category']."'>"; 
                $product_category   = $productslst['product_category'];    
                $product_id_name    = 'SN'.number_pad($productslst['product_id'], 5);
                $product_id         = $productslst['product_id'];   
                $product_name       = $product_id_name.' - '.$productslst['productcode_name'].' ('.$productslst['product_code'].')';
                $query2 = "SELECT customer_id FROM tbl_sales WHERE product_id=".$productslst['product_id'];
                $customer_info  = mysql_query($query2) or die('Query failed: ' . mysql_error());
                while ($customer_lst = mysql_fetch_array($customer_info, MYSQL_ASSOC)) {  
                      $customer_id   = $customer_lst['customer_id'];
                      $customer_name = getaccountname($customer_id,'C');
                }                                
                echo '<option class="'.$class.'" value="'.$product_id.'|'.$product_id_name.'|'.$customer_id.'|'.$customer_name.'">'.$product_name.' - '.$customer_name.'</option>';
              }
              else { 
                  $query2 = "SELECT customer_id FROM tbl_sales WHERE product_id=".$productslst['product_id'];
                  $customer_info  = mysql_query($query2) or die('Query failed: ' . mysql_error());
                  while ($customer_lst = mysql_fetch_array($customer_info, MYSQL_ASSOC)) {  
                         $customer_id   = $customer_lst['customer_id'];
                         $customer_name = getaccountname($customer_id,'C');
                  }
                  $product_id_name    = 'SN'.number_pad($productslst['product_id'], 5);
                  $product_id         = $productslst['product_id'];   
                  $product_name       = $product_id_name.' - '.$productslst['productcode_name'].' ('.$productslst['product_code'].')';
                  echo '<option class="'.$class.'" value="'.$product_id.'|'.$product_id_name.'|'.$customer_id.'|'.$customer_name.'">'.$product_name.' - '.$customer_name.'</option>';
              }
        }
    }        
    echo '</select>';
	*/
}

function selectbranch_withall() {
    $query = "SELECT * FROM tbl_branches ORDER BY branch_name";
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    echo '<select class="listboxbranches" id="group_id" name="" size="20">';
	echo '<option class="" value="All">Select All</option>';    	
	echo "<optgroup label='Branches'>";
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			echo '<option class="" value="'.$row['branch_id'].'">'.$row['branch_name'].'</option>';    
		}  
    echo "</optgroup>";	
    echo '</select>';
}


function selectbranch_noall($id) {
    $query = "SELECT * FROM tbl_branches ORDER BY branch_name";
	$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    echo '<select class="listboxbranches2" id="'.$id.'" name="'.$id.'" size="1">';
	echo '<option class="" value="0"></option>';    	
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			echo '<option class="" value="'.$row['branch_id'].'">'.$row['branch_name'].'</option>';    
		}  
    echo '</select>';
}
function get_bankaccountname($account_id){    
	if($account_id<>"") {
		$query = "SELECT account_name,account_code FROM tbl_chartaccounts WHERE account_id=".$account_id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			return $row['account_code'];
		}   
	} else {
		return "";
	}
}
function get_bankaccount_mbalance($account_id){    
	if($account_id<>"") {
		$query = "SELECT maintaining_balance FROM tbl_chartaccounts WHERE account_id=".$account_id;
		$recordset = mysql_query($query) or die('Query failed: ' . mysql_error());    
		while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
			return $row['maintaining_balance'];
		}   
	} else {
		return "";
	}
}
?>




