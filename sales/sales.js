var waisHost = "";

/*var menu = [
  {
    text: 'Transactions',
    href: '', 
    selectable: false,
    nodes: [
      { text: 'Non Spareparts',href: 'nonspareparts.php', tags: 'nonspareparts', state: { selected: true }, },
      //{ text: 'Spareparts',href: 'spareparts.php', tags: 'spareparts' },
    ],
  },
];*/

//$('#tree').treeview({ data:menu, enableLinks:false, onhoverColor:'#ddd' });
$(document).find('.panel-page-whole').load('nonspareparts.php');
html = '<button class="toolbar-button new_nonspareparts_installment"><i class="fa fa-plus" aria-hidden="true"></i> Installment</button>';
html += '<button class="toolbar-button new_nonspareparts_cash"><i class="fa fa-plus" aria-hidden="true"></i> Cash</button>';
$(document).find('.header-buttons').html(html);

/*$('#tree').on('nodeSelected', function(event, data) {
    var html = "";
    if(data.href != "") { 
        $(document).find('.panel-page').load(data.href); 
        if(data.tags=="nonspareparts") {
            html = '<button class="toolbar-button new_nonspareparts_installment"><i class="fa fa-plus" aria-hidden="true"></i> Installment</button>';
            html += '<button class="toolbar-button new_nonspareparts_cash"><i class="fa fa-plus" aria-hidden="true"></i> Cash</button>';
        } else if(data.tags=="spareparts") {
            html = '<button class="toolbar-button new_spareparts"><i class="fa fa-plus" aria-hidden="true"></i> New Sales</button>';
        } else {
        }            
        $(document).find('.header-buttons').html(html);
    }
}); 
*/
$(document).on("click", ".add_customer", function() { 
    wf_window({
      'title':'<i class="fa fa-th-large" aria-hidden="true"></i> Create Customer',
      'width':'700',
      'height':'415',
      'content':'create_customer.php'
    });
});

$(document).on("click", ".save-customer", function() { 
    var form = $('#form_create_customer');
    var uuid = $(this).data('uid');
    $.ajax({
        type:'post',
        url:'post.php?action=create_customer',
        data: form.serialize(),
        success:function(result) {
            $('#wf-modal-'+uuid).remove();
            $('#wf-window-'+uuid).remove();
            var data = result.split("|");
            $("#s_customer_id").select2('data', {id: data[0], text: data[1] });  
        }
    });
});   

$(document).on("click", ".new_nonspareparts_cash", function() { 
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> New Sales - Non Spareparts - Cash',
      'content': 'new_sales_nonsp_cash.php',
      'container': 'new_sales_nonsp_cash'
    });
});

$(document).on("click", ".new_nonspareparts_installment", function() { 
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> New Sales - Non Spareparts - Installment',
      'content': 'new_sales_nonsp_installment.php',
      'container': 'new_sales_nonsp_installment'
    });
});


$(document).on("click", ".new_spareparts", function() { 
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> New Sales - Spareparts',
      'content': 'new_sales_sp_cash.php',
      'container': 'new_sales_sp_cash'
    });
});

$(document).on("click", ".create-sales-nonsp-cash", function() { 
    $(':disabled').each(function(e) { $(this).removeAttr('disabled'); })
    var form = $('#form_sales');
    var uuid = $(this).data("uid");
    $.ajax({
        type:'post',
        url:'post.php?action=create_sales_nonsp_cash',
        data: form.serialize(),
        success:function(result) {
            $(document).find('.panel-page-whole').load('nonspareparts.php');
            $('#wf-modal-'+uuid).remove();
            $('#wf-window-'+uuid).remove();
        }
    });
});


$(document).on("click", ".create-sales-sp-cash", function() { 
    $(':disabled').each(function(e) { $(this).removeAttr('disabled'); })
    var form = $('#form_sales');
    var uuid = $(this).data("uid");
    $.ajax({
        type:'post',
        url:'post.php?action=create_sales_sp_cash',
        data: form.serialize(),
        success:function(result) {
            $(document).find('.panel-page-whole').load('spareparts.php');
            $('#wf-modal-'+uuid).remove();
            $('#wf-window-'+uuid).remove();
            wf_form({
              'title': '<i class="fa fa-th-large" aria-hidden="true"></i> Edit Sales - Spareparts - Cash',
              'content': 'edit_sales_sp_cash.php?id='+result,
              'container': ''
            });
        }
    });
});

