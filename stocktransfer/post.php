<?php

include "../includes/config.php";
session_start();


if($_GET['action']=="verify") {
    $count = 0;
    $query = "SELECT * FROM stocktransfer_sub WHERE t_id=".$_GET['id'];
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $count = $count + 1;
    }
    if($count > 0 ) {
        $sql = "UPDATE stocktransfer_main SET t_verifiedby_id='".$_SESSION['user_id']."',t_verify_date='".date('Y-m-d')."' WHERE t_id=".$_GET['id'];
        mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
        echo 'success';
    } else {
        echo '<div class="error-message">No inventory to transfer. Please select items.</div>';
    }
} else if($_GET['action']=="approved") {
    $sql = "UPDATE stocktransfer_main SET t_approvedby_id='".$_SESSION['user_id']."',t_approved_date='".date('Y-m-d')."' WHERE t_id=".$_GET['id'];
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} else if($_GET['action']=="intransit") {
    $sql = "UPDATE stocktransfer_main SET t_intransitby_id='".$_SESSION['user_id']."',t_intransit_date='".date('Y-m-d')."' WHERE t_id=".$_GET['id'];
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} else if($_GET['action']=="canceltransfer") {
    $sql = "UPDATE stocktransfer_main SET t_cancelby_id='".$_SESSION['user_id']."',t_cancel_date='".date('Y-m-d')."' WHERE t_id=".$_GET['id'];
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    $query = "SELECT * FROM stocktransfer_sub WHERE t_id=".$_GET['id'];
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $sql = "UPDATE inventory SET p_istransfer='N' WHERE p_id=".$row['p_id'];
        mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    }
} else if($_GET['action']=="createtransfer") {
    $t_request_date = date("Y-m-d",strtotime($_POST['t_request_date']));
    $t_requestby_id = addslashes($_POST['t_requestby_id']);
    $t_branch_origin_id = addslashes($_POST['t_branch_origin_id']);
    $t_branch_destination_id = addslashes($_POST['t_branch_destination_id']);
    $t_notes = addslashes($_POST['t_notes']);
    $sql = "INSERT INTO stocktransfer_main (
            t_request_date,t_requestby_id,t_branch_origin_id,t_branch_destination_id,t_notes) 
            VALUES ('$t_request_date','$t_requestby_id','$t_branch_origin_id','$t_branch_destination_id','$t_notes')";
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    echo mysql_insert_id();
} else if($_GET['action']=="additem") {
    $t_id = addslashes($_GET['t_id']);
    $p_id = addslashes($_GET['p_id']);
    $sql = "INSERT INTO stocktransfer_sub (t_id,p_id,p_qty) VALUES ('$t_id','$p_id','1')";
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    $sql = "UPDATE inventory SET p_istransfer='Y' WHERE p_id=".$p_id;
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} else if($_GET['action']=="deleteitem") {
    $query = "SELECT p_id FROM stocktransfer_sub WHERE t_sub_id=".$_GET['id'];
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $p_id = $row['p_id'];
    }
    $sql = "UPDATE inventory SET p_istransfer='N' WHERE p_id=".$p_id;
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    $sql = "DELETE FROM stocktransfer_sub WHERE t_sub_id=".$_GET['id'];      
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} elseif ($_GET['action'] == "updatetransfer") {
    $t_id = addslashes($_POST['t_id']);
    $t_branch_destination_id = addslashes($_POST['t_branch_destination_id']);
    $t_notes = addslashes($_POST['t_notes']);
    $sql = "UPDATE stocktransfer_main SET t_branch_destination_id='$t_branch_destination_id',t_notes='$t_notes' WHERE t_id=".$t_id;
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
} elseif ($_GET['action'] == "recieved") {

    $query = "SELECT t_sub_id,t_id,p_id,
                    (SELECT t_branch_destination_id FROM stocktransfer_main WHERE stocktransfer_main.t_id=stocktransfer_sub.t_id) AS branch_destination_id
                FROM stocktransfer_sub WHERE t_sub_id=".$_GET['t_sub_id'];
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $t_id = $row['t_id'];
        $p_id = $row['p_id'];
        $branch_destination_id = $row['branch_destination_id'];
    }
    $sql = "UPDATE inventory SET p_branch_id='$branch_destination_id' WHERE p_id=".$p_id;
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    $sql = "UPDATE stocktransfer_sub SET p_is_recieved='Y' WHERE t_sub_id=".$_GET['t_sub_id'];
    mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    $count = 1;
    $query = "SELECT COUNT(*) AS recieved FROM stocktransfer_sub WHERE p_is_recieved='N' AND t_id=".$t_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) { $count = $row['recieved']; }
    if($count == 0) {
        $sql = "UPDATE stocktransfer_main SET t_receivedby_id='".$_SESSION['user_id']."',t_received_date='".date('Y-m-d')."' WHERE t_id=".$t_id;
        mysql_query($sql) or die('<div class="message">Error! '.mysql_error().'</div>');
    }
}




?>  