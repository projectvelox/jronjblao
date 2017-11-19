var waisHost = "";

$(document).on("click", ".action-damage", function() { 
    var id = $(document).find("input[name='selected-stock']:checked").val();
    var page = $(document).find('.header-buttons').attr('data-page');
    if(id != undefined) {
      $.ajax({
          type:'post',
          url:'post.php?action=damage&id='+id,
          success:function(result) {
            console.log(result);
            if(page=='Warehouse') {
                $(document).find('.panel-page').load('warehouse.php?f='+$(document).find('.header-buttons').attr('data-filter')); 
            } else {
                $(document).find('.panel-page').load('store.php?f='+$(document).find('.header-buttons').attr('data-filter')); 
            }
          }
      }); 
    }
});  

$(document).on("click", ".action-lost", function() { 
    var id = $(document).find("input[name='selected-stock']:checked").val();
    var page = $(document).find('.header-buttons').attr('data-page');
    if(id != undefined) {
      $.ajax({
          type:'post',
          url:'post.php?action=lost&id='+id,
          success:function(result) {
            if(page=='Warehouse') {
                $(document).find('.panel-page').load('warehouse.php?f='+$(document).find('.header-buttons').attr('data-filter')); 
            } else {
                $(document).find('.panel-page').load('store.php?f='+$(document).find('.header-buttons').attr('data-filter')); 
            }
          }
      }); 
    }
});  

$(document).on("click", ".action-return", function() { 
    var id = $(document).find("input[name='selected-stock']:checked").val();
    var page = $(document).find('.header-buttons').attr('data-page');
    if(id != undefined) {
      $.ajax({
          type:'post',
          url:'post.php?action=return&id='+id,
          success:function(result) {
            if(page=='Warehouse') {
                $(document).find('.panel-page').load('warehouse.php?f='+$(document).find('.header-buttons').attr('data-filter')); 
            } else {
                $(document).find('.panel-page').load('store.php?f='+$(document).find('.header-buttons').attr('data-filter')); 
            }
          }
      }); 
    }
});

$(document).on("click", ".action-recieved", function() { 
    var id = $(document).find("input[name='selected-stock']:checked").val();
    var page = $(document).find('.header-buttons').attr('data-page');
    if(id != undefined) {
      $.ajax({
          type:'post',
          url:'post.php?action=recieved&id='+id,
          success:function(result) {
              $(document).find('.panel-page').load('returned.php'); 
          }
      }); 
    }
});

$(document).on("click", ".stock-view", function() { 
    var id = $(this).data('id');
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> View Stock',
      'content': 'form_view.php?id='+id
    });
});


$(document).on("click", ".stock-edit", function() { 
    var id = $(this).data('id');
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> Edit Stock',
      'content': 'form_edit.php?id='+id
    });
});

$(document).on("click", ".stock_update", function() { 
    var uuid = $(this).data("uid");
    var data = $(document).find('#form_inventory').serialize();
    var page = $(document).find('.header-buttons').attr('data-page');
    $.ajax({
        type:'post',
        url:'post.php?action=update',
        data: data,
        success:function(result) {
            if(page=='Warehouse') {
                $(document).find('.panel-page').load('warehouse.php?f='+$(document).find('.header-buttons').attr('data-filter')); 
            } else {
                $(document).find('.panel-page').load('store.php?f='+$(document).find('.header-buttons').attr('data-filter')); 
            }
            $('#wf-modal-'+uuid).remove();
            $('#wf-window-'+uuid).remove();
        }
    });
});   

