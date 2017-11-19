$(document).on("click", ".wf-close-window", function() { 
    if($("#s_customer_id").length){ $(document).find('#s_customer_id').data('select2').destroy(); }            
    if($("#s_salesagent_id").length){ $(document).find('#s_salesagent_id').data('select2').destroy(); }            
    if($("#s_product_id").length){ $(document).find('#s_product_id').data('select2').destroy(); }            
    if($("#p_collector_id").length){  $(document).find('#p_collector_id').data('select2').destroy(); }            
    if($("#p_sales_id").length){  $(document).find('#p_sales_id').data('select2').destroy(); }            
    
    if($(this).attr('closewindow')=="off") {
        return false;
    } else {
        $('#wf-modal-'+$(this).data('uid')).remove();
        $('#wf-window-'+$(this).data('uid')).remove();
    }
});

function wf_window(wf_options) {
    if(wf_options.window != "") {
        $('#wf-modal-'+wf_options.window).remove();
        $('#wf-window-'+wf_options.window).remove();
    }
    if(wf_options.margin!="") { var divStyle = 'padding: 0px;' } else { var divStyle = 'padding: 0px;' } 
    var d = new Date().getTime();
    if(window.performance && typeof window.performance.now === "function") { d += performance.now(); }
    var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = (d + Math.random()*16)%16 | 0;
        d = Math.floor(d/16);
        return (c=='x' ? r : (r&0x3|0x8)).toString(16);
    });
    $('<div id="wf-modal-'+uuid+'" class="wf-modal"></div>').appendTo('body'); 
    $('<div id="wf-window-'+uuid+'" class="wf-window"></div>').appendTo('body'); 
    $('#wf-modal-'+uuid).show();
    $('#wf-window-'+uuid).show();
    $('#wf-window-'+uuid).draggable({ cancel: '.wf-window-viewport, .wf-window-close-icon', containment: "" });    
    $('#wf-window-'+uuid).css('width',wf_options.width);
    $('#wf-window-'+uuid).css('height',wf_options.height);
    $('#wf-window-'+uuid).css('top','50%');
    $('#wf-window-'+uuid).css('left','50%');
    $('#wf-window-'+uuid).css('margin-left','-'+($('#wf-window-'+uuid).width()/2)+'px');
    $('#wf-window-'+uuid).css('margin-top','-'+($('#wf-window-'+uuid).height()/2)+'px');
    $('#wf-window-'+uuid).append('<div class="wf-window-heading"><span class="wf-window-title wf-noselect"></span><span data-uid="'+uuid+'" class="wf-window-close-icon wf-noselect wf-close-window">&#10006;</span></div><div class="wf-window-viewport" style="'+divStyle+'"></div>'); 
    $('#wf-window-'+uuid).find('.wf-window-title').html(wf_options.title);   
    $('#wf-window-'+uuid).find('.wf-window-viewport').load(wf_options.content, function() {
        $('#wf-window-'+uuid).find(":button").attr('data-uid',uuid);
        $('#wf-window-'+uuid).find(":button").addClass('wf-close-window');
    });   
}

