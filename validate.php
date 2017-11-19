<?php
    include "includes/config.php";
    $found = 0;
    $query = "SELECT *,
                    (SELECT d_name FROM employee_designations WHERE employee_designations.d_id=employees.e_designation_id) AS employee_designation
                FROM employees WHERE e_username='".$_POST['u']."' AND e_password='".$_POST['p']."'";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {       
        $user_id = $row['e_id'];
        $branch_id = $row['e_branch_id'];
        $employee_designation = $row['employee_designation'];
        $employee_name = ucwords(strtolower($row['e_firstname']))." ".ucwords(strtolower($row['e_lastname']));
        $found = 1;
        
    }
    if($found==1) {
        session_start();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['branch_id'] = $branch_id;
        $_SESSION['employee_name'] = $employee_name;
        $_SESSION['employee_designation'] = $employee_designation;
        header("Location: dashboard/");
    } else {
        header("Location: login.php");
    }
?>