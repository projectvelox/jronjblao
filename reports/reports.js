var waisHost = "";

var menu = [
  {
    text: 'Inventory',
    href: '', 
    selectable: false,
    nodes: [
      { text: 'Print Labels',href: 'colors.php', tags: ['0'], state: { selected: true }, },
      { text: 'Price List', href: 'inventorycategory.php', tags: ['0'] },
      { text: 'Inventory Summary', href: 'brands.php', tags: ['0'] },
    ],
  },
  { text: 'Employee Designation', href: 'designations.php',tags: ['0'] },
  { text: 'Vendor Category', href: 'vendorcategory.php',tags: ['0'] },
  { text: 'Chart of Account', href: 'chartofaccounts.php',tags: ['0'] },
  { text: 'Transaction Code', href: 'trancodes.php',tags: ['0'] },
  { text: 'LTO Registration Statuses', href: 'lto.php',tags: ['0'] },
];

$('#tree').treeview({ data:menu, enableLinks:false, onhoverColor:'#ddd' });
//$(document).find('.panel-page').load('preview.php');

$('#tree').on('nodeSelected', function(event, data) {
    if(data.href != "") { 
      $(document).find('.panel-page').load(data.href); 
      $(document).find('.definition-new').attr("data-pageurl",data.href); 
      $(document).find('.definition-new').attr("data-pageheader",data.text); 
    }
}); 


