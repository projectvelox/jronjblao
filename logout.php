<?php
    include "includes/config.php";
    include "includes/session.php";

    session_start(); 
    session_destroy(); 
    header("Location: login.php");
    
?>