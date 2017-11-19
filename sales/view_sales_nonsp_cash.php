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
                        (SELECT b_name FROM branches WHERE branches.b_id=sales_main.s_branch_id) AS s_branch,
                        (SELECT CONCAT(c_lastname,', ',c_firstname) FROM customers WHERE customers.c_id=sales_main.s_customer_id) AS s_customer,
                        (SELECT s_product_id FROM sales_sub WHERE sales_sub.s_sales_id=sales_main.s_id) AS s_product_id,
                        (SELECT CONCAT(p_code,' / ',p_name) FROM products WHERE products.p_id=(SELECT p_product_id FROM inventory WHERE inventory.p_id=(SELECT s_product_id FROM sales_sub WHERE sales_sub.s_sales_id=sales_main.s_id)) ) AS p_product_code,
                        (SELECT SUM(s_sold_price*s_qty) FROM sales_sub WHERE sales_sub.s_sales_id=sales_main.s_id) AS s_sold_price,   
                        (SELECT CONCAT(e_lastname,', ',e_firstname) FROM employees WHERE employees.e_id=sales_main.s_postedby_id) AS s_postedby,
                        (SELECT CONCAT(e_lastname,', ',e_firstname) FROM employees WHERE employees.e_id=sales_main.s_createdby_id) AS s_createdby
                        FROM sales_main WHERE s_id=".$_GET['id'];
    $recordset_main = mysql_query($query_main) or die('Query failed: ' . mysql_error());
    $data_sales = array();
    while ($row_main = mysql_fetch_array($recordset_main, MYSQL_ASSOC)) {
        $data_sales[] = $row_main;
        $p_id = $row_main['s_product_id'];
        $s_salesagent_id = $row_main['s_salesagent_id'];
        $s_customer_id = $row_main['s_customer_id'];
        $s_total = ($row_main['s_sold_price']+$row_main['s_othercharges']+$row_main['s_tax'])-$row_main['s_discount'];
        if($row_main['s_ispaid']=="Y") { $status = "Fully Paid"; } else { $status = "With Payables"; }
    }

    $customer = "";
    $query = "SELECT * FROM customers WHERE c_id=".$s_customer_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $customer = ucwords(strtolower($row['c_lastname'])).', '.ucwords(strtolower($row['c_firstname']));
    }

    $sales_agent = "";
    $query = "SELECT * FROM employees WHERE e_id=".$s_salesagent_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $sales_agent = ucwords(strtolower($row['e_lastname'])).', '.ucwords(strtolower($row['e_firstname']));
    }

    $query = "SELECT *,
                (SELECT p_code FROM products WHERE products.p_id=inventory.p_product_id) AS p_product_code,
                (SELECT p_name FROM products WHERE products.p_id=inventory.p_product_id) AS p_product_name,
                (SELECT i_name FROM inv_category WHERE inv_category.i_id=(SELECT p_category_id FROM products WHERE products.p_id=inventory.p_product_id)) AS p_category,
                (SELECT i_name FROM inv_category WHERE inv_category.i_id=((SELECT i_parent_id FROM inv_category WHERE inv_category.i_id=(SELECT p_category_id FROM products WHERE products.p_id=inventory.p_product_id)))) AS p_category_parent,
                (SELECT b_name FROM branches WHERE branches.b_id=inventory.p_branch_id) AS p_branch
                FROM inventory WHERE p_id=".$p_id;
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $product = get_stockcode($row["p_id"]).'-'.$row['p_id'].' / '.$row['p_product_code'].' / '.$row['p_product_name'].' / '.$row['p_reference'];
    }
    
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
<div class="form-container bg-lightgray" height="474" width="835" uuid="">
    <form id="form_sales"> 
        <input type="hidden" id="s_id" name="s_id" value="<?=$data_sales[0]['s_id'];?>" />
        <div class="col-lg-12">
            <span class="label-block">Product</span>
            <input disabled name="s_product" type="text" value="<?=$product;?>" class="field_readonly_block">
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
                <input disabled name="customer" type="text" value="<?=$customer;?>" class="field_readonly_block">
            </div> 
            <div class="col-lg-6">
                <span class="label-block">Sales Agent</span>
                <input disabled name="sales_agent" type="text" value="<?=$sales_agent;?>" class="field_readonly_block">
            </div> 
            <div class="col-lg-12">
                <span class="label-block">Notes</span>
                <textarea disabled name="s_notes" rows="4" cols="50" class="textarea-remarks-readonly" style="height: 81px"><?=$data_sales[0]['s_notes'];?></textarea>
            </div> 
            <div class="col-lg-6">
                <span class="label-block">Created By</span>
                <input disabled name="createdby" type="text" value="<?=$data_sales[0]['s_createdby'];?>" class="field_readonly_block">
            </div> 
            <div class="col-lg-6">
                <span class="label-block">Posted By</span>
                <input disabled name="postedby" type="text" value="<?=$data_sales[0]['s_postedby'];?>" class="field_readonly_block">
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
            <div class="col-lg-7"><span class="label-block align-text">Selling Price</span></div>
            <div class="col-lg-5"><input readonly="readonly" name="s_sold_price" type="text" value="<?=number_format($data_sales[0]['s_sold_price'],2,'.',',');?>" class="field_readonly_block"></div>
            <div class="col-lg-7"><span class="label-block align-text">Terms</span></div>
            <div class="col-lg-5">
                <select disabled name="s_payment_term" id="s_payment_term" class="field_readonly_block">
                <?php
                    $query = "SELECT * FROM paymentterms WHERE t_terms=0 ORDER BY t_code ASC";
                    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                        echo "<option value='".$row['t_terms']."' selected>".$row['t_code']."</option>";   
                    }
                ?>
                </select>
            </div>
            <div class="col-lg-7"><span class="label-block align-text">Delivery Date</span></div>
            <div class="col-lg-5"><input disabled name="s_deliverydate" type="text" value="<?=date('m/d/Y',strtotime($data_sales[0]['s_deliverydate']));?>" class="field_readonly_block"></div>
            <div class="col-lg-7"><span class="label-block align-text">OR Number</span></div>
            <div class="col-lg-5"><input disabled name="s_orno" type="text" value="<?=$data_sales[0]['s_orno'];?>" class="field_readonly_block"></div>
            <div class="col-lg-7"><span class="label-block align-text">Tax</span></div>
            <div class="col-lg-5"><input disabled name="s_tax" type="number" value="<?=$data_sales[0]['s_tax'];?>" class="field_readonly_block"></div>
            <div class="col-lg-7"><span class="label-block align-text">Other Charges</span></div>
            <div class="col-lg-5"><input disabled name="s_othercharges" type="number" value="<?=$data_sales[0]['s_othercharges'];?>" class="field_readonly_block"></div>
            <div class="col-lg-7"><span class="label-block align-text">Discount</span></div>
            <div class="col-lg-5"><input disabled name="s_discount" type="number" value="<?=$data_sales[0]['s_discount'];?>" class="field_readonly_block"></div>
            <div class="col-lg-7"><span class="label-block align-text">Total</span></div>
            <div class="col-lg-5"><input disabled name="s_total" type="text" value="<?=number_format($s_total,2,'.',',');?>" class="field_readonly_block"></div>
        </div>
    </form>
