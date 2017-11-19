var waisHost = "";

$(document).on("click", ".new-vendor", function() { 
    wf_window({
      'title':'<i class="fa fa-th-large" aria-hidden="true"></i> Create Vendor',
      'width':'800',
      'height':'365',
      'content':'create.php'
    });
});    

$(document).on("click", ".view_vendor", function() { 
    wf_window({
      'title':'<i class="fa fa-th-large" aria-hidden="true"></i> View Vendor',
      'width':'800',
      'height':'365',
      'content':'view.php?id='+$(this).data('id')
    });
});    

$(document).on("click", ".edit-vendor", function() { 
    var id = $(document).find("input[name='selected-record']:checked").val();
    if(id != undefined) {  
        wf_window({
          'title':'<i class="fa fa-th-large" aria-hidden="true"></i> Edit Vendor',
          'width':'800',
          'height':'365',
          'content':'edit.php?id='+id
        });
    }
});    

$(document).on("click", ".save-vendor", function() { 
    var form = $('#form_create_vendor');
    $.ajax({
        type:'post',
        url:'post.php?action=create',
        data: form.serialize(),
        success:function(result) {
        	   window.location.href = waisHost+'/wais/vendor/';
        }
    });
});    

$(document).on("click", ".update-vendor", function() { 
    var form = $('#form_edit_vendor');
      $.ajax({
        type:'post',
        url:'post.php?action=update',
        data: form.serialize(),
        success:function(result) {
            window.location.href = waisHost+'/wais/vendor/';
        }
    });      
});    

$(document).on("click", ".delete-vendor", function() { 
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
                        window.location.href = waisHost+'/wais/vendor/';
                    }
                });   
            }
        }
    });
});    

