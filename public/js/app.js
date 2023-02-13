(function ($) {
	// Disable submit buttom to all form
	$('form').submit(function (event) {
	    if($(this).hasClass('submit')) return true;
	    if ($(this).hasClass('isDisabled')) {
	        event.preventDefault();
	    }
	    else {
	        $(this).find(':submit').html('<i class="fa fa-spinner fa-spin"></i>');
	        $(this).addClass('isDisabled');
	    }
	});

	$('.entry--trigger').click(function (e) {
		if($(this).hasClass('is--active')) {
			$(this).removeClass('is--active');
			$(this).parent('li').removeClass('is--active');
		} else {
			$(this).addClass('is--active');
			$(this).parent('li').addClass('is--active');
		}
	});

	
	var lastScrollTop = 80;
	$(window).scroll(function(event){
	   var st = $(this).scrollTop();
	   if (st > lastScrollTop){
	       $('.desktop .logo').addClass('stick-up');
	   } else {
	      $('.desktop .logo').removeClass('stick-up');
	   }
	});

	$('.mobile .emotion--sizer-xs').height(400);

})(jQuery);

(function ($) {
    var o = $('.rd-navbar');
    if (o.length > 0) {

        $(document).ready(function () {
            o.RDNavbar({
            	stickUp: true,
                stickUpClone: false
            });
        });
    }
})(jQuery);
