<link type="text/css" rel="stylesheet" href="../css/jsgrid.min.css" />
<link type="text/css" rel="stylesheet" href="../css/jsgrid-theme.min.css" />
<script type="text/javascript" src="../js/jsgrid.min.js"></script>
<?php 
    include "../includes/config.php";
    include "../includes/session.php";

    $running_charges = 0;

    $total_payment = 0;
    $query_payments = "SELECT COALESCE(SUM(p_amount),0) AS total_payment FROM payments WHERE p_isposted = 'Y' AND p_sales_id=".$_GET['id'];
    $recordset_payments = mysql_query($query_payments) or die('Query failed: ' . mysql_error());
    while ($row_payments = mysql_fetch_array($recordset_payments, MYSQL_ASSOC)) {
        $total_payment = $row_payments['total_payment'];
    }

    $data = '[';
    $query_main = "SELECT 
                        *,
                        (SELECT SUM(s_sold_price*s_qty) FROM sales_sub WHERE sales_sub.s_sales_id=sales_main.s_id) AS s_sold_price   
                        FROM sales_main WHERE s_id=".$_GET['id'];
    $recordset_main = mysql_query($query_main) or die('Query failed: ' . mysql_error());
    while ($row_main = mysql_fetch_array($recordset_main, MYSQL_ASSOC)) {
        $term = $row_main['s_payment_term'];
        $first_down = $row_main['s_firstdownpayment'];
        $s_firstdownpayment_date = $row_main['s_firstdownpayment_date'];
        $second_down = $row_main['s_seconddownpayment'];
        $s_seconddownpayment_date = $row_main['s_seconddownpayment_date'];
        $s_downpayment_type = $row_main['s_downpayment_type'];
        $total = ($row_main['s_sold_price']+$row_main['s_othercharges']+$row_main['s_tax'])-$row_main['s_discount'];
        $monthly = ($total - ($first_down +$second_down)) / $term;
        $s_firstmonthlydue_date = date('m/d/Y',strtotime($row_main['s_firstmonthlydue_date']));
    }

    $paid = "";
    $data .= '{"p_date":"'.date('m/d/Y',strtotime($s_firstdownpayment_date)).'",
                "p_particulars":"Accounts Payable / Sales Purchase",
                "p_amount":"",
                "p_balance":"'.number_format($total, 2,'.',',').'",
                "p_ispaid":"'.$paid.'",
            },';


    if($s_downpayment_type=="S") {
        $total = $total - $first_down; 
        $running_charges = $running_charges + $first_down;
        if($total_payment >= $running_charges ) {
            $paid = 'Y';
        } else {
            $paid = "";
        }
        $data .= '{"p_date":"'.date('m/d/Y',strtotime($s_firstdownpayment_date)).'",
                    "p_particulars":"First Downpayment",
                    "p_amount":"'.number_format($first_down, 2,'.',',').'",
                    "p_balance":"'.number_format($total, 2,'.',',').'",
                    "p_ispaid":"'.$paid.'",
                },';
        $total = $total - $second_down;    
        $running_charges = $running_charges + $second_down;
        if($total_payment >= $running_charges ) {
            $paid = 'Y';
        } else {
            $paid = "";
        }
        $data .= '{"p_date":"'.date('m/d/Y',strtotime($s_seconddownpayment_date)).'",
                    "p_particulars":"Second Downpayment",
                    "p_amount":"'.number_format($second_down, 2,'.',',').'",
                    "p_balance":"'.number_format($total, 2,'.',',').'",
                    "p_ispaid":"'.$paid.'",
                },';

    } else {
        $total = $total - $first_down; 
        $running_charges = $running_charges + $first_down;
        if($total_payment >= $running_charges ) {
            $paid = 'Y';
        } else {
            $paid = "";
        }
        $data .= '{"p_date":"'.date('m/d/Y',strtotime($s_firstdownpayment_date)).'",
                    "p_particulars":"Downpayment",
                    "p_amount":"'.number_format($first_down, 2,'.',',').'",
                    "p_balance":"'.number_format($total, 2,'.',',').'",
                    "p_ispaid":"'.$paid.'",
                },';
    }


    for ($x = 1; $x <= $term; $x++) {
        $total = $total - $monthly;   
        $running_charges = $running_charges + $monthly;
        if($total_payment >= $running_charges ) {
            $paid = 'Y';
        } else {
            $paid = "";
        }
        

        $data .= '{"p_date":"'.$s_firstmonthlydue_date.'",
                    "p_particulars":"Monthly Amortization",
                    "p_amount":"'.number_format($monthly, 2,'.',',').'",
                    "p_balance":"'.number_format($total, 2,'.',',').'",
                    "p_ispaid":"'.$paid.'",
                },';
        
        $time = strtotime($s_firstmonthlydue_date);
        $s_firstmonthlydue_date = date("m/d/Y", strtotime("+1 month", $time));
    } 
    $data .= ']';
?>
<div class="form-container bg-lightgray nopadding" height="501" width="700" uuid="">
    <div id="dbgrid-paymentschdule"></div>
</div>
<div class="form-footer">
    <button closewindow="off" class="form-button"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
    <button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
</div>
<script>
    $("#dbgrid-paymentschdule").jsGrid({
        height: "420px",
        width: "100%",
        filtering: false,
        editing: false,
        sorting: true,
        paging: true,
        autoload: true,
        pageSize: 15,
        pageButtonCount: 15,
        data: <?php echo $data; ?>,
        fields: [
            /*{   
                title:"PAID", align:"left",width:"40px", align:"center", sorting:false,
                itemTemplate: function(value,e) { 
                    if(e.p_ispaid=="Y"){
                        return $("<div>").append('<i class="fa fa-check" aria-hidden="true"></i>'); 
                    } else {
                        return $("<div>").append(''); 
                    }
                },
            }, */           
            {name:"p_date", type:"text", width:"80px", title:"DATE", align:"center"},
            {name:"p_particulars", type:"text", width:"250px", title:"PARTICULARS", align:"left"},
            {name:"p_amount", type:"text", width:"100px", title:"AMOUNT", align:"right"},
            {name:"p_balance", type:"text", width:"100px", title:"BALANCE", align:"right"},
        ]
    });
</script>

