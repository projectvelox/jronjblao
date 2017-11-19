<?php
	if(!defined("MYSQL_ASSOC")) define("MYSQL_ASSOC", MYSQLI_ASSOC);

	class mysqlToMysqliAdapter {
		public $con = "";
	}

	$mysqlToMysqliAdapter = new mysqlToMysqliAdapter; 

	if (!function_exists('mysql_connect')) {
	    function mysql_connect($host, $username, $password) {
	    	global $mysqlToMysqliAdapter;
	        $mysqlToMysqliAdapter->con = mysqli_connect($host, $username, $password);
	        return $mysqlToMysqliAdapter->con;
	    }
	}

	if (!function_exists('mysql_error')) {
	    function mysql_error() {
	    	global $mysqlToMysqliAdapter;
	        return mysqli_error($mysqlToMysqliAdapter->con);
	    }
	}

	if (!function_exists('mysql_select_db')) {
	    function mysql_select_db($database) {
	    	global $mysqlToMysqliAdapter;
	        return mysqli_select_db($mysqlToMysqliAdapter->con, $database);
	    }
	}

	if (!function_exists('mysql_query')) {
	    function mysql_query($query) {
	    	global $mysqlToMysqliAdapter;
	        return mysqli_query($mysqlToMysqliAdapter->con, $query);
	    }
	}

	if (!function_exists('mysql_fetch_array')) {
	    function mysql_fetch_array($result, $type) {
	    	return mysqli_fetch_array($result, $type);
	    }
	}

	if (!function_exists('mysql_fetch_assoc')) {
		function mysql_fetch_assoc($result) {
			return mysqli_fetch_assoc($result);
		}
	}
?>