$(document).on("click", ".create-sales-nonsp-installment", function() { 
    $(':disabled').each(function(e) { $(this).removeAttr('disabled'); })
    var form = $('#form_sales');
    var uuid = $(this).data("uid");
    $.ajax({
        type:'post',
        url:'post.php?action=create_sales_nonsp_installment',
        data: form.serialize(),
        success:function(result) {
            $(document).find('.panel-page-whole').load('nonspareparts.php');
            $('#wf-modal-'+uuid).remove();
            $('#wf-window-'+uuid).remove();
        }
    });
});

$(document).on("click", ".sales-view-nonsp", function() { 
    var salestype = $(this).data('salestype');
    var id = $(this).data('id');
    if(salestype=="C") {
        wf_form({
          'title': '<i class="fa fa-th-large" aria-hidden="true"></i> View Sales - Non Spareparts - Cash',
          'content': 'view_sales_nonsp_cash.php?id='+id,
          'container': ''
        });
    } else {
        wf_form({
          'title': '<i class="fa fa-th-large" aria-hidden="true"></i> View Sales - Non Spareparts - Installment',
          'content': 'view_sales_nonsp_installment.php?id='+id,
          'container': ''
        });
    }
});

$(document).on("click", ".sales-view-sp", function() { 
    var salestype = $(this).data('salestype');
    var id = $(this).data('id');
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> View Sales - Spareparts - Cash',
      'content': 'view_sales_sp_cash.php?id='+id,
      'container': 'sales-sp'
    });
});

$(document).on("click", ".sales-edit-nonsp", function() { 
    var salestype = $(this).data('salestype');
    var id = $(this).data('id');
    if(salestype=="C") {
        wf_form({
          'title': '<i class="fa fa-th-large" aria-hidden="true"></i> Edit Sales - Non Spareparts - Cash',
          'content': 'edit_sales_nonsp_cash.php?id='+id,
          'container': ''
        });
    } else {
        wf_form({
          'title': '<i class="fa fa-th-large" aria-hidden="true"></i> Edit Sales - Non Spareparts - Installment',
          'content': 'edit_sales_nonsp_installment.php?id='+id,
          'container': ''
        });
    }
});


$(document).on("click", ".sales-edit-sp", function() { 
    var salestype = $(this).data('salestype');
    var id = $(this).data('id');
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> Edit Sales - Spareparts - Cash',
      'content': 'edit_sales_sp_cash.php?id='+id,
      'container': 'sales-sp'
    });
});

$(document).on("click", ".post-sales", function() { 
    var uuid = $(this).data("uid");
    var id = $(this).data("id");
    $.ajax({
        type:'post',
        url:'post.php?action=postsales&id='+id,
        success:function(result) {
          console.log(result);
            $(document).find('.panel-page-whole').load('nonspareparts.php');
            $('#wf-modal-'+uuid).remove();
            $('#wf-window-'+uuid).remove();
        }
    });
});

$(document).on("click", ".post-sales-sp", function() { 
    var uuid = $(this).data("uid");
    var id = $(this).data("id");
    $.ajax({
        type:'post',
        url:'post.php?action=postsales&id='+id,
        success:function(result) {
            $(document).find('.panel-page-whole').load('spareparts.php');
            $('#wf-modal-'+uuid).remove();
            $('#wf-window-'+uuid).remove();
        }
    });
});

$(document).on("click", ".view_stock", function() { 
    var id = $(this).data('id');
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> View Stock',
      'content': '../stocks/form_view.php?id='+id
    });
});


$(document).on("click", ".view_customer", function() { 
    wf_window({
      'title':'<i class="fa fa-th-large" aria-hidden="true"></i> View Customer',
      'width':'700',
      'height':'415',
      'content':'../customer/view.php?id='+$(this).data('id')
    });
});  

