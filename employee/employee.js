var waisHost = "";

$(document).on("click", ".new-employee", function() { 
    wf_window({
      'title':'<i class="fa fa-th-large" aria-hidden="true"></i> Create Employee',
      'width':'700',
      'height':'567',
      'content':'create.php'
    });
});    

$(document).on("click", ".view_employee", function() { 
    wf_window({
      'title':'<i class="fa fa-th-large" aria-hidden="true"></i> View Employee',
      'width':'700',
      'height':'567',
      'content':'view.php?id='+$(this).data('id')
    });
});    

$(document).on("click", ".edit-employee", function() { 
    var id = $(document).find("input[name='selected-record']:checked").val();
    if(id != undefined) {
        wf_window({
          'title':'<i class="fa fa-th-large" aria-hidden="true"></i> Edit Employee',
          'width':'700',
          'height':'567',
          'content':'edit.php?id='+id
        });
    }
});    

$(document).on("click", ".save-employee", function() { 
    var form = $('#form_create_employee');
    $.ajax({
        type:'post',
        url:'post.php?action=create',
        data: form.serialize(),
        success:function(result) {
        	window.location.href = waisHost+'/wais/employee/';
        }
    });
});    

$(document).on("click", ".update-employee", function() { 
    var form = $('#form_edit_employee');
      $.ajax({
        type:'post',
        url:'post.php?action=update',
        data: form.serialize(),
        success:function(result) {
            window.location.href = waisHost+'/wais/employee/';
        }
    });      
});    

$(document).on("click", ".delete-employee", function() { 
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
                        window.location.href = waisHost+'/wais/employee/';
                    }
                });   
            }
        }
    });
});    


