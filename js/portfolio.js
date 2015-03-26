/**
 * Created by Jurii on 07.07.14.
 */
$(document).ready(function () {

			jQuery('#mycarousel-1, #mycarousel-2, #mycarousel-3, #mycarousel-4, #mycarousel-5, #mycarousel-6, #mycarousel-7')
					.jcarousel({
						horisontal: true,
						wrap      : 'circular',
						scroll    : 4,
						easing    : 'easeInOutBack',
						animation : 1500
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
							title : null,
							thumbs	: {
								width	: 50,
								height	: 50
							}
						},
						beforeShow: function () {
							/* Disable right click */
							$.fancybox.wrap.bind("contextmenu", function (e) {
								return false;
							});
						}

					})
});