$(document).on("click", ".update-sales-nonsp-cash", function() { 
    $(':disabled').each(function(e) { $(this).removeAttr('disabled'); })
    var form = $('#form_sales');
    var uuid = $(this).data("uid");
    $.ajax({
        type:'post',
        url:'post.php?action=update_sales_nonsp_cash',
        data: form.serialize(),
        success:function(result) {
            $(document).find('.panel-page-whole').load('nonspareparts.php');
            $('#wf-modal-'+uuid).remove();
            $('#wf-window-'+uuid).remove();
        }
    });
});


$(document).on("click", ".update-sales-sp-cash", function() { 
    $(':disabled').each(function(e) { $(this).removeAttr('disabled'); })
    var form = $('#form_sales');
    var uuid = $(this).data("uid");
    $.ajax({
        type:'post',
        url:'post.php?action=update_sales_sp_cash',
        data: form.serialize(),
        success:function(result) {
            $(document).find('.panel-page-whole').load('spareparts.php');
            $('#wf-modal-'+uuid).remove();
            $('#wf-window-'+uuid).remove();
        }
    });
});

$(document).on("click", ".update-sales-nonsp-installment", function() { 
    $(':disabled').each(function(e) { $(this).removeAttr('disabled'); })
    var form = $('#form_sales');
    var uuid = $(this).data("uid");
    $.ajax({
        type:'post',
        url:'post.php?action=update_sales_nonsp_installment',
        data: form.serialize(),
        success:function(result) {
            $(document).find('.panel-page-whole').load('nonspareparts.php');
            $('#wf-modal-'+uuid).remove();
            $('#wf-window-'+uuid).remove();
        }
    });
});

$(document).on("click", ".delete-sales-nonsp-cash", function() { 
    var uuid = $(this).data("uid");
    var s_id = $(document).find("input[name='s_id']").val();
    $.ajax({
        type:'post',
        url:'post.php?action=deletesales&id='+s_id,
        success:function(result) {
            $(document).find('.panel-page-whole').load('nonspareparts.php');
            $('#wf-modal-'+uuid).remove();
            $('#wf-window-'+uuid).remove();
        }
    });
});

$(document).on("click", ".payment-schedule", function() { 
    var uuid = $(this).data("uid");
    var id = $(document).find("input[name='s_id']").val();
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> Payment Schedule',
      'content': 'view_paymentschedule.php?id='+id,
      'container': ''
    });
});

$(document).on("click", ".view-payments", function() { 
    var uuid = $(this).data("uid");
    var id = $(document).find("input[name='s_id']").val();
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> Collections',
      'content': 'view_payments.php?id='+id,
      'container': ''
    });
});



$(document).on("click", ".remove-item", function() { 
    var id = $(document).find("input[name='selected-spareparts']:checked").val();
    var sales_id = $(document).find("input[name='s_id']").val();
    if(id != undefined) {
        $.ajax({
            type:'post',
            url:'post.php?action=deleteitem&id='+id,
            success:function(result) {
                $(document).find('.sales-sp').load('edit_sales_sp_cash.php?id='+sales_id);
            }
        });
    }
});

$(document).on("click", ".close-sales-sp-cash-form", function() { 
    var uuid = $('.sales-sp').parent().attr('id').substring(10,$('.sales-sp').parent().attr('id').lenght);
    $('#wf-modal-'+uuid).remove();
    $('#wf-window-'+uuid).remove();
});

$(document).on("click", ".add-item", function() { 
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> Add Item',
      'content': 'add_item.php',
      'container': ''
    });
});


$(document).on("click", ".save-item", function() { 
    var s_qty = $(document).find('#s_qty').val();
    var s_product_id = $('#s_product_id').val();
    var s_id = $(document).find("input[name='s_id']").val();
    var uuid = $(this).data("uid");
    if(s_product_id != "" && s_qty != "") {
          $.ajax({
              type:'post',
              url:'post.php?action=additem&s_id='+s_id+'&s_product_id='+s_product_id+'&s_qty='+s_qty,
              success:function(result) {
                  $(document).find('.sales-sp').load('edit_sales_sp_cash.php?id='+s_id);
                  $('#wf-modal-'+uuid).remove();
                  $('#wf-window-'+uuid).remove();
              }
          });
    }
});
