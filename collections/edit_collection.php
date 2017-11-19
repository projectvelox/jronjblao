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
                        (SELECT CONCAT(p_code,' - ', p_name) FROM products WHERE products.p_id=
                            (SELECT p_product_id FROM inventory WHERE inventory.p_id=
                                (SELECT s_product_id FROM sales_sub WHERE sales_sub.s_sales_id=payments.p_sales_id)
                            )) AS p_product_code, 
                        (SELECT CONCAT(c_Lastname,', ',c_firstname) FROM customers WHERE customers.c_id=(SELECT s_customer_id FROM sales_main WHERE sales_main.s_id=payments.p_sales_id)) AS s_customer_name,
                        (SELECT CONCAT(e_Lastname,', ',e_firstname) FROM employees WHERE employees.e_id=payments.p_collector_id) AS s_collector_name,
                        (SELECT s_customer_id FROM sales_main WHERE sales_main.s_id=payments.p_sales_id) AS s_customer_id,
                        (SELECT s_sales_type FROM sales_main WHERE sales_main.s_id=payments.p_sales_id) AS s_sales_type
                        FROM payments 
                        WHERE p_id=".$_GET['id'];
    $recordset_main = mysql_query($query_main) or die('Query failed: ' . mysql_error());
    while ($row_main = mysql_fetch_array($recordset_main, MYSQL_ASSOC)) {
        $data[] = $row_main;
        $s_collector_name = $row_main['s_collector_name'];
        $s_collector_id = $row_main['p_collector_id'];
        $payment_for_name = 'Sales No.: S'.$row_main['p_sales_id'].' / Customer: '.$row_main['s_customer_name'].' (C'.$row_main['s_customer_id'].') / Product: '.$row_main['p_product_code'];
        $payment_for_id = $row_main['p_sales_id'];
    }    

    $data_collector = "[";
    $query = "SELECT * FROM employees WHERE e_is_collector='Y' ORDER BY e_lastname ASC, e_firstname ASC";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $data_collector .= "{id:'".$row['e_id']."',text:'".ucwords(strtolower($row['e_lastname'])).', '.ucwords(strtolower($row['e_firstname']))."'},";
    }
    $data_collector .= "]";


/*    $query_main = "SELECT 
                        *,
                        (SELECT CONCAT(p_code,' - ', p_name) FROM products WHERE products.p_id=
                            (SELECT p_product_id FROM inventory WHERE inventory.p_id=
                                (SELECT s_product_id FROM sales_sub WHERE sales_sub.s_sales_id=sales_main.s_id)
                            )) AS p_product_code, 
                        (SELECT CONCAT(c_lastname,', ',c_firstname) FROM customers WHERE customers.c_id=sales_main.s_customer_id) AS s_customer
                        FROM sales_main
                        WHERE s_ispaid='N'
                            AND s_branch_id=".$_SESSION['branch_id']." ORDER BY s_confirm ASC, s_sales_date ASC";
*/

    $query_main = "SELECT 
                        *,
                        (SELECT CONCAT(p_code,' - ', p_name) FROM products WHERE products.p_id=
                            (SELECT p_product_id FROM inventory WHERE inventory.p_id=
                                (SELECT s_product_id FROM sales_sub WHERE sales_sub.s_sales_id=sales_main.s_id)
                            )) AS p_product_code, 
                        (SELECT CONCAT(c_lastname,', ',c_firstname) FROM customers WHERE customers.c_id=sales_main.s_customer_id) AS s_customer
                        FROM sales_main
                        WHERE s_ispaid='N'
                        ORDER BY s_confirm ASC, s_sales_date ASC";
                        
    $recordset_main = mysql_query($query_main) or die('Query failed: ' . mysql_error());
    $data_sales = '[';
    while ($row_main = mysql_fetch_array($recordset_main, MYSQL_ASSOC)) {
        $data_sales .= "{id:'".$row_main['s_id']."',text:'Sales No.: S".$row_main['s_id'].' / Customer: '.$row_main['s_customer'].' (C'.$row_main['s_customer_id'].') / Product: '.$row_main['p_product_code']."'},";
    }
    $data_sales .= ']';  


