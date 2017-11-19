var waisHost = "";

function formatCurrency(total) {
    var neg = false;
    if(total < 0) {
        neg = true;
        total = Math.abs(total);
    }
    return (neg ? "-" : '') + parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
}

var menu = [
  {
    text: 'Purchase Orders',
    href: '', 
    selectable: false,
    nodes: [
      { text: 'Draft',href: 'drafts.php', tags: 'Verify', state: { selected: true }, },
      { text: 'Verified', href: 'verified.php', tags: 'Approve' },
      { text: 'Approved', href: 'approved.php', tags: 'Recieved' },
      { text: 'Recieved', href: 'recieved.php', tags: '' },
    ],
  },
];

$('#tree').treeview({ data:menu, enableLinks:false, onhoverColor:'#ddd' });
$(document).find('.panel-page').load('drafts.php');
var html = '<button class="toolbar-button action-verify"><i class="fa fa-plus" aria-hidden="true"></i> Verify</button>';
html += '<button class="toolbar-button new-po"><i class="fa fa-plus" aria-hidden="true"></i> New PO</button>';
$(document).find('.header-buttons').html(html);

$('#tree').on('nodeSelected', function(event, data) {
    var html = "";
    if(data.href != "") { 
        $(document).find('.panel-page').load(data.href); 
        if(data.tags=="Verify") {
              html = '<button data-action="" class="toolbar-button action-verify"><i class="fa fa-plus" aria-hidden="true"></i> Verify</button>';
              html += '<button class="toolbar-button new-po"><i class="fa fa-plus" aria-hidden="true"></i> New PO</button>';
        } else if(data.tags=="Approve") {
              html = '<button class="toolbar-button action-approve"><i class="fa fa-plus" aria-hidden="true"></i> Approve</button>';
              html += '<button class="toolbar-button action-disapproved"><i class="fa fa-times" aria-hidden="true"></i> Disapproved</button>';
              html += '<button class="toolbar-button new-po"><i class="fa fa-plus" aria-hidden="true"></i> New PO</button>';
        } else if(data.tags=="Recieved") {
              html += '<button class="toolbar-button new-po"><i class="fa fa-plus" aria-hidden="true"></i> New PO</button>';
        }
        $(document).find('.header-buttons').html(html);
    }
}); 

$(document).on("click", ".action-verify", function() { 
    var id = $(document).find("input[name='selected-po']:checked").val();
    if(id != undefined) {
      $.ajax({
          type:'post',
          url:'post.php?action=decision&id='+id+'&decision=V',
          success:function(result) {
              $(document).find('.panel-page').load('drafts.php'); 
          }
      }); 
    }
});  

$(document).on("change", ".p_charge_tax,.p_charge_shipping,.p_charge_others,.p_discount", function() { 
    var totalcost_products = parseInt($(document).find("input[name='totalcost_products']").val());
    var p_charge_tax = parseInt($(document).find("input[name='p_charge_tax']").val());
    var p_charge_shipping = parseInt($(document).find("input[name='p_charge_shipping']").val());
    var p_charge_others = parseInt($(document).find("input[name='p_charge_others']").val());
    var p_discount = parseInt($(document).find("input[name='p_discount']").val());
    var totalamount = totalcost_products + ((p_charge_tax+p_charge_shipping+p_charge_others)-p_discount);
    $(document).find("input[name='totalamount']").val(formatCurrency(totalamount));
});  



$(document).on("click", ".action-recieved", function() { 
    var id = $(document).find("input[name='selected-po']:checked").val();
    if(id != undefined) {
      $.ajax({
          type:'post',
          url:'post.php?action=decision&id='+id+'&decision=R',
          success:function(result) {
              $(document).find('.panel-page').load('approved.php'); 
          }
      }); 
    }
});  

$(document).on("click", ".close-poform", function() { 
    var uuid = $(this).parent().parent().parent().attr('id');
    var uuid = uuid.substring(10,uuid.length);
    $('#wf-modal-'+uuid).remove();
    $('#wf-window-'+uuid).remove();
}); 


$(document).on("click", ".action-approve", function() { 
    var id = $(document).find("input[name='selected-po']:checked").val();
    if(id != undefined) {
      $.ajax({
          type:'post',
          url:'post.php?action=decision&id='+id+'&decision=A',
          success:function(result) {
              $(document).find('.panel-page').load('verified.php'); 
          }
      }); 
    }
}); 


$(document).on("click", ".action-disapproved", function() { 
    var id = $(document).find("input[name='selected-po']:checked").val();
    if(id != undefined) {
      $.ajax({
          type:'post',
          url:'post.php?action=decision&id='+id+'&decision=D',
          success:function(result) {
              $(document).find('.panel-page').load('approved.php'); 
          }
      }); 
    }
});  

