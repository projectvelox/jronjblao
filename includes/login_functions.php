<?php
//require_once "config.php";
function checkuser_menuoptions($emp_id,$menu_options) {  
	$dbconn = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD) or die('Could not connect: ' . mysql_error());
	mysql_select_db(DB_DATABASE) or die('Could not select database');
    $query = "SELECT * FROM user_accessrights WHERE emp_id=".$emp_id;
	$allowed = 0;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {       
		$allowed = $row[$menu_options]; 
    }
	return $allowed;
}
?>