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

			jQuery('#mycarousel')
					.jcarousel({
						horisontal: true,
						wrap      : 'circular',
						scroll    : 1,
						easing    : 'easeInOutBack',
						animation : 1200
					});


	/*(function($, Modernizr) {
		$(function() {
			$('.mycarousel').jcarousel({
				wrap: 'circular',
				transitions: Modernizr.csstransitions ? {
					transforms:   Modernizr.csstransforms,
					transforms3d: Modernizr.csstransforms3d,
					easing:       'easeInOutBack'
				} : false
			});

			$('.jcarousel-control-prev').jcarouselControl({
				target: '-=1'
			});

			$('.jcarousel-control-next').jcarouselControl({
				target: '+=1'
			});
		});
	})(jQuery, Modernizr);*/


});
