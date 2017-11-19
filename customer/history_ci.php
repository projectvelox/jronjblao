<?php
    include "../includes/config.php";
    include "../includes/functions.php";
    include "../includes/functions.form.php";
    include "../includes/login_functions.php";
    include "../includes/session.php";
?>

<?php
    $customer_id = $_GET['id'];
    $query = "SELECT CONCAT(c_lastname, ', ', c_firstname, ' ', c_middlename) AS name FROM customers WHERE c_id=$customer_id";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    $customer_name = mysql_fetch_assoc($recordset)['name'];


    $credit_history = [];
    $query = "SELECT id, ci_application_date, application_status FROM form_ci_1 WHERE ci_id=$customer_id";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) $credit_history[] = $row;
?>


<style type="text/css">
    table {
      border: 1px solid #ccc;
      border-collapse: collapse;
      margin: 0;
      padding: 0;
      width: 100%;
      table-layout: fixed;
    }
    table caption {
      font-size: 1.5em;
      margin: .5em 0 .75em;
    }
    table tr {
      background: #f8f8f8;
      border: 1px solid #ddd;
      padding: .35em;
    }
    table th, table td {
      padding: .625em;
      text-align: center;
    }
    table th {
      font-size: .85em;
      letter-spacing: .1em;
    }
    table td {
      font-size: .85em;
      letter-spacing: .1em;
    }


</style>
<div class="form-container">
    <p>Credit History of: <?php echo $customer_name; ?></p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Status</th>
                <th>View</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($credit_history as $history) { ?>
            <tr>
                <td><?php echo $history["id"]; ?></td>
                <td><?php echo $history["ci_application_date"]; ?></td>
                <td><?php echo $history["application_status"]; ?></td>
                <td><a href="print_ci.php?id=<?php echo $history["id"]; ?>" target="_blank" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-eye-open"></i> View</a></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<div class="form-footer">
    <!--
    <button closewindow="off" class="form-button create-ci"><i class="fa fa-eye" aria-hidden="true"></i> Credit Investigation</button>
    <button closewindow="off" class="form-button update-customer"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
    -->
    <button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
</div>
<script>
    //$('input[name="c_birthdate"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
</script>