/**
 * Created by Jurii on 07.07.14.
 */

$(document).ready(function () {


	//������� �������
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
			autoplayTimeout:12000,
			autoplayHoverPause:true,
			dots:true, // ����� ���������
			dotsContainer: '.owl-head-pags' // ��������� ���������

		});



	$('#owl-index').owlCarousel({
		slideBy:1,
		singleItem: true,
		navSpeed:300, // �������� prev next
		autoplaySpeed:300,
		slideSpeed: 300,
		paginationSpeed: 300,
		smartSpeed: 500, // �������� ������ ������
		fluidSpeed:300,
		loop:true,
		autoplay:true,
		autoplayTimeout:10000,
		autoplayHoverPause:true,
		dots:true,
		dotsEach:true,
		items:5,
		responsive:{
			0:{
				items:2,
				nav:false
			},
			420:{
				items:3,
				nav:false
			},
			610:{
				items:4,
				nav:false
			},
			825:{
				items:5,
				nav:false
			},
			950:{
				items:5,
				nav:true
			}
		}
	});

	// ���������� ��������
	/*owl.on('mousewheel', '.owl-stage', function (e) {
		if (e.deltaY>0) {
			owl.trigger('next.owl');
		} else {
			owl.trigger('prev.owl');
		}
		e.preventDefault();
	});*/


/*
	padding - ������ ����� ����� ������ ��������;
	preload - ���������� ��������, ������� ����������� ��������������;
	closeEffect - ������ �������� (elastic - ������������, fade - ����������, none - ��� �������);
	nextEffect, prevEffect - ������ �������� ������/����� (elastic - ������������, fade - ����������, none - ��� �������);
	opacity - �������������� ���������� (0 - ��������� ����������, 1 - ������������);
	speedIn, speedOut - ����� ����������/��������� ���������� � �������������.

	����� �������� ��� ����������, ���������� �������� � ���� ������ CSS ��������� ������:
#fancybox-overlay {background: #ff0000 !important;}
#ff0000 - ���� ����. ����� �������� �� ����������� ���������� url(/fon.jpg).

	����� ��������� ���������� �� ��������� ��������� ����� �������� �� ���� ������ http://fancyapps.com/fancybox/#examples
*/
	$("a.plus")
			.attr('rel', 'gallery')
			.fancybox({
				padding : 4,
				scrolling : 'no',
				closeBtn : false,

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

	// ������ �������
	/*jQuery('.col-1').containedStickyScroll({
		hightTop      : 470
	});
	jQuery('.col-3').containedStickyScroll({
				hightTop : 570
			}
	);*/



		$(function(){
			var win = $(window);
			var nav = $('.col-1');
			var navTop = nav.length && nav.offset().top;
			var isFixed=0;

			processScroll();
			win.on('scroll', processScroll);

			function processScroll() {
			var i, scrollTop = win.scrollTop();

			if (scrollTop >= navTop && !isFixed) {
			isFixed = 1;
			nav.addClass('subnav-fixed')
			} else if (scrollTop <= navTop && isFixed) {
			isFixed = 0;
			nav.removeClass('subnav-fixed')
			}
			}


			});





// ������� ��������� � ������
	/*$(document).ready(function(){
		$('a[href*=#]').bind("click", function(e){
			var anchor = $(this);
			$('html, body').stop().animate({
				scrollTop: $(anchor.attr('href')).offset().top
			}, 1000);
			e.preventDefault();
		});
		return false;
	});*/

	// = ������ ������� ��������� � ������� �����
	//	 �� ��� ������ ����� ������� ���������� �� #
	/*$('a[href^="#"]').bind('click.smoothscroll',function (e) {
		e.preventDefault();

		var target = this.hash,
				$target = $(target);

		$('html, body').stop().animate({
			'scrollTop': $target.offset().top
		}, 900, 'swing', function () {
			window.location.hash = target;
		});
	});*/


});