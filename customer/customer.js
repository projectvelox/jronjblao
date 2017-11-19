var waisHost = "";
var selectedCustomerID = null;

$(document).on("click", ".ledger-customer", function() { 
    var id = $(document).find("input[name='selected-record']:checked").val();
    wf_window({
      'title':'<i class="fa fa-th-large" aria-hidden="true"></i> Customer Ledger',
      'width':'1000',
      'height':'500',
      'content':'../journal/ledger.php?id='+id
    });
});   

$(document).on("click", ".new-customer", function() { 
    wf_window({
      'title':'<i class="fa fa-th-large" aria-hidden="true"></i> Create Customer',
      'width':'700',
      'height':'415',
      'content':'create.php'
    });
});

$(document).on("click", ".view_customer", function() { 
  selectedCustomerID = $(this).data('id');
    wf_window({
      'title':'<i class="fa fa-th-large" aria-hidden="true"></i> View Customer',
      'width':'700',
      'height':'415',
      'content':'view.php?id='+$(this).data('id')
    });
});    

$(document).on("click", ".edit-customer", function() { 
    var id = $(document).find("input[name='selected-record']:checked").val();
    if(id != undefined) {
        wf_window({
        'title':'<i class="fa fa-th-large" aria-hidden="true"></i> Edit Customer',
        'width':'700',
        'height':'415',
        'content':'edit.php?id='+id
        });
    }
});    

$(document).on("click", ".save-customer", function() { 
    var form = $('#form_create_customer');
    $.ajax({
        type:'post',
        url:'post.php?action=create',
        data: form.serialize(),
        success:function(result) {
        	 window.location.href = waisHost+'/wais/customer/';
        }
    });
});    

$(document).on("click", ".update-customer", function() { 
    var form = $('#form_edit_customer');
      $.ajax({
        type:'post',
        url:'post.php?action=update',
        data: form.serialize(),
        success:function(result) {
            window.location.href = waisHost+'/wais/customer/';
        }
    });      
});    

$(document).on("click", ".delete-customer", function() { 
    var id = $(this).data("id");
    bootbox.confirm({ 
        message: "Are you sure you want to delete this record?", 
        title: "<i class='fa fa-th-large' aria-hidden='true'></i> Confirm Action",
        buttons: {
            confirm: { label: 'Yes', className: 'btn-success' },
            cancel: { label: 'No', className: 'btn-success' }
        }, callback: function (result) {
            if(result==true) {
                $.ajax({
                    type:'post',
                    url:'post.php?action=delete&id='+id,
                    success:function(result) {
                        window.location.href = waisHost+'/wais/customer/';
                    }
                });   
            }
        }
    });
});    

$(document).on("click", ".journal_view", function() { 
    var id = $(this).data('id');
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> View Journal',
      'content': '../journal/form_view.php?id='+id,
      'container': 'form_journal_view'
    });
});

$(document).on("click", ".origination_view", function() { 
    var id = $(this).data('id');
    var origination = $(this).data('origination');
    var param = $(this).data('param');
    if(origination=="S") {
          if(param=="C") {
              wf_form({
                'title': '<i class="fa fa-th-large" aria-hidden="true"></i> View Sales - Non Spareparts - Cash',
                'content': '../sales/view_sales_nonsp_cash.php?id='+id,
                'container': ''
              });
          } else {
              wf_form({
                'title': '<i class="fa fa-th-large" aria-hidden="true"></i> View Sales - Non Spareparts - Installment',
                'content': '../sales/view_sales_nonsp_installment.php?id='+id,
                'container': ''
              });
          }
    } else if(origination=="PO") {
          wf_form({
            'title': '<i class="fa fa-th-large" aria-hidden="true"></i> View Purchase Order',
            'content': '../po/form_view.php?f=edit&id='+id
          });
    } else if(origination=="PL") {
          wf_form({
            'title': '<i class="fa fa-th-large" aria-hidden="true"></i> View Pull-Out Log',
            'content': 'form_view.php?id='+id,
            'container': 'container-pullout-edit'
          });
    } else if(origination=="CL") {
          wf_form({
            'title': '<i class="fa fa-th-large" aria-hidden="true"></i> View Collection',
            'content': '../collections/view_collection.php?id='+id,
            'container': 'view_collection'
          });
    } 
});

$(document).on("click", ".create-ci", function() {
  var id = $(document).find("input[name='selected-record']:checked").val();
    wf_window({
      'title':'<i class="fa fa-th-large" aria-hidden="true"></i> Create Credit Investigation',
      'width':'750',
      'height':'550',
      'content':'create_ci.php?id='+id
    });
});

$(document).on("click", ".save-ci", function() {
  var form = $('#form_create_ci');
    $.ajax({
        type:'post',
        url:'post.php?action=create_ci',
        data: form.serialize(),
        success:function(result) {
           window.location.href = waisHost+'/wais/customer/';
        }
    });
});

$(document).on("click", ".view-history-ci", function() {
  var id = $(document).find("input[name='selected-record']:checked").val();
    wf_window({
      'title':'<i class="fa fa-th-large" aria-hidden="true"></i> Credit History',
      'width':'700',
      'height':'415',
      'content':'history_ci.php?id='+selectedCustomerID
    });
});