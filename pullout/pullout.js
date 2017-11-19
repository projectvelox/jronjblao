var waisHost = "";

var menu = [
  {
    text: 'Pull Out Log',
    href: '', 
    selectable: false,
    nodes: [
      { text: 'Draft',href: 'drafts.php', tags: 'Draft', state: { selected: true }, },
      { text: 'Verified',href: 'verified.php', tags: 'Verify' },
      { text: 'Received',href: 'received.php', tags: 'Received'},
      { text: 'Redeemed', href: 'redeemed.php', tags: 'Redeemed' },
    ],
  },
  { text: 'Reposessed Units', href: 'repossed.php',tags: ['0'] },


];

$('#tree').treeview({ data:menu, enableLinks:false, onhoverColor:'#ddd' });
$(document).find('.panel-page').load('drafts.php');
html = '<button class="toolbar-button action-verify"><i class="fa fa-check" aria-hidden="true"></i> Verify</button>';
html += '<button class="toolbar-button action-delete"><i class="fa fa-times" aria-hidden="true"></i> Delete</button>';
html += '<button class="toolbar-button new-pullout"><i class="fa fa-plus" aria-hidden="true"></i> New Pull-Out</button>';
$(document).find('.header-buttons').html(html);

$('#tree').on('nodeSelected', function(event, data) {
    var html = "";
    if(data.href != "") { 
        $(document).find('.panel-page').load(data.href); 
        if(data.tags=="Draft") {
              html = '<button class="toolbar-button action-verify"><i class="fa fa-check" aria-hidden="true"></i> Verify</button>';
              html += '<button class="toolbar-button action-delete"><i class="fa fa-times" aria-hidden="true"></i> Cancel Request</button>';
              html += '<button class="toolbar-button new-pullout"><i class="fa fa-plus" aria-hidden="true"></i> New Pull-Out</button>';
        } else if(data.tags=="Verify") {
              html = '<button class="toolbar-button action-received"><i class="fa fa-check" aria-hidden="true"></i> Receive</button>';
        } else if(data.tags=="Received") {
              html = '<button class="toolbar-button action-redeem"><i class="fa fa-check" aria-hidden="true"></i> Redeem</button>';
              html += '<button class="toolbar-button action-repo"><i class="fa fa-check" aria-hidden="true"></i> Repossess</button>';
        } else if(data.tags=="Redeemed") {
              html = '';
        } else {
              html = '';
        }            
        $(document).find('.header-buttons').html(html);
    }
}); 


$(document).on("click", ".sales-view-nonsp", function() { 
    var salestype = $(this).data('salestype');
    var id = $(this).data('id');
    if(salestype=="C") {
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
});
$(document).on("click", ".pullout-edit", function() { 
    var id = $(this).data('id');
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> Edit Pull-Out Log',
      'content': 'form_edit.php?id='+id,
      'container': 'container-pullout-edit'
    });
});

$(document).on("click", ".pullout-view", function() { 
    var id = $(this).data('id');
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> View Pull-Out Log',
      'content': 'form_view.php?id='+id,
      'container': 'container-pullout-edit'
    });
});

$(document).on("click", ".pullout_delete_item", function() { 
    var id = $(document).find("input[name='parts_selected']:checked").val();
    var p_id = $(document).find("input[name='p_id']").val();
    if(id != undefined) {
      $.ajax({
          type:'post',
          url:'post.php?action=deleteitem&id='+id,
          success:function(result) {
              $(document).find('.container-pullout-edit').load('form_edit.php?id='+p_id);
          }
      });  
    } 
}); 

$(document).on("click", ".pullout_add_item", function() { 
    var p_id = $(document).find("input[name='p_id']").val();
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> Add Parts',
      'content': 'form_addparts.php?id='+p_id,
      'container': ''
    });
});

$(document).on("click", ".pullout-saveparts", function() { 
    var p_id = $(document).find("input[name='p_id']").val();
    var form = $('#form-addparts');
    var uuid = $(this).data("uid");
    $.ajax({
        type:'post',
        url:'post.php?action=addparts',
        data: form.serialize(),
        success:function(result) {
            $(document).find('.container-pullout-edit').load('form_edit.php?id='+p_id);            
            $('#wf-modal-'+uuid).remove();
            $('#wf-window-'+uuid).remove();
        }
    });
}); 