?>
<div class="form-container bg-lightgray" height="425" width="835" uuid="">
    <form id="form_collection"> 
        <input type="hidden" id="p_id" name="p_id" value="<?=$data[0]['p_id'];?>" />
        <div class="col-lg-12">
            <span class="label-block">Payment For</span>
            <input type="hidden" id="p_sales_id" name="p_sales_id" class="block" placeholder="Select"/>
        </div> 
        <div class="col-lg-12">
            <div class="col-lg-3">
                <span class="label-block">Collection Date</span>
                <input disabled name="p_date" name="p_date" type="text" value="<?=date('m/d/Y',strtotime($data[0]['p_date']));?>" class="field_readonly_block">
            </div>
            <div class="col-lg-3">
                <span class="label-block">OR Number</span>
                <input fname="p_or" name="p_or" type="text" value="<?=$data[0]['p_or'];?>" class="field-input-block-thin">
            </div>

            <div class="col-lg-3">
                <span class="label-block">Collection Amount</span>
                <input name="p_amount" name="p_amount" type="number" value="<?=$data[0]['p_amount'];?>" class="field-input-block-thin">
            </div>
            <div class="col-lg-3">
                <span class="label-block">Rebate</span>
                <input name="p_rebate" name="p_rebate" type="number" value="<?=$data[0]['p_rebate'];?>" class="field-input-block-thin">
            </div>
            <div class="col-lg-6">
                <span class="label-block">Collector</span>
                <input type="hidden" id="p_collector_id" name="p_collector_id" class="block" placeholder="Select"/>
            </div> 
            <div class="col-lg-6">
                <span class="label-block">Branch</span>
                <select disabled name="p_branch_id" id="p_branch_id" class="field_readonly_block">
                <?php
                    echo "<option value=''></option>";
                    $query = "SELECT * FROM branches ORDER BY b_name ASC";
                    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                        if($data[0]['p_branch_id']==$row['b_id']) {
                            echo "<option value='".$row['b_id']."' selected>".$row['b_name']."</option>";
                        } else {
                            echo "<option value='".$row['b_id']."'>".$row['b_name']."</option>";   
                        }
                    }
                ?>
                </select>
            </div>

            <div class="col-lg-12">
                <span class="label-block">Notes</span>
                <textarea name="p_notes" rows="4" cols="50" class="textarea-remarks-input" style="height: 129px"><?=$data[0]['p_notes'];?></textarea>
            </div> 
        </div>        
    </form>
</div>
<div class="form-footer">
    <button class="form-button"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
    <button closewindow="off" class="form-button delete-collection"><i class="fa fa-times" aria-hidden="true"></i> Delete</button>
    <button closewindow="off" class="form-button update-collection"><i class="fa fa-floppy-o" aria-hidden="true"></i> Update</button>
    <button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
</div>
<script>

    $(document).ready(function(){    
        $('#p_collector_id').select().attr('placeholder','<?=$s_collector_name;?>');
        $('#p_collector_id').trigger('change');
        $('#p_collector_id').val("<?=$s_collector_id;?>");
        $('#p_sales_id').select().attr('placeholder','<?=$payment_for_name;?>');
        $('#p_sales_id').trigger('change');
        $('#p_sales_id').val("<?=$payment_for_id;?>");
    });
    

    var numerical = document.querySelectorAll('input[type=number]')
    numerical.forEach(function (input) {
      input.addEventListener('change', function (e) {
        if (e.target.value == '') {
          e.target.value = 0
        }
      })
    })


    $('#p_collector_id').select2({
        data:<?=$data_collector;?>,
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

    $('#p_sales_id').select2({
        data:<?=$data_sales;?>,
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




    /*$('input').on("change", function(e) {
        var discount = parseInt($('input[name="s_discount"]').val());
        var s_tax = parseInt($('input[name="s_tax"]').val());
        var s_othercharges = parseInt($('input[name="s_othercharges"]').val());
        var sold_price = parseInt($('input[name="s_sold_price"]').val());
        var s_total = (sold_price+s_othercharges+s_tax)-discount;
        $('input[name="s_total"]').val(s_total);        
    })*/

</script>