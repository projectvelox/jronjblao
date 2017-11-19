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

   $query_main = "SELECT 
                        *,
                        (SELECT SUM(s_sold_price*s_qty) FROM sales_sub WHERE sales_sub.s_sales_id=sales_main.s_id) AS s_sold_price,   
                        (SELECT b_name FROM branches WHERE branches.b_id=sales_main.s_branch_id) AS s_branch,
                        (SELECT CONCAT(c_lastname,', ',c_firstname) FROM customers WHERE customers.c_id=sales_main.s_customer_id) AS s_customer,
                        (SELECT CONCAT(e_lastname,', ',e_firstname) FROM employees WHERE employees.e_id=sales_main.s_postedby_id) AS s_postedby,
                        (SELECT CONCAT(e_lastname,', ',e_firstname) FROM employees WHERE employees.e_id=sales_main.s_createdby_id) AS s_createdby,
                        (SELECT CONCAT(e_lastname,', ',e_firstname) FROM employees WHERE employees.e_id=sales_main.s_salesagent_id) AS sales_agent_name
                        FROM sales_main WHERE s_id=".$_GET['id'];
    $recordset_main = mysql_query($query_main) or die('Query failed: ' . mysql_error());
    $data_sales = array();
    while ($row_main = mysql_fetch_array($recordset_main, MYSQL_ASSOC)) {
        $data_sales[] = $row_main;
        $s_salesagent_id = $row_main['s_salesagent_id'];
        $s_customer_id = $row_main['s_customer_id'];
        $s_customer = $row_main['s_customer'];
        $sales_agent_name = $row_main['sales_agent_name'];
        $s_total = ($row_main['s_sold_price']+$row_main['s_othercharges']+$row_main['s_tax'])-$row_main['s_discount'];
        if($row_main['s_ispaid']=="Y") { $status = "Fully Paid"; } else { $status = "With Payables"; }
    }

    $data_customer = "[";
    $query = "SELECT * FROM customers ORDER BY c_lastname ASC, c_firstname ASC";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $data_customer .= "{id:'".$row['c_id']."',text:'".ucwords(strtolower($row['c_lastname'])).', '.ucwords(strtolower($row['c_firstname']))."'},";
    }
    $data_customer .= "]";

    $data_agent = "[";
    $query = "SELECT * FROM employees WHERE e_is_salesagent='Y' ORDER BY e_lastname ASC, e_firstname ASC";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $data_agent .= "{id:'".$row['e_id']."',text:'".ucwords(strtolower($row['e_lastname'])).', '.ucwords(strtolower($row['e_firstname']))."'},";
    }
    $data_agent .= "]";

    $query_items = "SELECT  *,
                        (SELECT CONCAT(p_code,' / ',p_name) FROM products WHERE products.p_id=(SELECT p_product_id FROM inventory WHERE inventory.p_id=sales_sub.s_product_id)) AS p_product_code,
                        (SELECT p_reference FROM inventory WHERE inventory.p_id=sales_sub.s_product_id) AS p_reference
                        FROM sales_sub WHERE s_sales_id=".$_GET['id'];
    $recordset_items = mysql_query($query_items) or die('Query failed: ' . mysql_error());
    $data_spareparts = '[';
    while ($row_items = mysql_fetch_array($recordset_items, MYSQL_ASSOC)) {
        $total = $row_items['s_sold_price'] * $row_items['s_qty'];
        $data_spareparts .= '{
            "s_sub_id":"'.$row_items['s_sub_id'].'",
            "p_product_code":"S'.$row_items['s_product_id'].' / '.$row_items['p_product_code'].' / '.$row_items['p_reference'].'",
            "s_product_id":"'.$row_items['s_product_id'].'",
            "s_sold_price":"'.$row_items['s_sold_price'].'",
            "s_qty":"'.$row_items['s_qty'].'",
            "s_total":"'.number_format($total,2,'.',',').'",
        },';
    }

    $data_spareparts .= ']';
    $total_payment = 0;
    $query_payments = "SELECT COALESCE(SUM(p_amount),0) AS total_payment FROM payments WHERE p_isposted = 'Y' AND p_sales_id=".$_GET['id'];
    $recordset_payments = mysql_query($query_payments) or die('Query failed: ' . mysql_error());
    while ($row_payments = mysql_fetch_array($recordset_payments, MYSQL_ASSOC)) {
        $total_payment = $row_payments['total_payment'];
    }
    $total_payable = 0;
    $query_sales = "SELECT *, (SELECT SUM(s_sold_price*s_qty) FROM sales_sub WHERE sales_sub.s_sales_id=sales_main.s_id) AS total_sales FROM sales_main WHERE s_id=".$_GET['id'];
    $recordset_sales = mysql_query($query_sales) or die('Query failed: ' . mysql_error());
    while ($row_sales = mysql_fetch_array($recordset_sales, MYSQL_ASSOC)) {
        $total_sales = $row_sales['total_sales'];
        $total_payable = ($total_sales+$row_sales['s_othercharges']+$row_sales['s_tax'])-$row_sales['s_discount'];
    }
    $total_balance = number_format($total_payable-$total_payment,2,'.',',');
    $total_payment = number_format($total_payment,2,'.',',');