function wf_form(wf_options) {
    /*if(wf_options.window != "") {
        $('#wf-modal-'+wf_options.window).remove();
        $('#wf-window-'+wf_options.window).remove();
    }*/
    if(wf_options.margin!="") { var divStyle = 'padding: 0px;' } else { var divStyle = 'padding: 0px;' } 
    var d = new Date().getTime();
    if(window.performance && typeof window.performance.now === "function") { d += performance.now(); }
    var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = (d + Math.random()*16)%16 | 0;
        d = Math.floor(d/16);
        return (c=='x' ? r : (r&0x3|0x8)).toString(16);
    });
    $('<div id="wf-modal-'+uuid+'" class="wf-modal"></div>').appendTo('body'); 
    $('<div id="wf-window-'+uuid+'" class="wf-window"></div>').appendTo('body'); 
    $('#wf-window-'+uuid).draggable({ cancel: '.wf-window-viewport, .wf-window-close-icon', containment: "" });    
    $('#wf-window-'+uuid).append('<div class="wf-window-heading"><span class="wf-window-title wf-noselect"></span><span data-uid="'+uuid+'" class="wf-window-close-icon wf-noselect wf-close-window">&#10006;</span></div><div class="wf-window-viewport '+wf_options.container+'" style="'+divStyle+'"></div>'); 
    $('#wf-window-'+uuid).find('.wf-window-title').html(wf_options.title);   
    $('#wf-window-'+uuid).find('.wf-window-viewport').load(wf_options.content, function() {
        $('#wf-window-'+uuid).find(":button").attr('data-uid',uuid);
        $('#wf-window-'+uuid).find(":button").addClass('wf-close-window');
        var h = $('#wf-window-'+uuid).find(".form-container").attr("height");
        var w = $('#wf-window-'+uuid).find(".form-container").attr("width");
        $('#wf-window-'+uuid).css('width',w);
        $('#wf-window-'+uuid).css('height',h);
        $('#wf-window-'+uuid).css('top','50%');
        $('#wf-window-'+uuid).css('left','50%');
        $('#wf-window-'+uuid).css('margin-left','-'+($('#wf-window-'+uuid).width()/2)+'px');
        $('#wf-window-'+uuid).css('margin-top','-'+($('#wf-window-'+uuid).height()/2)+'px');
        $('#wf-window-'+uuid).find(".form-container").attr("uuid",uuid);
        $('#wf-modal-'+uuid).show();
        $('#wf-window-'+uuid).show();
    });   
}

function wf_message(wf_options) {
    if(wf_options.window != "") {
        $('#wf-modal-'+wf_options.window).remove();
        $('#wf-window-'+wf_options.window).remove();
    }
    if(wf_options.margin!="") { var divStyle = 'padding: 0px;' } else { var divStyle = 'padding: 0px;' } 
    var d = new Date().getTime();
    if(window.performance && typeof window.performance.now === "function") { d += performance.now(); }
    var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = (d + Math.random()*16)%16 | 0;
        d = Math.floor(d/16);
        return (c=='x' ? r : (r&0x3|0x8)).toString(16);
    });
    $('<div id="wf-modal-'+uuid+'" class="wf-modal"></div>').appendTo('body'); 
    $('<div id="wf-window-'+uuid+'" class="wf-window" style="background: #fff  !important"></div>').appendTo('body'); 
    $('#wf-modal-'+uuid).show();
    $('#wf-window-'+uuid).show();
    $('#wf-window-'+uuid).draggable({ cancel: '.wf-window-viewport, .wf-window-close-icon', containment: "" });    
    $('#wf-window-'+uuid).css('width','400px');
    $('#wf-window-'+uuid).css('height','100px');
    $('#wf-window-'+uuid).css('top','50%');
    $('#wf-window-'+uuid).css('left','50%');
    $('#wf-window-'+uuid).css('margin-left','-'+($('#wf-window-'+uuid).width()/2)+'px');
    $('#wf-window-'+uuid).css('margin-top','-'+($('#wf-window-'+uuid).height()/2)+'px');
    $('#wf-window-'+uuid).append('<div class="wf-window-heading" style="background: #fff  !important"><span class="wf-window-title wf-noselect"></span><span data-uid="'+uuid+'" class="wf-window-close-icon wf-noselect wf-close-window">&#10006;</span></div><div class="wf-window-viewport" style="background: #fff !important"></div>'); 
    //$('#wf-window-'+uuid).find('.wf-window-title').html(wf_options.title);   
    $('#wf-window-'+uuid).find('.wf-window-viewport').html(wf_options.content);
    /*, function() {
        $('#wf-window-'+uuid).find(":button").attr('data-uid',uuid);
        $('#wf-window-'+uuid).find(":button").addClass('wf-close-window');
    });   
    */
}