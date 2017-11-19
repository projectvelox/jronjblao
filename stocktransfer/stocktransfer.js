var waisHost = "";

var menu = [
  {
    text: 'Stock Transfer',
    href: '', 
    selectable: false,
    nodes: [
      { text: 'Request',href: 'requests.php', tags: 'Request', state: { selected: true }, },
      { text: 'Verified',href: 'verified.php', tags: 'Verify' },
      { text: 'Approved',href: 'approved.php', tags: 'Approved'},
      { text: 'Stocks In Transit', href: 'intransit.php', tags: 'In Transit' },
      { text: 'Transferred', href: 'transffered.php', tags: 'Transffered' },
    ],
  },
  { text: 'Cancelled Requests', href: 'cancelled.php',tags: ['0'] },
];

$('#tree').treeview({ data:menu, enableLinks:false, onhoverColor:'#ddd' });
$(document).find('.panel-page').load('requests.php');
html = '<button class="toolbar-button action-verify"><i class="fa fa-check" aria-hidden="true"></i> Verify</button>';
html += '<button class="toolbar-button cancel-transfer"><i class="fa fa-times" aria-hidden="true"></i> Cancel Request</button>';
html += '<button class="toolbar-button new-transfer"><i class="fa fa-plus" aria-hidden="true"></i> New Transfer</button>';
$(document).find('.header-buttons').html(html);

$('#tree').on('nodeSelected', function(event, data) {
    var html = "";
    if(data.href != "") { 
        $(document).find('.panel-page').load(data.href); 
        if(data.tags=="Request") {
              html = '<button class="toolbar-button action-verify"><i class="fa fa-check" aria-hidden="true"></i> Verify</button>';
              html += '<button class="toolbar-button cancel-transfer"><i class="fa fa-times" aria-hidden="true"></i> Cancel Request</button>';
              html += '<button class="toolbar-button new-transfer"><i class="fa fa-plus" aria-hidden="true"></i> New Transfer</button>';
        } else if(data.tags=="Verify") {
              html = '<button class="toolbar-button action-approve"><i class="fa fa-check" aria-hidden="true"></i> Approve</button>';
        } else if(data.tags=="Approved") {
              html = '<button class="toolbar-button action-intransit"><i class="fa fa-truck" aria-hidden="true"></i> In Transit</button>';
        } else if(data.tags=="In Transit") {
              html = '';
        } else {
              html = '';
        }            
        $(document).find('.header-buttons').html(html);
    }
}); 

$(document).on("click", ".action-verify", function() { 
    var id = $(document).find("input[name='selected-record']:checked").val();
    if(id != undefined) {
      $.ajax({
          type:'post',
          url:'post.php?action=verify&id='+id,
          success:function(result) {
              $(document).find('.panel-page').load('requests.php'); 
              /*if(result != 'success') {
                  wf_message({
                    'title': '<i class="fa fa-th-large" aria-hidden="true"></i> System',
                    'content': result
                  });
              } else {
                  $(document).find('.panel-page').load('requests.php'); 
              }*/
          }
      }); 
    }
}); 

$(document).on("click", ".action-approve", function() { 
    var id = $(document).find("input[name='selected-record']:checked").val();
    if(id != undefined) {
      $.ajax({
          type:'post',
          url:'post.php?action=approved&id='+id,
          success:function(result) {
              $(document).find('.panel-page').load('verified.php'); 
          }
      }); 
    }
}); 

$(document).on("click", ".action-intransit", function() { 
    var id = $(document).find("input[name='selected-record']:checked").val();
    if(id != undefined) {
      $.ajax({
          type:'post',
          url:'post.php?action=intransit&id='+id,
          success:function(result) {
              $(document).find('.panel-page').load('approved.php'); 
          }
      }); 
    }
}); 

$(document).on("click", ".cancel-transfer", function() { 
    var id = $(document).find("input[name='selected-record']:checked").val();
    if(id != undefined) {
      $.ajax({
          type:'post',
          url:'post.php?action=canceltransfer&id='+id,
          success:function(result) {
              $(document).find('.panel-page').load('requests.php'); 
          }
      }); 
    }
});

$(document).on("click", ".new-transfer", function() { 
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> New Transfer Request',
      'content': 'form_new.php',
      'container': 'newrequest'
    });
});  

$(document).on("click", ".transfer_create", function() { 
    var form = $('#form_newtransferrequest');
    var uuid = $(this).data("uid");
    $.ajax({
        type:'post',
        url:'post.php?action=createtransfer',
        data: form.serialize(),
        success:function(result) {
            $(document).find('.panel-page').load('requests.php'); 
            $('#wf-modal-'+uuid).remove();
            $('#wf-window-'+uuid).remove();
            wf_form({
              'title': '<i class="fa fa-th-large" aria-hidden="true"></i> Edit Transfer Request',
              'content': 'form_edit.php?id='+result,
              'container': 'editrequest'
            });
        }
    });
}); 