?>
<div class="form-container bg-lightgray" height="548" width="835" uuid="">
    <form id="sales-sp"> 
        <input type="hidden" id="s_id" name="s_id" value="<?=$data_sales[0]['s_id'];?>" />
        <div class="col-lg-12">
            <div id="dbgrid-spareparts" style="border-left: 1px solid #bbbbbb;"></div>
        </div> 
        <div class="col-lg-8">
            <div class="col-lg-3">
                <span class="label-block">Sales Date</span>
                <input disabled name="s_sales_date" name="s_sales_date" type="text" value="<?=date('m/d/Y',strtotime($data_sales[0]['s_sales_date']));?>" class="field_readonly_block">
            </div>
            <div class="col-lg-6">
                <span class="label-block">Branch</span>
                <select disabled name="s_branch_id" id="s_branch_id" class="field_readonly_block">
                <?php
                    echo "<option value=''></option>";
                    $query = "SELECT * FROM branches ORDER BY b_name ASC";
                    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                        if($data_sales[0]['s_branch_id']==$row['b_id']) {
                            echo "<option value='".$row['b_id']."' selected>".$row['b_name']."</option>";
                        } else {
                            echo "<option value='".$row['b_id']."'>".$row['b_name']."</option>";   
                        }
                    }
                ?>
                </select>
            </div>
            <div class="col-lg-3">
                <span class="label-block">Sales Type</span>
                <input name="s_sales_type" type="hidden" value="C" class="field-input-block-thin">
                <input disabled name="s_sales_type_text" type="text" value="Cash" class="field_readonly_block">
            </div>         
            <div class="col-lg-6">
                <span class="label-block">Customer</span>
                <input disabled type="hidden" id="s_customer_id" name="s_customer_id" class="block" placeholder="Select"/>
            </div> 
            <div class="col-lg-6">
                <span class="label-block">Sales Agent</span>
                <input disabled type="hidden" id="s_salesagent_id" name="s_salesagent_id" class="block" placeholder="Select"/>
            </div> 
            <div class="col-lg-12">
                <span class="label-block">Notes</span>
                <textarea disabled name="s_notes" rows="4" cols="50" class="textarea-remarks-readonly" style="height: 48px"><?=$data_sales[0]['s_notes'];?></textarea>
            </div> 
            <div class="col-lg-4">
                <span class="label-block align-text">Payments</span>
                <input disabled name="" type="text" value="<?=$total_payment;?>" class="field_readonly_block">
            </div>
            <div class="col-lg-4">
                <span class="label-block align-text">Balance</span>
                <input disabled name="" type="text" value="<?=$total_balance;?>" class="field_readonly_block">
            </div>
            <div class="col-lg-4">
                <span class="label-block align-text">Status</span>
                <input disabled name="" type="text" value="<?=$status;?>" class="field_readonly_block">
            </div>

        </div>        
        <div class="col-lg-4">     
            <div class="col-lg-7"><span class="label-block align-text">Total Amount</span></div>
            <div class="col-lg-5"><input disabled name="s_sold_price" type="text" value="<?=$s_total;?>" class="field_readonly_block"></div>
            <div class="col-lg-7"><span class="label-block align-text">Delivery Date</span></div>
            <div class="col-lg-5"><input disabled name="s_deliverydate" type="text" value="<?=date('m/d/Y',strtotime($data_sales[0]['s_deliverydate']));?>" class="field_readonly_block"></div>
            <div class="col-lg-7"><span class="label-block align-text">OR Number</span></div>
            <div class="col-lg-5"><input disabled name="s_orno" type="text" value="<?=$data_sales[0]['s_orno'];?>" class="field_readonly_block"></div>
            <div class="col-lg-7"><span class="label-block align-text">Tax</span></div>
            <div class="col-lg-5"><input disabled name="s_tax" type="number" value="<?=$data_sales[0]['s_tax'];?>" class="field_readonly_block"></div>
            <div class="col-lg-7"><span class="label-block align-text">Other Charges</span></div>
            <div class="col-lg-5"><input disabled name="s_othercharges" type="number" value="<?=$data_sales[0]['s_othercharges'];?>" class="field_readonly_block"></div>
            <div class="col-lg-7"><span class="label-block align-text">Discount</span></div>
            <div class="col-lg-5"><input disabled  name="s_discount" type="number" value="<?=$data_sales[0]['s_discount'];?>" class="field_readonly_block"></div>
            <div class="col-lg-7"><span class="label-block align-text">Grand Total</span></div>
            <div class="col-lg-5"><input disabled name="s_total" type="text" value="0" class="field_readonly_block"></div>
        </div>
    </form>
