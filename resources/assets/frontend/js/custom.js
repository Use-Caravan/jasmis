

 $('[data-toggle="tooltip"]').tooltip(); 


// driver-banner
$(".smal-close").click(function() {
    $(".driver-banner").toggleClass("closed");
    $(".top-header").toggleClass("default");
   
});





// all restaurant slider

$('.advertisement-slider').owlCarousel({
    loop:true,
    margin:0,
    nav:true,
      navText:['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
    responsive:{
        0:{
            items:1
        },
        600:{
            items:1
        },
        1000:{
            items:1
        }
    }
});


// header scroll

$(window).scroll(function() {    
    var scroll = $(window).scrollTop();
    if (scroll >= 2) {
        $(".top-header").addClass("stick");
    } else {
    $(".top-header").removeClass("stick");
      }
});

// form-group

//floating_label
$(document).on('focus active', '.floating_label .form-group .form-control',function(){
    $(this).parent().addClass('focus');
});


$(function(){
        var val = $('.floating_label .form-group .form-control').val();
        if(val == 1) {
            $(this).parent().addClass('focus');
        } else {
            $(this).parent().removeClass('focus');
        }  
});


$(document).on('blur', '.floating_label .form-group .form-control',function(){
    $(this).parent().removeClass('focus');
});

// foucus

$('.floating_label .form-group .form-control').blur(function() {
     if( $(this).val() == '' ) { 
            $(this).parent().removeClass('focus');
            $(this).parent().removeClass('focuss');

     } else {
         $(this).parent().addClass('focus');
     }
});

// modal shown

$('.modal').on('shown.bs.modal', function (e) {
  $('body').addClass('modal-open');  
})

// loader

// loader
  $('.loader').on('click', function() {
    var $this = $(this);
    var loadingText = '<span class="shape"><i class="fa fa-circle-o-notch fa-spin"></i> loading...</span>';
    if ($(this).html() !== loadingText) {
      $this.data('original-text', $(this).html());
      $this.html(loadingText);
    }
    setTimeout(function() {
      $this.html($this.data('original-text'));
    }, 2000);
  });

  $('.loader2').on('click', function() {
    var $this = $(this);
    var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i>';
    if ($(this).html() !== loadingText) {
      $this.data('original-text', $(this).html());
      $this.html(loadingText);
    }
    setTimeout(function() {
      $this.html($this.data('original-text'));
    }, 2000);
  });


// filter 

// search 


$("#filter-toggle, .filter-overlay").click(function() {
    $(".search-and-filter").toggleClass("open");
    $(".filter-overlay").toggleClass("open");
});


// tab

$(".menu-button-box .add-click").click(function() {
$(this).parent().toggleClass('in');  
});


// sticky sidebar

// sticky responsive size



$(document).ready(function() {
    // Optimalisation: Store the references outside the event handler:
    var $window = $(window);

    function checkWidth() {
        var windowsize = $window.width();
        if (windowsize > 767) {
         
          $("[data-sticky_column]").stick_in_parent({recalc_every: 1});

    $(function() {
      return $("[data-sticky_column]").stick_in_parent({
        parent: "[data-sticky_parent]"
      });
    });



    new WOW().init();


    // all restaurant slider

$('.restaurant-slider').owlCarousel({
    loop:true,
    margin:0,
    nav:true,
      navText:['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
    responsive:{
        0:{
            items:1
        },
        640:{
            items:3
        },
        768:{
            items:4
        },
        1000:{
            items:5
        }
    }
});

  // offer slider

$('.offer-slider').owlCarousel({
    loop:true,
    margin:0,
    nav:true,
    navText:['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
    items: 1
});


// add sider




        }
    }
    // Execute on load
    checkWidth();
    // Bind event listener
    $(window).resize(checkWidth);
})


// datepicker

$('.dpicker').datepicker({
    language: 'en',
   // minDate: new Date() // Now can select only dates, which goes after today
   onSelect: function (dateText, inst) {
         $('.dpicker').parent().addClass('focuss');
      }
});






$('.tpicker').datepicker({
    dateFormat: ' ',
    timepicker: true,
    timeFormat: "hh:ii AA",
    classes: 'only-timepicker'
});

// change password

$("span.change-password").click(function() {
  $(".change-password-div").toggleClass("open");
  $(this).hide();
});


// alert


/* $(".alert-trigger").click(function() {
    iziToast.success({
        title: 'C wallet',
        position: 'bottomLeft',
        message: 'Payment Added in C Wallet'
    });
}); */



// reedam points


$(".loyalty_box a.link").click(function() {
  $(".reedam_reward").toggleClass("open");
});


//Current Page
$(document).ready(function() {
    $("[href]").each(function() {
    if (this.href == window.location.href) {
        $(this).addClass("current_page");
        }
    });
});



// responsive menu toggle


// Header menu
$(".responsive-menu, .header-menu-overlay, .close-header-menu,  .navigation li a").click(function() {
  $(".navigation").toggleClass('open');
  $(".header-menu-overlay").toggleClass('open');
    $("body").toggleClass('overflow');  
});


// cart toggle



$(".mini-mobile-cart, .close-cart-toggle, .cart-toggle-overlay").click(function() {
  $(".de-cart").toggleClass('open');
  $(".cart-toggle-overlay").toggleClass('open');
    $("body").toggleClass('overflow');  
});


// footer closepanel

$("footer .col-sm-4 h3").click(function() {
  $(this).parent().toggleClass('open');   
});

// footer closepanel

$("body").on("click",".group-item .full_row .box", function() {
  $(this).parent().toggleClass('open');   
}); 


// custom input

$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});