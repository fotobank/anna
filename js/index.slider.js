/**
 * Created by Jurii on 07.07.14.
 */

$(document).ready(function () {

			$('.slider')
					._TMS({
						show               : 0,
						pauseOnHover       : false,
						prevBu             : false,
						nextBu             : false,
						playBu             : false,
						duration           : 700,
						preset             : 'fade',
						pagination         : '.pags',
						pagNums            : false,
						slideshow          : 7000,
						numStatus          : false,
						banners            : false, // fromLeft, fromRight, fromTop, fromBottom
						waitBannerAnimation: false,
						progressBar        : false
					});


		var carousel = $("#owl-index");
		carousel.owlCarousel({
			loop:true,
			autoWidth:true,
			autoplay:true,
			autoplayTimeout: 10000,
			autoplayHoverPause:true,
			items:5,
			responsive:{
				0:{
					items:1,
					nav:true
				},
				600:{
					items:3,
					nav:true
				},
				1000:{
					items:5,
					nav:true
				}
			}
		});


	carousel.on('mousewheel', '.owl-stage', function (e) {
		if (e.deltaY>0) {
			carousel.trigger('next.owl');
		} else {
			carousel.trigger('prev.owl');
		}
		e.preventDefault();
	});


	$("a.plus")
			.attr('rel', 'gallery')
			.fancybox({
				padding : 0,
				openEffect  : 'elastic',
				closeEffect	: 'elastic',
				nextEffect  : 'elastic',
				prevEffect  : 'elastic',
				margin      : [20, 60, 20, 60], // Increase left/right margin
				helpers : {
					title : null
				},
				beforeShow: function () {
					/* Disable right click */
					$.fancybox.wrap.bind("contextmenu", function (e) {
						return false;
					});
				}

			});
});