</div>
<div class="form-footer">
    <button class="form-button"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
    <button closewindow="off" class="form-button view-payments"><i class="fa fa-money" aria-hidden="true"></i> Payments</button>
    <button closewindow="off" class="form-button close-sales-sp-cash-form"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
</div>
<script>

    $(document).ready(function(){    
        $('#s_salesagent_id').select().attr('placeholder','<?=$sales_agent_name;?>');
        $('#s_salesagent_id').trigger('change');
        $('#s_salesagent_id').val("<?=$s_salesagent_id;?>");
        $('#s_customer_id').select().attr('placeholder','<?=$s_customer;?>');
        $('#s_customer_id').trigger('change');
        $('#s_customer_id').val("<?=$s_customer_id;?>");    
    });

    $("#dbgrid-spareparts").jsGrid({
        height: "200px",
        width: "100%",
        filtering: false,
        editing: false,
        sorting: true,
        paging: false,
        autoload: true,
        data: <?php echo $data_spareparts; ?>,
        fields: [
            {name:"p_product_code", type:"text", width:"350px", title:"PRODUCT", align:"left"},
            {name:"s_sold_price", type:"text", width:"80px", title:"UNIT PRICE", align:"right"},
            {name:"s_qty", type:"text", width:"50px", title:"QTY", align:"center"},
            {name:"s_total", type:"text", width:"80px", title:"TOTAL", align:"right"},
        ]
    });


    var numerical = document.querySelectorAll('input[type=number]')
    numerical.forEach(function (input) {
      input.addEventListener('change', function (e) {
        if (e.target.value == '') {
          e.target.value = 0
        }
      })
    })

    $('#s_customer_id').select2({
        data:<?=$data_customer;?>,
        initSelection : function (element, callback) {
          var initialData = [];
          $(element.val().split(",")).each(function () {
            initialData.push({
              id  : this,
              text: this
            });
          });
          callback(initialData);
        },
        dropdownCssClass : 'capitalize',
        containerCssClass: 'capitalize',
        formatLoadMore   : 'Loading more...',
        query            : function (q) {
          var pageSize,
            results;
          pageSize = 20; 
          results  = [];
          if (q.term && q.term !== "") {
            results = _.filter(this.data, function (e) {
              return (e.text.toUpperCase().indexOf(q.term.toUpperCase()) >= 0);
            });
          } else if (q.term === "") {
            results = this.data;
          }
          q.callback({
            results: results.slice((q.page - 1) * pageSize, q.page * pageSize),
            more   : results.length >= q.page * pageSize
          });
        }
    });

    $('#s_salesagent_id').select2({
        data:<?=$data_agent;?>,
        initSelection : function (element, callback) {
          var initialData = [];
          $(element.val().split(",")).each(function () {
            initialData.push({
              id  : this,
              text: this
            });
          });
          callback(initialData);
        },
        dropdownCssClass : 'capitalize',
        containerCssClass: 'capitalize',
        formatLoadMore   : 'Loading more...',
        query            : function (q) {
          var pageSize,
            results;
          pageSize = 20; 
          results  = [];
          if (q.term && q.term !== "") {
            results = _.filter(this.data, function (e) {
              return (e.text.toUpperCase().indexOf(q.term.toUpperCase()) >= 0);
            });
          } else if (q.term === "") {
            results = this.data;
          }
          q.callback({
            results: results.slice((q.page - 1) * pageSize, q.page * pageSize),
            more   : results.length >= q.page * pageSize
          });
        }
    });

    $('input[name="s_sales_date"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
    $('input[name="s_deliverydate"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });

    $('input').on("change", function(e) {
        var discount = parseInt($('input[name="s_discount"]').val());
        var s_tax = parseInt($('input[name="s_tax"]').val());
        var s_othercharges = parseInt($('input[name="s_othercharges"]').val());
        var sold_price = parseInt($('input[name="s_sold_price"]').val());
        var s_total = (sold_price+s_othercharges+s_tax)-discount;
        $('input[name="s_total"]').val(s_total);        
    })

</script>