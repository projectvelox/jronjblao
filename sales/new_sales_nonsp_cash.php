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
                (SELECT p_property_1 FROM products WHERE products.p_id=inventory.p_product_id) AS p_property_1_name,
                (SELECT p_property_2 FROM products WHERE products.p_id=inventory.p_product_id) AS p_property_2_name,
                (SELECT p_property_3 FROM products WHERE products.p_id=inventory.p_product_id) AS p_property_3_name,
                (SELECT p_property_4 FROM products WHERE products.p_id=inventory.p_product_id) AS p_property_4_name,
                (SELECT p_property_5 FROM products WHERE products.p_id=inventory.p_product_id) AS p_property_5_name,
                (SELECT p_property_6 FROM products WHERE products.p_id=inventory.p_product_id) AS p_property_6_name,
                (SELECT p_property_7 FROM products WHERE products.p_id=inventory.p_product_id) AS p_property_7_name,
                (SELECT i_name FROM inv_category WHERE inv_category.i_id=(SELECT p_category_id FROM products WHERE products.p_id=inventory.p_product_id)) AS p_category,
                (SELECT i_name FROM inv_category WHERE inv_category.i_id=((SELECT i_parent_id FROM inv_category WHERE inv_category.i_id=(SELECT p_category_id FROM products WHERE products.p_id=inventory.p_product_id)))) AS p_category_parent,
                (SELECT b_name FROM branches WHERE branches.b_id=inventory.p_branch_id) AS p_branch
                FROM inventory WHERE 
                    p_status = 'I' AND 
                    (SELECT i_name FROM inv_category WHERE inv_category.i_id=((SELECT i_parent_id FROM inv_category WHERE inv_category.i_id=(SELECT p_category_id FROM products WHERE products.p_id=inventory.p_product_id)))) <> 'Spare Parts'
                    AND p_branch_id=".$_SESSION['branch_id'];
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $properties = "";
        if($row['p_property_1_name'] <> "") { $properties .= $row['p_property_1_name'].': '.$row['p_property_1'].' '; }
        if($row['p_property_2_name'] <> "") { $properties .= $row['p_property_2_name'].': '.$row['p_property_2'].' '; }
        if($row['p_property_3_name'] <> "") { $properties .= $row['p_property_3_name'].': '.$row['p_property_3'].' '; }
        if($row['p_property_4_name'] <> "") { $properties .= $row['p_property_4_name'].': '.$row['p_property_4'].' '; }
        if($row['p_property_5_name'] <> "") { $properties .= $row['p_property_5_name'].': '.$row['p_property_5'].' '; }
        if($row['p_property_6_name'] <> "") { $properties .= $row['p_property_6_name'].': '.$row['p_property_6'].' '; }
        if($row['p_property_7_name'] <> "") { $properties .= $row['p_property_7_name'].': '.$row['p_property_7'].' '; }
        $data_product .= "{price:'',id:'".$row['p_id']."',text:'".get_stockcode($row["p_id"]).'-'.$row['p_id'].' / '.$row['p_product_code'].' / '.$row['p_product_name'].' / '.$row['p_reference'].' / '.$properties."'},";
    }
    $data_product .= "]";


?>
<div class="form-container bg-lightgray" height="425" width="835" uuid="">
    <form id="form_sales"> 
        <div class="col-lg-12">
            <span class="label-block">Product</span>
            <input type="hidden" id="s_product_id" name="s_product_id" class="block" placeholder="Select Product"/>
        </div> 
        <div class="col-lg-8">
            <div class="col-lg-3">
                <span class="label-block">Sales Date</span>
                <input disabled name="s_sales_date" name="s_sales_date" type="text" value="<?=date('m/d/Y');?>" class="field_readonly_block">
            </div>
            <div class="col-lg-6">
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
            <div class="col-lg-3">
                <span class="label-block">Sales Type</span>
                <input name="s_sales_type" type="hidden" value="C" class="field-input-block-thin">
                <input disabled name="s_sales_type_text" type="text" value="Cash" class="field_readonly_block">
            </div>         
            <div class="col-lg-6">
                <span class="label-block">Customer</span>
                <input type="hidden" id="s_customer_id" name="s_customer_id" class="block" style="width: 90% !important" placeholder="Select"/>
                <i class="fa fa-plus add_customer" aria-hidden="true"></i>
            </div> 
            <div class="col-lg-6">
                <span class="label-block">Sales Agent</span>
                <input type="hidden" id="s_salesagent_id" name="s_salesagent_id" class="block" placeholder="Select"/>
            </div> 
            <div class="col-lg-12">
                <span class="label-block">Notes</span>
                <textarea name="s_notes" rows="4" cols="50" class="textarea-remarks-input" style="height: 129px"></textarea>
            </div> 
        </div>        
        <div class="col-lg-4">     
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
            <div class="col-lg-7"><span class="label-block align-text">Selling Price</span></div>
            <div class="col-lg-5"><input readonly="readonly" name="s_sold_price" type="text" value="0" class="field_readonly_block"></div>
            <div class="col-lg-7"><span class="label-block align-text">Delivery Date</span></div>
            <div class="col-lg-5"><input name="s_deliverydate" type="text" value="" class="field-input-block-thin"></div>
            <div class="col-lg-7"><span class="label-block align-text">OR Number</span></div>
            <div class="col-lg-5"><input name="s_orno" type="text" value="" class="field-input-block-thin"></div>
            <div class="col-lg-7"><span class="label-block align-text">Tax</span></div>
            <div class="col-lg-5"><input name="s_tax" type="number" value="0" class="field-input-block-thin"></div>
            <div class="col-lg-7"><span class="label-block align-text">Other Charges</span></div>
            <div class="col-lg-5"><input name="s_othercharges" type="number" value="0" class="field-input-block-thin"></div>
            <div class="col-lg-7"><span class="label-block align-text">Discount</span></div>
            <div class="col-lg-5"><input name="s_discount" type="number" value="0" class="field-input-block-thin"></div>
            <div class="col-lg-7"><span class="label-block align-text">Total</span></div>
            <div class="col-lg-5"><input disabled name="s_total" type="text" value="0" class="field_readonly_block"></div>
        </div>
    </form>
</div>
<div class="form-footer">
    <button closewindow="off" class="form-button create-sales-nonsp-cash"><i class="fa fa-plus" aria-hidden="true"></i> Create</button>
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