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

    $data_product = "[";
    $query = "SELECT *,
                (SELECT p_code FROM products WHERE products.p_id=inventory.p_product_id) AS p_product_code,
                (SELECT p_name FROM products WHERE products.p_id=inventory.p_product_id) AS p_product_name,
                (SELECT i_name FROM inv_category WHERE inv_category.i_id=(SELECT p_category_id FROM products WHERE products.p_id=inventory.p_product_id)) AS p_category,
                (SELECT i_name FROM inv_category WHERE inv_category.i_id=((SELECT i_parent_id FROM inv_category WHERE inv_category.i_id=(SELECT p_category_id FROM products WHERE products.p_id=inventory.p_product_id)))) AS p_category_parent,
                (SELECT b_name FROM branches WHERE branches.b_id=inventory.p_branch_id) AS p_branch
                FROM inventory WHERE 
                    p_status = 'I' AND 
                    (SELECT i_name FROM inv_category WHERE inv_category.i_id=((SELECT i_parent_id FROM inv_category WHERE inv_category.i_id=(SELECT p_category_id FROM products WHERE products.p_id=inventory.p_product_id)))) <> 'Spare Parts'
                    AND p_branch_id=".$_SESSION['branch_id'];
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $data_product .= "{price:'123',id:'".$row['p_id']."',text:'S".$row['p_id'].' / '.$row['p_product_code'].' / '.$row['p_product_name'].' / '.$row['p_reference']."'},";
    }
    $data_product .= "]";
?>
<div class="form-container bg-lightgray" height="300" width="600" uuid="">
    <form id="form_sales"> 
        <div class="col-lg-12">
            <div class="col-lg-3">
                <span class="label-block">Sales Date</span>
                <input disabled name="s_sales_date" name="s_sales_date" type="text" value="<?=date('m/d/Y');?>" class="field_readonly_block">
            </div>
            <div class="col-lg-5">
                <span class="label-block">Branch</span>
                <select disabled name="s_branch_id" id="s_branch_id" class="field_readonly_block">
                <?php
                    echo "<option value=''></option>";
                    $query = "SELECT * FROM branches ORDER BY b_name ASC";
                    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
                    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
                        if($_SESSION['branch_id']==$row['b_id']) {
                            echo "<option value='".$row['b_id']."' selected>".$row['b_name']."</option>";
                        } else {
                            echo "<option value='".$row['b_id']."'>".$row['b_name']."</option>";   
                        }
                    }
                ?>
                </select>
            </div> 
            <div class="col-lg-4">
                <span class="label-block">OR Number</span>
                <input name="s_orno" type="text" value="" class="field-input-block-thin">            
            </div>
            <div class="col-lg-12">
                <span class="label-block">Notes</span>
                <textarea name="s_notes" rows="4" cols="50" class="textarea-remarks-input" style="height: 50px"></textarea>
            </div> 
            <div class="col-lg-6">
                <span class="label-block">Customer</span>
                <input type="hidden" id="s_customer_id" name="s_customer_id" class="block" placeholder="Select"/>
            </div> 
            <div class="col-lg-6">
                <span class="label-block">Sales Agent</span>
                <input type="hidden" id="s_salesagent_id" name="s_salesagent_id" class="block" placeholder="Select"/>
            </div> 
        </div>        
    </form>
</div>
<div class="form-footer">
    <button closewindow="off" class="form-button create-sales-sp-cash"><i class="fa fa-plus" aria-hidden="true"></i> Create</button>
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

    $('input[name="s_sales_date"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
    $('input[name="s_firstdownpayment_date"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
    $('input[name="s_seconddownpayment_date"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
    $('input[name="s_firstmonthlydue_date"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });
    $('input[name="s_deliverydate"]').daterangepicker({ singleDatePicker: true, showDropdowns: true });

</script>