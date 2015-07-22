/**
 * Created by Jurii on 07.07.14.
 */
$(document).ready(function(){
	// This function is executed once the document is loaded
	
	// Caching the jQuery selectors:
	var count = $('.onlineWidget .count');
	var panel = $('.onlineWidget .panel');
	var timeout;

	// Loading the number of users online into the count div:
	count.load('/inc/who_is_online/online.php #online');

	$('.onlineWidget').hover(
		function(){

			// Setting a custom 'open' event on the sliding panel:
			clearTimeout(timeout);
			timeout = setTimeout(function(){panel.trigger('open');},500);
		},
		function(){

			// Custom 'close' event:
			clearTimeout(timeout);
			timeout = setTimeout(function(){panel.trigger('close');},500);
		}
	);
	
	var loaded=false;	// A flag which prevents multiple ajax calls to geodata.php;
	var geodata;

	// Binding functions to custom events:
	panel.bind('open',function(){
		panel.slideDown(function(){
			if(!loaded)
			{
//				panel('.container',  $(this).load('inc/who_is_online/geodata.php'));
				panel.load('/inc/who_is_online/geodata.php #geodata');

				// Loading the countries and the flags once the sliding panel is shown:
				// panel.load('inc/who_is_online/geodata.php');
				loaded=true;
			}
		});
	}).bind('close',function(){
		panel.slideUp();
	});
	
});