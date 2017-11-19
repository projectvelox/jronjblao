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

    $data_product = "[";
    $query = "SELECT *,
                (SELECT p_category_id FROM products WHERE products.p_id=inventory.p_product_id) AS p_category,
                (SELECT i_name FROM inv_category WHERE inv_category.i_id=(SELECT i_parent_id FROM inv_category WHERE inv_category.i_id=(SELECT p_category_id FROM products WHERE products.p_id=inventory.p_product_id))) AS p_category_name,
                (SELECT p_code FROM products WHERE products.p_id=inventory.p_product_id) AS p_product_code,
                (SELECT p_name FROM products WHERE products.p_id=inventory.p_product_id) AS p_product_name
                FROM inventory
                WHERE 
                    inventory.p_branch_id=".$_SESSION['branch_id']." AND
                    (SELECT i_name FROM inv_category WHERE inv_category.i_id=(SELECT i_parent_id FROM inv_category WHERE inv_category.i_id=(SELECT p_category_id FROM products WHERE products.p_id=inventory.p_product_id))) = 'Spare Parts'";
    $recordset = mysql_query($query) or die('Query failed: ' . mysql_error());
    while ($row = mysql_fetch_array($recordset, MYSQL_ASSOC)) {
        $count = 0;
        $query_count = "SELECT SUM(s_qty) AS s_total_qty 
                            FROM sales_sub 
                            WHERE 
                                (SELECT s_confirm FROM sales_main WHERE sales_main.s_id=sales_sub.s_sales_id) = 'Y'
                                AND s_product_id=".$row['p_id'];
        $recordset_count = mysql_query($query_count) or die('Query failed: ' . mysql_error()); {
            while ($row_count = mysql_fetch_array($recordset_count, MYSQL_ASSOC)) {
                $count = $row['p_qty'] - $row_count['s_total_qty'];
            }
        }
        if($count > 0 ){
            $data_product .= "{max:'".$count."',id:'".$row['p_id']."',text:'".get_stockcode($row["p_id"]).'-'.$row['p_id'].' / '.$row['p_product_code'].' / '.$row['p_product_name'].' / '.$row['p_reference']."'},";
        }
    }
    $data_product .= "]";
?>
<div class="form-container bg-lightgray" height="170" width="700" uuid="">
    <form id="form_add_item"> 
        <div class="col-lg-10">
            <span class="label-block">Sparepart</span>
            <input type="hidden" id="s_product_id" name="s_product_id" class="block" placeholder="Select Product"/>
        </div> 
        <div class="col-lg-2">
            <span class="label-block">Quantity</span>
            <input id="s_qty" name="s_qty" type="number" min="1" max="1" class="field-input-block-thin" value="1">
        </div> 
    </form>
</div>
<div class="form-footer">
    <button closewindow="off" class="form-button save-item"><i class="fa fa-plus" aria-hidden="true"></i> Add</button>
    <button class="form-button"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
</div>
<script>

    $("#s_qty").change(function() {
        var max = parseInt($(this).attr('max'));
        var min = parseInt($(this).attr('min'));
        if ($(this).val() > max) { $(this).val(max); }
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


    $('#s_product_id').on("change", function(e) {
        if($('#s_product_id').val()!="") {
            $("#s_qty").attr('max',$('#s_product_id').select2("data").max);
        }
    })

</script>