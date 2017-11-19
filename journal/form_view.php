<style>
    .jsgrid-grid-header, .jsgrid-grid-body {
        overflow-y: scroll !important;
    }
</style>
<?php
    include "../includes/config.php";
    include "../includes/functions.php";
    include "../includes/functions.form.php";
    include "../includes/login_functions.php";
    include "../includes/session.php";

    $query_main = "SELECT *,
                    (SELECT b_name FROM branches WHERE branches.b_id=journal_main.j_branch_id) AS branch_name,
                    (SELECT CONCAT(e_lastname,', ',e_firstname) FROM employees WHERE journal_main.j_postedby_id=employees.e_id) AS postedby_name
                    FROM journal_main WHERE j_id=".$_GET['id'];
    $recordset_main = mysql_query($query_main) or die('Query failed: ' . mysql_error());
    while ($row_main = mysql_fetch_array($recordset_main, MYSQL_ASSOC)) {
        if($row_main['j_origination_category']="S")  { $origination = "Sales"; }
        else if($row_main['j_origination_category']="PL")  { $origination = "Pull Out"; }
        else if($row_main['j_origination_category']="PO")  { $origination = "Purchase Order"; }
        $total_debit = 0;
        $total_credit = 0;
        $query_sub = "SELECT 
                            SUM(IF(j_entry='D',j_amount,0)) total_debit,
                            SUM(IF(j_entry='C',j_amount,0)) total_credit
                        FROM journal_sub WHERE j_parent_id=".$row_main['j_id'];
        $recordset_sub = mysql_query($query_sub) or die('Query failed: ' . mysql_error());
        while ($row_sub = mysql_fetch_array($recordset_sub, MYSQL_ASSOC)) {
            $total_debit = $row_sub['total_debit'];
            $total_credit = $row_sub['total_credit'];
        }    
        $data[] = $row_main;
        $j_no = 'J'.$row_main['j_id'];
    }

    $query_journal = "SELECT * FROM journal_sub WHERE j_parent_id=".$_GET['id'];
    $recordset_journal = mysql_query($query_journal) or die('Query failed: ' . mysql_error());
    
    $total_debit = 0;
    $total_credit = 0;
    $total = 0;

    $data_journal = '[';
    while ($row_journal = mysql_fetch_array($recordset_journal, MYSQL_ASSOC)) {
        if($row_journal['j_entry']=="D") {
            $data_journal .= '{
                "total_debit":"'.number_format($row_journal['j_amount'],2,'.',',').'",
                "total_credit":"'.number_format(0,2,'.',',').'",
                "particulars":"'.$row_journal['j_entry_description'].'",
            },';
            $total_debit = $total_debit + $row_journal['j_amount'];
        } else {
            $data_journal .= '{
                "total_debit":"'.number_format(0,2,'.',',').'",
                "total_credit":"'.number_format($row_journal['j_amount'],2,'.',',').'",
                "particulars":"'.$row_journal['j_entry_description'].'",
            },';
            $total_credit = $total_credit + $row_journal['j_amount'];
        }
    }
    
    $data_journal .= '{
        "total_debit":"<strong>'.number_format($total_debit,2,'.',',').'</strong>",
        "total_credit":"<strong>'.number_format($total_credit,2,'.',',').'</strong>",
        "particulars":"",
    },';
    $data_journal .= ']'; 

?>
<div class="form-container bg-lightgray" height="427" width="800" uuid="">
    <form id=""> 
        <div class="col-lg-7">
            <div class="col-lg-4">
                <span class="label-block">Journal No.</span>
                <input disabled name="" type="text" value="<?=$j_no;?>" class="field_readonly_block">
            </div>
            <div class="col-lg-4">
                <span class="label-block">Transaction Date</span>
                <input disabled name="" type="text" value="<?=formatdate($data[0]["j_transaction_date"]);?>" class="field_readonly_block">
            </div>
            <div class="col-lg-4">
                <span class="label-block">Origination</span>
                <input disabled name="p_redeem_fee" type="text" value="<?=$origination;?>" class="field_readonly_block">
            </div>
            <div class="col-lg-6">
                <span class="label-block">Branch</span>
                <input disabled name="p_redeem_date" type="text" value="<?=$data[0]["branch_name"];?>" class="field_readonly_block">
            </div>
            <div class="col-lg-6">
                <span class="label-block">Posted by</span>
                <input disabled name="" type="text" value="<?=$data[0]["postedby_name"];?>" class="field_readonly_block">
            </div>
        </div>
        <div class="col-lg-5">
            <div class="col-lg-12">
                <span class="label-block">Particulars</span>
                <textarea disabled="" name="" rows="4" cols="50" class="textarea-remarks-readonly" style="height: 76px"><?=$data[0]["j_particulars"];?></textarea>
            </div> 
        </div>
        <div class="col-lg-12">
            <div id="dbgrid-journal" class="datagrid-sub"></div>
        </div> 
    </div>
        
    </form>
</div>
<div class="form-footer">
    <button class="form-button"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
    <button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
</div>
<script>
    $("#dbgrid-journal").jsGrid({
        height: "200px",
        width: "756px",
        filtering: false,
        editing: false,
        sorting: false,
        data: <?php echo $data_journal; ?>,
        fields: [           
            {name:"particulars", type:"text", width:"400px", title:"JORUNAL ENTRIES", align:"left"},
            {name:"total_debit", type:"text", width:"100px", title:"DEBIT", align:"right"},
            {name:"total_credit", type:"text", width:"100px", title:"CREDIT", align:"right"},
        ]
    });
</script>