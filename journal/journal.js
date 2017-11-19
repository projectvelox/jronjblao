var waisHost = "";

var menu = [
  {
    text: 'By Origination',
    href: '', 
    selectable: false,
    nodes: [
      { text: 'Sales',href: 'sales.php', tags: 'sales'},
      { text: 'Collections', href: 'collections.php', tags: 'collections' },
      { text: 'Purchase Orders', href: 'po.php', tags: 'po' },
      { text: 'Pull-Out', href: 'pl.php', tags: 'pl' },
    ],
  },
  { text: 'All Listing',href: 'all.php', tags: 'all', state: { selected: true }, },
];

$('#tree').treeview({ data:menu, enableLinks:false, onhoverColor:'#ddd' });
$(document).find('.panel-page').load('all.php');
html = '<button class="toolbar-button action-post"><i class="fa fa-check" aria-hidden="true"></i> Post</button>';
html += '<button class="toolbar-button"><i class="fa fa-print" aria-hidden="true"></i> Print</button>';
$(document).find('.header-buttons').html(html);

$('#tree').on('nodeSelected', function(event, data) {
    var html = "";
    if(data.href != "") { 
        $(document).find('.panel-page').load(data.href); 
        if(data.tags=="all") {
              html = '<button class="toolbar-button action-post"><i class="fa fa-check" aria-hidden="true"></i> Post</button>';
              html += '<button class="toolbar-button"><i class="fa fa-print" aria-hidden="true"></i> Print</button>';
        } else {
              html = '<button class="toolbar-button action-post"><i class="fa fa-check" aria-hidden="true"></i> Post</button>';
              html += '<button class="toolbar-button"><i class="fa fa-print" aria-hidden="true"></i> Print</button>';
        }            
        $(document).find('.header-buttons').html(html);
    }
}); 

$(document).on("click", ".journal_view", function() { 
    var id = $(this).data('id');
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> View Journal',
      'content': 'form_view.php?id='+id,
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

$(document).on("click", ".action-post", function() { 
    var id = $(document).find("input[name='selected-record']:checked").val();
    var uuid = $(this).data("uid");    
    if(id != undefined) {
      $.ajax({
          type:'post',
          url:'post.php?action=verify&id='+id,
          success:function(result) {
              console.log(result);
              $(document).find('.panel-page').load('pending.php');
          }
      });
    }
}); 