$(document).on("click", ".transfer-view", function() { 
    var id = $(this).data('id');
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> View Transfer Request',
      'content': 'form_view.php?id='+id,
      'container': 'viewrequest'
    });
}); 

$(document).on("click", ".transfer-view-intransit", function() { 
    var id = $(this).data('id');
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> View Transfer Request',
      'content': 'form_view_intransit.php?id='+id,
      'container': 'viewrequestintransit'
    });
}); 

$(document).on("click", ".transfer-edit", function() { 
    var id = $(this).data('id');
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> Edit Transfer Request',
      'content': 'form_edit.php?id='+id,
      'container': 'editrequest'
    });
}); 

$(document).on("click", ".transfer_add_item", function() { 
    var t_id = $(document).find("input[name='t_id']").val();
    var branch_id = $(document).find("select[name='t_branch_origin_id'] option:selected").val();
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> Add Stock',
      'content': 'form_items.php?f=new&t_id='+t_id+'&branch_id='+branch_id
    });
});   

$(document).on("click", ".transfer-itemsave", function() { 
    var form = $(this).parent().parent().parent().attr('id');
    var uuid = $('#'+form).find(".form-container").attr("uuid");
    var t_id = $(document).find("input[name='t_id']").val();
    $("input[name='selected-stock-record']:checked").each(function(){
          var p_id = $(this).val();
          $.ajax({
              type:'post',
              url:'post.php?action=additem&p_id='+p_id+'&t_id='+t_id,
              success:function(result) {
                  $(document).find('.editrequest').load('form_edit.php?id='+t_id);
                  $('#wf-modal-'+uuid).remove();
                  $('#wf-window-'+uuid).remove();
              }
          });
    });
}); 


$(document).on("click", ".transfer_delete_item", function() { 
    var t_id = $(document).find("input[name='t_id']").val();
    var id = $(document).find("input[name='stock_selected']:checked").val();
    var form = $(this).parent().parent().parent().attr('id');
    var uuid = $('#'+form).find(".form-container").attr("uuid");
    if(id != undefined) {
      $.ajax({
          type:'post',
          url:'post.php?action=deleteitem&id='+id,
          success:function(result) {
              $(document).find('.editrequest').load('form_edit.php?id='+t_id);
          }
      });  
    } 
}); 

$(document).on("click", ".transfer_update", function() { 
    var uuid = $(this).parent().parent().parent().attr('id');
    var uuid = uuid.substring(10,uuid.length);
    var form = $('#form_newtransferrequest');
    $.ajax({
        type:'post',
        url:'post.php?action=updatetransfer',
        data: form.serialize(),
        success:function(result) {
          $('#wf-modal-'+uuid).remove();
          $('#wf-window-'+uuid).remove();
          $(document).find('.panel-page').load('requests.php');
          html = '<button class="toolbar-button action-verify"><i class="fa fa-check" aria-hidden="true"></i> Verify</button>';
          html += '<button class="toolbar-button cancel-transfer"><i class="fa fa-times" aria-hidden="true"></i> Cancel Request</button>';
          html += '<button class="toolbar-button new-transfer"><i class="fa fa-plus" aria-hidden="true"></i> New Transfer</button>';
          $(document).find('.header-buttons').html(html);
        }
    });
});  



$(document).on("click", ".stock-transfer-recieve", function() { 
    $(this).parent().html('<i class="fa fa-check" aria-hidden="true"></i>');
    var t_sub_id = $(this).data('id');
    if(t_sub_id != undefined) {
      $.ajax({
          type:'post',
          url:'post.php?action=recieved&t_sub_id='+t_sub_id,
          success:function(result) {
              $(document).find('.panel-page').load('intransit.php');
          }
      });  
    } 
}); 
  


$(document).on("click", ".transfer-view-recieved", function() { 
    var id = $(this).data('id');
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> View Transfer',
      'content': 'form_view_transferred.php?id='+id,
      'container': 'viewtransfer'
    });
}); 

$(document).on("click", ".close-stocktransfer-editform", function() { 
    var uuid = $(this).parent().parent().parent().attr('id');
    uuid = uuid.substring(10,uuid.length);
    $('#wf-modal-'+uuid).remove();
    $('#wf-window-'+uuid).remove();
}); 



$(document).on("click", ".print_mtf", function(e) { 
    e.preventDefault(); 
    var id = $(this).data('id');
    var url = '../reports/rpt-form-merc-transfer.php?id='+id;
    window.open(url, '_blank');
});









