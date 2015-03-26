/**
 * Created by Jurii on 25.03.2015.
 * запуск главного меню
 */
$(document).ready(function () {
	var menu = $('.centered-navigation-menu');
	var menuToggle = $('.centered-navigation-menu-button');

	$(menuToggle).on('click', function (e) {
		e.preventDefault();
		menu.slideToggle(function () {
			if (menu.is(':hidden')) {
				menu.removeAttr('style');
			}
		});
	});
});