$(document).on("click", ".po-view", function() { 
    var id = $(this).data('id');
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> View Purchase Order',
      'content': 'form_view.php?f=edit&id='+id
    });
});    

$(document).on("click", ".po-edit", function() { 
    var id = $(this).data('id');
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> Edit Purchase Order',
      'content': 'form_edit.php?f=edit&id='+id,
      'container': 'poform'
    });
});    

$(document).on("click", ".po_add_item", function() { 
    var p_id = $(document).find("input[name='p_id']").val();
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> Add Items',
      'content': 'form_items.php?f=new&p_id='+p_id
    });
});    

$(document).on("click", ".po-itemsave", function() { 
    var form = $(this).parent().parent().parent().attr('id');
    var uuid = $('#'+form).find(".form-container").attr("uuid");
    var data = $(document).find('#form-po-additem').serialize();
    var p_id = $(document).find("input[name='p_id']").val();
    $.ajax({
        type:'post',
        url:'post.php?action=addpoitem',
        data: data,
        success:function(result) {
            $(document).find('.poform').load('form_edit.php?f=edit&id='+p_id);
            $('#wf-modal-'+uuid).remove();
            $('#wf-window-'+uuid).remove();
        }
    });
});   

$(document).on("click", ".po_delete_item", function() { 
    var form = $(this).parent().parent().parent().attr('id');
    var uuid = $('#'+form).find(".form-container").attr("uuid");
    var p_id = $(document).find("input[name='p_id']").val();
    var id = $(document).find("input[name='record_selected']:checked").val();
    var p_id = $(document).find("input[name='p_id']").val();
    if(id != undefined) {
      $.ajax({
          type:'post',
          url:'post.php?action=deleteitem&id='+id,
          success:function(result) {
              $(document).find('.poform').load('form_edit.php?f=edit&id='+p_id);
          }
      });  
    } 
});   

$(document).on("click", ".po_edit_item", function() { 
    var item = $(document).find("input[name='record_selected']:checked").val();
    var p_id = $(document).find("input[name='p_id']").val();
    if(item != undefined) {
      wf_form({ 
        'title': '<i class="fa fa-th-large" aria-hidden="true"></i> Add Items',
        'content': 'form_items.php?f=edit&id='+p_id+'&item='+item
      });
    }
});

$(document).on("click", ".po-itemupdate", function() { 
    var form = $(this).parent().parent().parent().attr('id');
    var uuid = $('#'+form).find(".form-container").attr("uuid");
    var data = $(document).find('#form-po-additem').serialize();
    var p_id = $(document).find("input[name='p_id']").val();
    $.ajax({
        type:'post',
        url:'post.php?action=update',
        data: data,
        success:function(result) {
            $(document).find('.poform').load('form_edit.php?f=edit&id='+p_id);
            $('#wf-modal-'+uuid).remove();
            $('#wf-window-'+uuid).remove();
        }
    });
});   

$(document).on("click", ".po_save", function() { 
    var form = $('#form_po');
    $.ajax({
        type:'post',
        url:'post.php?action=updatepo',
        data: form.serialize(),
        success:function(result) {
            window.location.href = waisHost+'/wais/po/';
        }
    });
}); 

$(document).on("click", ".po_delete", function() { 
    var form = $('#form_po');
    $.ajax({
        type:'post',
        url:'post.php?action=deletepo',
        data: form.serialize(),
        success:function(result) {
            window.location.href = waisHost+'/wais/po/';
        }
    });
});   


$(document).on("click", ".new-po", function() { 
    var id = $(this).data('id');
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> New Purchase Order',
      'content': 'form_new.php',
      'container': 'poform'
    });
});  

$(document).on("click", ".po_create_save", function() { 
    var uuid = $(this).data("uid");
    var form = $('#form_po');
    $.ajax({
        type:'post',
        url:'post.php?action=createpo',
        data: form.serialize(),
        success:function(result) {
          $('#wf-modal-'+uuid).remove();
          $('#wf-window-'+uuid).remove();
          wf_form({
              'title': '<i class="fa fa-th-large" aria-hidden="true"></i> Edit Purchase Order',
              'content': 'form_edit.php?f=edit&id='+result,
              'container': 'poform'
          });
        }
    });
});  

$(document).on("click", ".form-button-recieved", function() { 
    id = $(this).data('id');
    $(this).parent().html('<i class="fa fa-check" aria-hidden="true"></i>');
    $.ajax({
        type:'post',
        url:'post.php?action=markrecieved&id='+id,
        success:function(result) {
          console.log(result);
        }
    });
});  

