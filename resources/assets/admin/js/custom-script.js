//Tooltip
$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
});

//Date picker
$('.datepicker').datetimepicker({
  format: 'DD/MM/YYYY'
});
$('.timepicker').datetimepicker({
    format: 'LT'    
});

//Current Page
$(document).ready(function() {
  $("[href]").each(function() {
  if (this.href == window.location.href) {
      $(this).addClass("active");
      }
  });
});

//slimScroll
$(".scrollbar").slimScroll({  
  alwaysVisible: true,
  railVisible: true,
  railColor: '#0D515C',
  railOpacity: 0.2,
  color: '#0D515C',
  size: '5px'
});

$('.heading .nav a').click(function(){
  $('.col-xs-6.layout_height .scrollbar').slimScroll({ scrollTo: '0px', alwaysVisible: true});
});

//Sortable
$(document).ready(function(){
	$('#drag-left').draggable({
		connectToSortable: "#sortable",
		appendTo: "body",
		helper: function (event) {
			var src = $(event.currentTarget);

			html = $('<div />').addClass('addon-draggable');
			html.text("Product Name");

			return html;
		},
		cursorAt: { left: 20 },		
		delay: 300,
		revert: "invalid"
	});

	$("#drag-right").droppable({
		greedy: true,
		drop: function( event, ui ) {
			var draggable = $(ui.draggable[0]);			
			console.log(event.currentTarget);
			draggable = $(this);
			draggable.append("<li class='item_tag'>Product Name</li>");
		}
	});
});

$("[data-toggle=popover]").popover({
    html: true,
    content: function () {
        var targetId = $(this).attr('data-target');
        return $(targetId).html();
    }
});


$('body').on('click', function (e) {
    $('[data-toggle="popover"]').each(function () {      
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
          $(this).popover('hide');
        }
    });
});

$(document).on("click", ".popover .btn" , function(){    
    $(this).parents(".popover").popover('hide');
});

//Datatable
$(document).ready(function() {
  $('#example').DataTable();
} );

// iziToast alert
$(".trigger-info").on('click', function (event) {
  event.preventDefault();
  iziToast.info({
      id: 'info',
      title: 'Hello',
      position: 'bottomLeft',
      transitionIn: 'bounceInRight'
  });
});

$(".trigger-success").on('click', function (event) {
  event.preventDefault();
  iziToast.success({
      id: 'success',
      title: 'Success',
      message: 'Thank you for your visit',
      position: 'bottomRight',
      transitionIn: 'bounceInLeft',
  });
});

$(".trigger-warning").on('click', function (event) {
  event.preventDefault();
  iziToast.warning({
      id: 'warning',
      title: 'Warning',
      message: 'You forgot important data',
      position: 'topLeft',
      // close: false,
      transitionIn: 'flipInX',
      transitionOut: 'flipOutX'
  });
});

$(".trigger-error").on('click', function (event) {
  event.preventDefault();
  iziToast.error({
      id: 'error',
      title: 'Error',
      message: 'Illegal operation',
      position: 'topRight',
      transitionIn: 'fadeInDown'
  });
});

// function error(msg) {
//   iziToast.error({
//       id: 'error',
//       title: 'Error',
//       message: msg,
//       position: 'bottomRight',
//       transitionIn: 'fadeInDown'
//   });  
// }

// function success(msg) {
//   iziToast.success({
//       title: msg,
//   });  
// }
