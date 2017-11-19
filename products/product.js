var waisHost = "";

$(document).on("click", ".new-product", function() { 
    wf_window({
      'title':'<i class="fa fa-th-large" aria-hidden="true"></i> Create Product',
      'width':'700',
      'height':'625',
      'content':'create.php'
    });
});    

$(document).on("click", ".save-product", function() { 
    var form = $('#form_create_product');
    $.ajax({
        type:'post',
        url:'post.php?action=create',
        data: form.serialize(),
        success:function(result) {
            window.location.href = waisHost+'/wais/products/';
        }
    });
});    

$(document).on("click", ".edit-product", function() { 
    var id = $(document).find("input[name='selected-record']:checked").val();
    if( id != undefined) {
      wf_window({
        'title':'<i class="fa fa-th-large" aria-hidden="true"></i> Edit Product',
        'width':'700',
        'height':'625',
        'content':'edit.php?id='+id
      });
    }
});    


$(document).on("click", ".update-product", function() { 
    var form = $('#form_edit_product');
      $.ajax({
        type:'post',
        url:'post.php?action=update',
        data: form.serialize(),
        success:function(result) {
            window.location.href = waisHost+'/wais/products/';
        }
    });      
});   

$(document).on("click", ".delete-product", function() { 
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
                        window.location.href = waisHost+'/wais/products/';
                    }
                });   
            }
        }
    });
});    

$(document).on("click", ".view_product", function() { 
    wf_window({
      'title':'<i class="fa fa-th-large" aria-hidden="true"></i> View Product',
      'width':'700',
      'height':'625',
      'content':'view.php?id='+$(this).data('id')
    });
});    


 