</div>
<div class="form-footer">
    <button class="form-button"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
    <button closewindow="off" class="form-button view-payments"><i class="fa fa-money" aria-hidden="true"></i> Collections</button>
    <button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
</div>
<script>

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

    $('#s_product_id').select2({
        data:<?=$data_product;?>,
        initSelection : function (element, callback) {
          var initialData = [];
          $(element.val().split(",")).each(function () {
            initialData.push({
              id  : this,
              text: this,
              price: this
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
    $('input[name="s_firstdownpayment_date"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
    $('input[name="s_seconddownpayment_date"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
    $('input[name="s_firstmonthlydue_date"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
    $('input[name="s_deliverydate"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });

    $('#s_product_id').on("change", function(e) {
        var id = $('#s_product_id').val();
        var discount = parseInt($('input[name="s_discount"]').val());
        var s_tax = parseInt($('input[name="s_tax"]').val());
        var s_othercharges = parseInt($('input[name="s_othercharges"]').val());
        $.ajax({
            type:'post',
            url:'post.php?action=getprice&id='+id,success:function(result) {
                var s_total = (parseInt(result)+s_othercharges+s_tax)-discount;
                $('input[name="s_sold_price"]').val(parseInt(result));
                $('input[name="s_total"]').val(s_total);
            }
        });
    })

    $('input').on("change", function(e) {
        var discount = parseInt($('input[name="s_discount"]').val());
        var s_tax = parseInt($('input[name="s_tax"]').val());
        var s_othercharges = parseInt($('input[name="s_othercharges"]').val());
        var sold_price = parseInt($('input[name="s_sold_price"]').val());
        var s_total = (sold_price+s_othercharges+s_tax)-discount;
        $('input[name="s_total"]').val(s_total);        
    })

</script>