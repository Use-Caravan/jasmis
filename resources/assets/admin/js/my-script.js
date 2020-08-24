const ITEM_ACTIVE = 1;
const ITEM_INACTIVE = 0;
const AJAX_SUCCESS = 1;
const AJAX_FAIL = 0;

$(document).ready(function()
{            
    $('body').on('change','.SwitchStatus',function (e, data) {
        var itemkey = $(this).attr('itemkey');        
        var action = $(this).attr('action');        
        var status = ($(this).prop('checked') == true) ?  ITEM_ACTIVE  : ITEM_INACTIVE ;        
        $.ajax({
            url: action,
            type: 'post',
            data : { itemkey : itemkey,status : status },
            success: function(result) {
                if(result.status == AJAX_SUCCESS ){
                    successNotify(result.msg);
                }else{
                    errorNotify(result.msg);
                }
            }
        }); 
    });
    $('body').on('change','.SwitchPopular',function (e, data) {
        var itemkey = $(this).attr('itemkey'); 
        var action = $(this).attr('action'); 
        var status = ($(this).prop('checked') == true) ?  ITEM_ACTIVE  : ITEM_INACTIVE ;
        $.ajax({
            url: action,
            type: 'post',
            data : { itemkey : itemkey,status : status },
            success: function(result) {
                if(result.status == AJAX_SUCCESS ){
                    successNotify(result.msg);
                }else{
                    errorNotify(result.msg);
                }
            }
        }); 
    });
    $('body').on('change','.SwitchQuickbuy',function (e, data) {
        var itemkey = $(this).attr('itemkey'); 
        var action = $(this).attr('action'); 
        var status = ($(this).prop('checked') == true) ?  ITEM_ACTIVE  : ITEM_INACTIVE ;
        $.ajax({
            url: action,
            type: 'post',
            data : { itemkey : itemkey,status : status },
            success: function(result) {
                if(result.status == AJAX_SUCCESS ){
                    successNotify(result.msg);
                }else{
                    errorNotify(result.msg);
                }
            }
        }); 
    });

    $('body').on('change','.SwitchNewitem',function (e, data) {
        var itemkey = $(this).attr('itemkey'); 
        var action = $(this).attr('action'); 
        var status = ($(this).prop('checked') == true) ?  ITEM_ACTIVE  : ITEM_INACTIVE ;
        $.ajax({
            url: action,
            type: 'post',
            data : { itemkey : itemkey,status : status },
            success: function(result) {
                if(result.status == AJAX_SUCCESS ){
                    successNotify(result.msg);
                }else{
                    errorNotify(result.msg);
                }
            }
        }); 
    });

    $('body').on('change','select.approvedStatuss',function (e, data) {
        var itemkey = $(this).attr('id');
        var action = $(this).attr('action');        
        var approved_status = $(this).val();
        $.ajax({
            url: action,
            type: 'post',
            data : { itemkey : itemkey,approved_status : approved_status },
            success: function(result) {
                if(result.status == AJAX_SUCCESS ){
                    successNotify(result.msg);
                }else{
                    errorNotify(result.msg);
                }
            }
        }); 
    });


    $("body").on('click','#deleteConfirm',function() {
        $(this).closest('tr').find('form').submit();
    });

    $("#search-left").keyup(function(){
        var count = 0; 
        try {
            var filter = ( $(this).val() != '' ) ? $(this).val() : '';
            var regex = new RegExp(filter, "gi");
        } catch(e) {
            var filter = ( $(this).val() != '' ) ? '\\'+$(this).val() : ''; 
            var regex = new RegExp(filter, "gi");
        }
        $("#drag-ingredient li").each(function(){
            if ($(this).text().search(regex) < 0) {
                $(this).fadeOut('fast');
            } else {
                $(this).show();
                count++;
            }
        });
    });
    $("#search-right").keyup(function(){
        var count = 0; 
        try {
            var filter = ( $(this).val() != '' ) ? $(this).val() : '';
            var regex = new RegExp(filter, "gi");
        } catch(e) {
            var filter = ( $(this).val() != '' ) ? '\\'+$(this).val() : ''; 
            var regex = new RegExp(filter, "gi");
        }
        $("#drop-ingredient li").each(function(){
            if ($(this).text().search(regex) < 0) {
                $(this).fadeOut('fast');
            } else {
                $(this).show();
                count++;
            }
        });
    });  
    setTimeout(function()
    {
        $('.flash-message').fadeOut('slow');
    },3000);

    $('.nav-tabs li').each(function()
    {
        if($(this).attr('haserror') != '') {
            $('.nav-tabs li').removeClass('active');
            $(this).addClass('active');
            $contentId = $(this).find('a').attr('href');
            $('.tab-content .tab-pane').removeClass('active in');
            $($contentId).addClass('active in');
            return false;
        }
    });  
});
function successNotify(message = '',title = 'Success') {
    title = '';
    iziToast.success({
        id: 'success',
        title: title,
        message: message,
        position: 'bottomRight',
        transitionIn: 'bounceInLeft',
    });
}
function errorNotify(message = '',title = 'Error') {
    title = '';
    iziToast.error({
        id: 'error',
        title: title,
        message: message,
        position: 'bottomRight',
        transitionIn: 'bounceInLeft',
    });
}
function popover() {  
    
    $('body').on('click','[data-toggle=popover]',function()
    {
        $(this).popover({
            html: true,
            trigger:'manual',
            content: function () {
                var targetId = $(this).attr('data-target');
                return $(targetId).html();
            }
        });
        $(this).popover('show');
    });    
}


