<?php

if (session_id() == "") {
    session_start();    
    if(!isset($_SESSION['user_id'])){
    	header ("Location: ../login.php");
    	exit; 
	}
}

/*
if(!isset($_SESSION['user_id']) && $_SESSION['user_id'] != ''){
    header ("Location: login.php");
    exit; 
}*/

?>