/**
 * Created by Jurii on 07.07.14.
 */

$(document).ready(function () {


		$("#owl-head").owlCarousel({

			slideSpeed : 300,
			paginationSpeed : 400,
			singleItem:true,
			animateOut: 'fadeOut',
			animateIn: 'fadeIn',
			items:1,
			smartSpeed:450,
			loop:true,
			autoplay:true,
			autoplayTimeout:8000,
			autoplayHoverPause:true,

			dots:true,
			dotData:true,

			dotsContainer: '.owl-head-pags'

		});


	var owl = $('#owl-index');
	owl.owlCarousel({
		slideBy:1,
		singleItem: true,
		navSpeed:300, // скорость prev next
		autoplaySpeed:300,
		slideSpeed: 300,
		paginationSpeed: 300,
		smartSpeed: 500, // скорость скрола мышкой
		fluidSpeed:300,
		loop:true,
		autoplay:true,
		autoplayTimeout:10000,
		autoplayHoverPause:true,
		dots:true,
		dotsEach:true,

		responsive:{
			0:{
				items:1,
				nav:false
			},
			600:{
				items:3,
				nav:false
			},
			960:{
				items:4,
				nav:false
			},
			1200:{
				items:5,
				nav:true
			}
		}
	});
	owl.on('mousewheel', '.owl-stage', function (e) {
		if (e.deltaY>0) {
			owl.trigger('next.owl');
		} else {
			owl.trigger('prev.owl');
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
