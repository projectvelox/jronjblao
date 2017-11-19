<?php
	include("mysql_to_mysqli_adapter.php");
    date_default_timezone_set('Pacific/Ponape');

    define("DB_HOST","localhost");
    define("DB_DATABASE","wais.retail");
    define("DB_USERNAME","wais_admin");
    define("DB_PASSWORD","w@1s@dm1n");
    define("SYSTEM_NAME","WAIS");

    $dbconn = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD) or die('Could not connect: ' . mysql_error());
    mysql_select_db(DB_DATABASE) or die('Could not select database');

    function get_stockcode($id) {
    	$code = "";
	    $query = "SELECT 
					p_product_id,
					(SELECT i_code FROM inv_category WHERE inv_category.i_id=(SELECT p_category_id FROM products WHERE products.p_id=inventory.p_product_id)) AS i_code
					FROM inventory WHERE p_id=".$id;
	    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
	    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
	    	$code = $row['i_code'];
	    }
	    return $code;
	}

	function get_userbranch($id) {
    	$code = "";
	    $query = "SELECT 
						e_branch_id,
						(SELECT b_name FROM branches WHERE branches.b_id=employees.e_branch_id) AS branch_name
					FROM employees WHERE e_id=".$id;
	    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
	    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
	    	$branch = $row['branch_name'];
	    }
	    return $branch;
	}

?>
