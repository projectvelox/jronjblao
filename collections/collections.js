var waisHost = "";


$(document).find('.panel-page-whole').load('collections.php');
html = '<button class="toolbar-button"><i class="fa fa-check" aria-hidden="true"></i> Post</button>';
html = '<button class="toolbar-button new_collection_form"><i class="fa fa-plus" aria-hidden="true"></i> New Collection</button>';
$(document).find('.header-buttons').html(html);

$(document).on("click", ".payment-schedule", function() { 
    var uuid = $(this).data("uid");
    var id = $(document).find("input[name='s_id']").val();
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> Payment Schedule',
      'content': '../sales/view_paymentschedule.php?id='+id,
      'container': ''
    });
});

$(document).on("click", ".view-sales", function() { 
    var param = $(this).data('param');
    if(param=="C") {
        wf_form({
          'title': '<i class="fa fa-th-large" aria-hidden="true"></i> View Sales - Non Spareparts - Cash',
          'content': '../sales/view_sales_nonsp_cash.php?id='+$(this).data('id'),
          'container': ''
        });
    } else {
        wf_form({
          'title': '<i class="fa fa-th-large" aria-hidden="true"></i> View Sales - Non Spareparts - Installment',
          'content': '../sales/view_sales_nonsp_installment.php?id='+$(this).data('id'),
          'container': ''
        });
    }
});


$(document).on("click", ".collection-edit", function() { 
    var id = $(this).data('id');
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> Edit Collection',
      'content': 'edit_collection.php?id='+id,
      'container': 'edit_collection'
    });
});

$(document).on("click", ".collection-view", function() { 
    var id = $(this).data('id');
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> View Collection',
      'content': 'view_collection.php?id='+id,
      'container': 'view_collection'
    });
});

$(document).on("click", ".update-collection", function() { 
    $(':disabled').each(function(e) { $(this).removeAttr('disabled'); })
    var form = $('#form_collection');
    var uuid = $(this).data("uid");
    $.ajax({
        type:'post',
        url:'post.php?action=update-collection',
        data: form.serialize(),
        success:function(result) {
            $(document).find('.panel-page-whole').load('collections.php');
            $('#wf-modal-'+uuid).remove();
            $('#wf-window-'+uuid).remove();
        }
    });
});


$(document).on("click", ".view_customer", function() { 
    wf_window({
      'title':'<i class="fa fa-th-large" aria-hidden="true"></i> View Customer',
      'width':'700',
      'height':'498',
      'content':'../customer/view.php?id='+$(this).data('id')
    });
});  

$(document).on("click", ".new_collection_form", function() { 
    var id = $(this).data('id');
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> New Collection',
      'content': 'new_collection.php',
      'container': 'new_collection'
    });
});

$(document).on("click", ".create-collection", function() { 
    $(':disabled').each(function(e) { $(this).removeAttr('disabled'); })
    var form = $('#form_collection');
    var uuid = $(this).data("uid");
    $.ajax({
        type:'post',
        url:'post.php?action=createcollection',
        data: form.serialize(),
        success:function(result) {
            $(document).find('.panel-page-whole').load('collections.php');
            $('#wf-modal-'+uuid).remove();
            $('#wf-window-'+uuid).remove();
        }
    });
});

$(document).on("click", ".delete-collection", function() { 
    var id = $(document).find("input[name='p_id']").val();
    var uuid = $(this).data("uid");
    $.ajax({
        type:'post',
        url:'post.php?action=deletecollection&id='+id,
        success:function(result) {
            $(document).find('.panel-page-whole').load('collections.php');
            $('#wf-modal-'+uuid).remove();
            $('#wf-window-'+uuid).remove();
        }
    });
});



$(document).on("click", ".post-collection", function() { 
    var id = $(this).data('id');
    var amount = $(this).data('amount');
    $.ajax({
        type:'post',
        url:'post.php?action=postcollection&id='+id+'&amount='+amount,
        success:function(result) {
            $(document).find('.panel-page-whole').load('collections.php');
            $('#wf-modal-'+uuid).remove();
            $('#wf-window-'+uuid).remove();
        }
    });
});



$(document).on("click", ".view-payments", function() { 
    var uuid = $(this).data("uid");
    var id = $(document).find("input[name='s_id']").val();
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> Payments',
      'content': '../sales/view_payments.php?id='+id,
      'container': ''
    });
});

