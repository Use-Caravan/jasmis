const ITEM_ACTIVE = 1;
const ITEM_INACTIVE = 0;
const AJAX_SUCCESS = 1;
const AJAX_FAIL = 0;
const PHONE_NUMBER_EXISTS = 2;
const EMAIL_EXISTS = 3;
const OTP_UNVERIFIED = 2;



/** API Response code and message */
const HTTP_NOT_FOUND = 404;
const HTTP_SUCCESS = 200;
const HTTP_UNPROCESSABLE = 422;
const EXPECTATION_FAILED = 417;
const OTP_VERFICATION_REQUIRED = 419;
const UNAUTHORISED = 401;    
const FORBIDDEN_UNAUTHORISED = 403;


$(document).ready(function()
{    
    $('.loginModel').click(function(){        
        $('#login_modal').modal('toggle');
    });
    $('.discountModel').click(function(){
        $('#discount_modal').modal('toggle');
    });

    /* function moneyFormat()
    {
        $('meta[name="_currencySymbol"]').attr('content'),
        $('meta[name="_currencyPosition"]').attr('content'),  
    } */
    
    $('#register-form').on('submit',function (e) {
        e.preventDefault();        
        var action = $(this).attr('action');
        $.ajax({ 
            url: action,
            type: 'post',            
            data : $(this).serializeArray(),
            success: function(result) {
                if(result.status == AJAX_SUCCESS ){
                    successNotify(result.msg);
                    $('#otp_temp_key').val(result.data.otp_temp_key);
                    $('#enter_otp_user_key').val(result.data.user_key); 
                    $('#sign-up,#otp-modal').modal('toggle');
                    // setTimeout(function() {location.reload()}, 2000);
                }else if(result.status == AJAX_FAIL){
                    //errorNotify(result.msg);
                    $('#send-otp').html(result.msg);
                    $('#confirmation_verify_otp_user_key').val(result.data);                
                    $('#sign-up,#otp_resend_modal').modal('toggle');                    
                }
                else if(result.status == EMAIL_EXISTS){
                    errorNotify(result.msg);
                }
                else if(result.status == PHONE_NUMBER_EXISTS){
                    errorNotify(result.msg);
                }
            }
        }); 
    });

    $('#otp-form').on('submit',function (e) {        
        e.preventDefault();        
        var action = $(this).attr('action');        
        $.ajax({ 
            url: action,
            type: 'post',
            data : $(this).serializeArray(),
            success: function(result) {
                if(result.status == AJAX_SUCCESS ){
                    successNotify(result.msg);
                    setTimeout(function() {location.reload()}, 2000);
                }else{
                    errorNotify(result.msg);                    
                }
            }
        }); 
    });

     $('#otp-resend-form').on('submit',function (e) {        
        e.preventDefault();
        var action = $(this).attr('action');        
        $.ajax({ 
            url: action,
            type: 'post',            
            data : $(this).serializeArray(),
            success: function(result) {
                if(result.status == AJAX_SUCCESS ){
                    successNotify(result.msg);
                    $('#otp_temp_key').val(result.data.otp_temp_key);
                    $('#enter_otp_user_key').val(result.data.user_key);
                    $('#otp_resend_modal,#otp-modal').modal('toggle');
                }else{
                    errorNotify(result.msg);                    
                }
            }
        }); 
    });


    $('#login-form').on('submit',function (e) {        
        e.preventDefault();        
        var action = $(this).attr('action');        
        $.ajax({ 
            url: action,
            type: 'post',            
            data : $(this).serializeArray(),
            success: function(result) {
                if(result.status == AJAX_SUCCESS ){
                    successNotify(result.msg);
                    setTimeout(function() {location.reload()}, 1000);
                    
                }else if(result.status == AJAX_FAIL){
                    errorNotify(result.msg);
                    //$('#login_modal,#otp_resend_modal').modal('toggle');                    
                }
                else if(result.status == OTP_UNVERIFIED){
                    $('#send-otp').html(result.msg);
                    $('#confirmation_verify_otp_user_key').val(result.data.user_key); 
                    $('#login_modal,#otp_resend_modal').modal('toggle');                    
                }
            }
        }); 
    });

     $('.forgot-submit').on('click',function (e) {
        e.preventDefault();   
        var form = $('#forgot-form');
        var action = form.attr('action');
        $.ajax({
            url: action,
            type: 'post',
            data : form.serializeArray(),
            success: function(result) {   
                if(result.status === AJAX_SUCCESS) {
                    successNotify(result.msg);
                    form.unbind('submit').submit();
                     
                }else{                    
                    //errorNotify(result.msg);
                }
            }
        }); 
    });

    $('#driver-register-form').on('submit',function (e) {
        e.preventDefault();
        var action = $(this).attr('action');
        $.ajax({ 
            url: action,
            type: 'post',            
            data : $(this).serializeArray(),
            success: function(result) {
                if(result.status == AJAX_SUCCESS ){
                    successNotify(result.msg);
                    setTimeout(function() {location.reload()}, 2000);
                }else{
                    errorNotify(result.msg);
                }
            }
        });  
    });         

    $('#newsletter-form').on('submit',function (e) {
        e.preventDefault();        
        var action = $(this).attr('action');
        $.ajax({ 
            url: action,
            type: 'post',            
            data : $(this).serializeArray(),
            success: function(result) {
                if(result.status == AJAX_SUCCESS ){
                    successNotify(result.msg);
                    
                }else{
                    errorNotify(result.msg);
                }
            }
        });  
    });              

    $('#contact').on('submit',function (e) {
        e.preventDefault();        
        var action = $(this).attr('action');
        var method = $(this).attr('method');
        $.ajax({ 
            url: action,
            type: method,            
            data : $(this).serializeArray(),
            success: function(result) {
                if(result.status == HTTP_SUCCESS ){
                    successNotify(result.message);
                    setTimeout(function() {location.reload()}, 2000);
                }else{
                    // var message = result.message;
                    // errorNotify(message.replace(",","<br/>"));
                }
            }
        });  
    });

    /** Address Management Crud */
    $('#address-form').on('submit',function (e) {        
        e.preventDefault();
        var action = $(this).attr('action');
        var method = $(this).attr('method');
       // console.log(data);
        $.ajax({ 
            url: action,
            type: method,
            data : $("#address-form").serializeArray(),
            success: function(result) {                
                if(result.status == HTTP_SUCCESS ){
                    successNotify(result.message);
                    setTimeout(function() {location.reload()}, 2000);
                } else {
                    /* var message = result.message;
                    errorNotify(message.replace(",","<br/>")); */
                }
            }
        });  
    });
    $('.deleteaddress').click(function (e) {
        var action = $(this).data('action');
        $.ajax({ 
            url: action,
            type: 'DELETE',
            success: function(result) {
                if(result.status == HTTP_SUCCESS ){
                    successNotify(result.message);
                    setTimeout(function() {location.reload()}, 2000);
                } else {
                    var message = result.message;
                    errorNotify(message.replace(",","<br/>"));
                }
            }
        });  
    });
    
    $('#profile-update').submit(function(e) {        
        e.preventDefault();        
        var formData = new FormData(this);
        if ($(this).valid()) {
            var action = $('#profile-update').attr('action');
            $.ajax({
                url: action,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                /* data : $("#profile-update").serializeArray(), */
                success: function(result) {
                    if(result.status == HTTP_SUCCESS ){
                        successNotify(result.message);
                        $('#edit-profile').modal('toggle');
                    }else{
                        var message = result.message;
                        errorNotify(result.message.replace(",","<br/>"));
                    }
                }
            });  
        }
    });
    

    $('.addAddress').click(function()
    {
        $('#address-form').find("input[type=text],[type=hidden], select, textarea").val("");
        $('#address-form').attr('action',$('#address-form').attr('createAction'));
        $('#address-form').attr('method','POST');
        initMap();
        $('#modal_address').modal('toggle');
    });
    $('.editaddress').on('click', function() {
        var action = $(this).data('action');
        $.ajax({
            url: action,
            type: 'GET',
            success: function(result){
                if(result.status == HTTP_SUCCESS ){
                    successNotify(result.message);
                    var data = result.data;
                    $("#address-form [name='latitude']").val(data.latitude);
                    $("#address-form [name='longitude']").val(data.longitude);
                    $("#address-form [name='company']").val(data.company);
                    $("#address-form [name='landmark']").val(data.landmark);
                    $("#address-form [name='address_line_two']").val(data.address_line_two);
                    $("#address-form [name='address_line_one']").val(data.address_line_one);
                    $("#address-form [name='address_type_id']").val(data.address_type_id);
                    $('#address-form').attr('action',data.action);
                    $('#address-form').attr('method',data.method);
                    initMap();
                    $('#modal_address').modal('toggle');
                } else {
                    var message = result.message;
                    errorNotify(message.replace(",","<br/>"));
                }
            }
        });    
    });
    /** Address Management Crud */

    $('.vieworder').on('click', function() {
        var action = $(this).data('action');
        //alert(action);
        $.ajax({
            url: action,
            type: 'GET',
            success: function(result){
                if(result.status == HTTP_SUCCESS ){
                    successNotify(result.msg); 
                    $("#order_view").html(result.viewOrder);
                }
            }
        });    
    });       

 /*    $('.reorder').on('click',function(){
        var action = $(this).data('action');
        $.ajax({
            url: action,
            type: 'GET',
            success: function(result){
                alert(JSON.stringify(result));
            }
        });

    }) */        
 
    $('#wallet-form').on('submit',function (e) {   
        e.preventDefault();
        var action = $(this).attr('action');
        var method = $(this).attr('method');
        $.ajax({ 
            url: action,
            type: method,            
            data : $(this).serializeArray(),
            success: function(result) {
                if(result.status == HTTP_SUCCESS ) {
                    successNotify(result.message);                          
                    
                    window.location = result.data.payment_url;
                    $('#wallet_amount').html(result.data.wallet_amount);
                    $('#c-amount').val('');
                }else{
                    var message = result.message;
                    errorNotify(message.replace(",","<br/>"));
                }
            }
        });  
    });

    $('#redeem-form').on('submit',function (e) {
        e.preventDefault();        
        var action = $(this).attr('action');
        var method = $(this).attr('method');
        $.ajax({ 
            url: action,
            type: method,            
            data : $(this).serializeArray(),
            success: function(result) {
                if(result.status == HTTP_SUCCESS ) {
                    successNotify(result.message);
                    $('#redeem_points').val("");
                    $('#redeem_amount').html(result.data.loyalty_points);
                    $('#loyaltyname').html(result.data.loyalty_level_name);
                }else{
                    var message = result.message;
                    errorNotify(message.replace(",","<br/>"));
                }
            }
        });  
    });

    $('#post-rating').submit(function(e){
        e.preventDefault();
        if ($(this).valid()) {
            var action = $(this).attr('action');
            $.ajax({
                url: action,
                type: 'POST',            
                data : $(this).serializeArray(),
                success: function(result) {
                    if(result.status == HTTP_SUCCESS ) {
                        successNotify(result.message);
                        location.reload();
                    }else{
                        var message = result.message;
                        errorNotify(result.message.replace(",","<br/>"));
                    }
                }
            });  
        }
    });

    $('.restaurent_popup').on('click',function(e){
        e.preventDefault();
        /* var vendorId = $(this).attr('data-id'); */
        var vendorKey = $(this).attr('data-key');
        var backgroundImage = $(this).attr('data-img');
        var branchCount = $(this).attr('data-count');
        var action = $(this).attr('data-action');
         $.ajax({
            type : 'get',
            url : action,            
            data: {vendor_key:vendorKey},
            success:function(result){
                $('.modal-content').html(result.list);
                $('.popup-img').css("background-image", "url("+backgroundImage+")");
                //$("#popup_img").attr('src',backgroundImage);
                $('#branch_count').text(branchCount);
                $('.respopup').modal('show'); 

            }
        });
         
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
function questionToastr()
{
    iziToast.question({
        timeout: 20000,
        close: false,
        overlay: true,
        toastOnce: true,
        id: 'question',
        zindex: 999,
        title: 'Hey',
        message: 'Are you sure?',
        position: 'center',
        buttons: [
            ['<button><b>YES</b></button>', function (instance, toast) {
    
                instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');                    
                
            }, true],
            ['<button>NO</button>', function (instance, toast) {
    
                instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
    
            }],
        ],
        onClosing: function(instance, toast, closedBy){
            console.info('Closing | closedBy: ' + closedBy);
        },
        onClosed: function(instance, toast, closedBy){
            console.info('Closed | closedBy: ' + closedBy);
        }
    });
}