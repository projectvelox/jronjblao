var waisHost = "";

/*var menu = [
  {
    text: 'Transactions',
    href: '', 
    selectable: false,
    nodes: [
      { text: 'Non Spareparts',href: 'nonspareparts.php', tags: 'nonspareparts', state: { selected: true }, },
      //{ text: 'Spareparts',href: 'spareparts.php', tags: 'spareparts' },
    ],
  },
];*/

//$('#tree').treeview({ data:menu, enableLinks:false, onhoverColor:'#ddd' });
$(document).find('.panel-page-whole').load('contracts.php');
html = '<button class="toolbar-button">Promisorry Note</button>';
html += '<button class="toolbar-button">Chattel Mortgage</button>';
html += '<button class="toolbar-button">Deed of Sale</button>';
html += '<button class="toolbar-button">Waiver</button>';
$(document).find('.header-buttons').html(html);

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

$(document).on("click", ".view_customer", function() { 
    wf_window({
      'title':'<i class="fa fa-th-large" aria-hidden="true"></i> View Customer',
      'width':'700',
      'height':'415',
      'content':'../customer/view.php?id='+$(this).data('id')
    });
});    

$(document).on("click", ".stock-view", function() { 
    var id = $(this).data('id');
    wf_form({
      'title': '<i class="fa fa-th-large" aria-hidden="true"></i> View Stock',
      'content': '../stocks/form_view.php?id='+id
    });
});