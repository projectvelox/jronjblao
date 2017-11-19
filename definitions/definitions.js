var waisHost = "";

var menu = [
  {
    text: 'Inventory',
    href: '', 
    selectable: false,
    nodes: [
      { text: 'Color',href: 'colors.php', tags: ['0'], state: { selected: true }, },
      { text: 'Inventory Category', href: 'inventorycategory.php', tags: ['0'] },
      { text: 'Brand', href: 'brands.php', tags: ['0'] },
    ],
  },
  {
    text: 'Organization',
    href: '',
    selectable: false,
    nodes: [
      { text: 'Branch',href: 'branches.php',tags: ['0'],},
      { text: 'Department',href: 'departments.php',tags: ['0'],},
      { text: 'Area',href: 'areas.php',tags: ['0'],},
      { text: 'District',href: 'district.php',tags: ['0'],},
      { text: 'Insurance',href: 'insurance.php',tags: ['0'],},
      { text: 'Witness',href: 'witness.php',tags: ['0'],},
    ]
  },
  { text: 'Employee Designation', href: 'designations.php',tags: ['0'] },
  { text: 'Vendor Category', href: 'vendorcategory.php',tags: ['0'] },
  {
    text: 'Payment / Shipping',
    href: '',
    selectable: false,
    nodes: [
      { text: 'Mode of Payment',href: 'paymenttype.php',tags: ['0'],},
      { text: 'Payment Term',href: 'paymentterms.php',tags: ['0'],},
      { text: 'Shipping Method', href: 'shippingmethod.php', tags: ['0'] },
    ]
  },
  { text: 'Chart of Account', href: 'chartofaccounts.php',tags: ['0'] },
  { text: 'Transaction Code', href: 'trancodes.php',tags: ['0'] },
  { text: 'LTO Registration Statuses', href: 'lto.php',tags: ['0'] },
];

$('#tree').treeview({ data:menu, enableLinks:false, onhoverColor:'#ddd' });
$(document).find('.panel-page').load('colors.php');

$('#tree').on('nodeSelected', function(event, data) {
    if(data.href != "") { 
      $(document).find('.panel-page').load(data.href); 
      $(document).find('.definition-new').attr("data-pageurl",data.href); 
      $(document).find('.definition-new').attr("data-pageheader",data.text); 
    }
}); 

$(document).on("click", ".definition-new", function() { 
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> New '+$(this).attr("data-pageheader"),
      'content': 'form_'+$(this).attr("data-pageurl")+"?f=new"
    });
});    

$(document).on("click", ".definition-edit", function() { 
    var id = $(this).data('id');
    var page = $(document).find('.definition-new').attr("data-pageurl");
    var header = $(document).find('.definition-new').attr("data-pageheader");
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> Edit '+header,
      'content': 'form_'+page+"?f=edit&id="+id
    });
});    


$(document).on("click", ".definition-save", function() { 
    var uuid = $(document).find('.form-container').attr("uuid");
    var data = $(document).find('#form-definition').serialize();
    $.ajax({
        type:'post',
        url:'post.php?action=create',
        data: data,
        success:function(result) {
            $('#wf-modal-'+uuid).remove();
            $('#wf-window-'+uuid).remove();
            $(document).find('.panel-page').load($(document).find('.definition-new').attr("data-pageurl"));
        }
    });
});    

$(document).on("click", ".definition-update", function() { 
    var uuid = $(document).find('.form-container').attr("uuid");
    var data = $(document).find('#form-definition').serialize();
    $.ajax({
        type:'post',
        url:'post.php?action=update',
        data: data,
        success:function(result) {
            $('#wf-modal-'+uuid).remove();
            $('#wf-window-'+uuid).remove();
            $(document).find('.panel-page').load($(document).find('.definition-new').attr("data-pageurl"));
        }
    });
});    

$(document).on("click", ".definition-delete", function() { 
    var uuid = $(document).find('.form-container').attr("uuid");
    var data = $(document).find('#form-definition').serialize();
    $.ajax({
        type:'post',
        url:'post.php?action=delete',
        data: data,
        success:function(result) {
          console.log(result);
            $('#wf-modal-'+uuid).remove();
            $('#wf-window-'+uuid).remove();
            $(document).find('.panel-page').load($(document).find('.definition-new').attr("data-pageurl"));
        }
    });
});    



