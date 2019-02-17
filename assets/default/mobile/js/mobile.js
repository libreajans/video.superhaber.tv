$(function() {
	$('#mobile-headline-slider').utilCarousel(
	{
		items : 1,
		loop:true,
		drag:true,
		autoplay:true,
		pagination:false,
		autoplayTimeout:1000,
		autoplayHoverPause:true,
		dotsContainer: '#mobile-headline-pager'
	});

    $('#open-side-menu').click(function(){
        $(this).toggleClass('open');
    });
});