$(document).on("click", ".close-editform-pullout", function() { 
    window.location.href = waisHost+'/wais/pullout/';
}); 

$(document).on("click", ".pullout_update", function() { 
    var p_id = $(document).find("input[name='p_id']").val();
    var form = $('#form_editpulloutlog');
    var uuid = $(this).data("uid");
    $.ajax({
        type:'post',
        url:'post.php?action=update',
        data: form.serialize(),
        success:function(result) {
            $('#wf-modal-'+uuid).remove();
            $('#wf-window-'+uuid).remove();
        }
    });
}); 

$(document).on("click", ".action-verify", function() { 
    var id = $(document).find("input[name='selected-record']:checked").val();
    var uuid = $(this).data("uid");    
    if(id != undefined) {
      $.ajax({
          type:'post',
          url:'post.php?action=verify&id='+id,
          success:function(result) {
              $('#wf-modal-'+uuid).remove();
              $('#wf-window-'+uuid).remove();
              $(document).find('.panel-page').load('drafts.php');

          }
      });
    }
}); 

$(document).on("click", ".action-received", function() { 
    var id = $(document).find("input[name='selected-record']:checked").val();
    var uuid = $(this).data("uid");    
    if(id != undefined) {
      $.ajax({
          type:'post',
          url:'post.php?action=received&id='+id,
          success:function(result) {
              $('#wf-modal-'+uuid).remove();
              $('#wf-window-'+uuid).remove();
              $(document).find('.panel-page').load('verified.php');
          }
      });
    }
}); 

$(document).on("click", ".action-redeem", function() { 
    var id = $(document).find("input[name='selected-record']:checked").val();
    var uuid = $(this).data("uid");    
    if(id != undefined) {
      $.ajax({
          type:'post',
          url:'post.php?action=reedem&id='+id,
          success:function(result) {
              $('#wf-modal-'+uuid).remove();
              $('#wf-window-'+uuid).remove();
              $(document).find('.panel-page').load('received.php');
          }
      });
    }
}); 

$(document).on("click", ".action-repo", function() { 
    var id = $(document).find("input[name='selected-record']:checked").val();
    var uuid = $(this).data("uid");    
    if(id != undefined) {
      $.ajax({
          type:'post',
          url:'post.php?action=repo&id='+id,
          success:function(result) {
              $('#wf-modal-'+uuid).remove();
              $('#wf-window-'+uuid).remove();
              $(document).find('.panel-page').load('received.php');
          }
      });
    }
}); 

$(document).on("click", ".action-delete", function() { 
    var id = $(document).find("input[name='selected-record']:checked").val();
    var uuid = $(this).data("uid");    
    if(id != undefined) {
      $.ajax({
          type:'post',
          url:'post.php?action=delete&id='+id,
          success:function(result) {
              $('#wf-modal-'+uuid).remove();
              $('#wf-window-'+uuid).remove();
              $(document).find('.panel-page').load('drafts.php');
          }
      });
    }
}); 


$(document).on("click", ".new-pullout", function() { 
    var id = $(this).data('id');
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> New Pull-Out Log',
      'content': 'form_new.php?id='+id,
      'container': 'container-pullout-new'
    });
});

$(document).on("click", ".pullout_createe ", function() { 
    var product_id = $(document).find("input[name='selected-stock-record']:checked").val();
    var sales_id = 0;
    var uuid = $(this).data("uid");    
    if(product_id != undefined) {
      $.ajax({
          type:'post',
          url:'post.php?action=create&product_id='+product_id+'&sales_id='+sales_id,
          success:function(result) {
              $('#wf-modal-'+uuid).remove();
              $('#wf-window-'+uuid).remove();
              $(document).find('.panel-page').load('drafts.php');
              wf_form({
                'title': '<i class="fa fa-th-large" aria-hidden="true"></i> Edit Pull-Out Log',
                'content': 'form_edit.php?id='+result,
                'container': 'container-pullout-edit'
              });
          }
      });
    }
}); 

