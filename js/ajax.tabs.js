/**
 * разделы
 */

/**
 * ajax плагин загрузки контента
 *
 * инициализация и вызов:
 * // id родителя для делегирования потомков
 * $('ul.list-title').ajax_load({
 * 'id_child': 'a.navlink', - id на который вешается делегируемый клик
 * 'metod'   : 'click', - вызываемый метод
 * 'url'     : '/classes/ajaxSite/ajax_loader.php', - адрес скрипта
 * 'id_load' : '#pageContent', - id для загрузки ответа сервера (контента)
 * 'type'    : 'POST', - тип вызова
 * 'header'  : 'Content-Type: application/json; charset=utf-8;', - посылаемый заголовок
 * 'data': {'location': 'index'}  - массив посылаемого к серверу запроса
 * });
 *
 *
 * просто вызов ajax:
 *
 * $(this).ajax_load('load', {
 * 'url'    : '/classes/ajaxSite/ajax_loader.php', - адрес скрипта
 * 'id_load': '#pageContent', - id для загрузки ответа сервера (контента)
 * 'type'   : 'GET', - тип вызова
 * 'header' : 'Content-Type: application/json; charset=utf-8;', - посылаемый заголовок
 * 'data'   : {'location': 'index', 'id': '#element_id'}  - массив посылаемого к серверу запроса
 * 	});
 *
 */
(function ($) {

	var methods = {

		init: function (options) {

			var o = $.extend({

				'id_child': 'a.navlink', // id на который вешается клик
				'metod'   : 'click', // вызываемый метод
				'url'     : '/classes/ajaxSite/ajax_loader.php', // адрес скрипта
				'id_load' : '#pageContent', // id для загрузки ответа сервера (контента)
				'type'    : 'GET', // тип вызова
				'header'  : 'Content-Type: application/json; charset=utf-8;', // посылаемый заголовок
				'data'    : {}  // массив посылаемого к серверу запроса

			}, options);


			this.on(o.metod, o.id_child, function (e) {

				// id click-элемента
			  o.data.id = this.parentElement.id;

				$(this).ajax_load('load', {

					'url'    : o.url,
					'id_load': o.id_load,
					'type'   : o.type,
					'header' : o.header,
					'data'   : o.data

				});
				e.preventDefault(); // удаление с тега 'а' действия по умолчанию
			})
		},


		load: function (argument) {
			var a = $.extend({

				'url'    : '/classes/ajaxSite/ajax_loader.php',
				'id_load': '#pageContent',
				'type'   : 'GET',
				'header' : 'Content-Type: application/json; charset=utf-8;',
				'data'   : {}
			}, argument);



			var event = $(this); // Запоминаеи event
			event.after("<span class='aiax-load-gif'></span>"); // добавляем скрытый индикатор загрузки

			$(document).ajaxStart(function () {

			       	event.next().show();

			}).ajaxStop(function () {

				setTimeout(function () {

				     	event.next().remove();

				}, 300)

			});


			$.ajax({
				ifModified: true,
				global: true,
				type  : a.type,
				header: a.header,
				url   : a.url,
				data  : a.data,

				error: function (XHR) {
					console.log('Ответ функции: "Ошибка: ' + XHR.status + '  ' + XHR.statusText + ': ' + a.url + 'in jQuery.ajax_load".');
				},

				statusCode: {
					404: function () {
						console.log('Ошибка задания параметров вызова функции "jQuery.ajax_load". Страница: "', a.url, '" не найдена.');
					}
				},
				success   : function (html) {
// alert (html);
// console.log(html);
					$(a.id_load).html(html);
				}
			});
		}
	};


	$.fn.ajax_load = function (method) {

		if (methods[method]) {
			// если запрашиваемый метод существует, мы его вызываем
			// все параметры, кроме имени метода прийдут в метод
			// this так же перекочует в метод
			return methods[ method ].apply(this, Array.prototype.slice.call(arguments, 1));
			//return methods.init.apply(this, arguments);
		} else if (typeof method === 'object' || !method) {
			// если первым параметром идет объект, либо совсем пусто
			// выполняем метод init
			return methods.init.apply(this, arguments);
		} else {
			// если ничего не получилось
			$.error('Метод "' + method + '" не найден в плагине jQuery.ajax_load');
			return false;
		}
	};

})(jQuery);


// запуск ajax_load
$(function () {

	// id родителя для делегирования потомков
	/*$('ul.list-title').ajax_load({

		'id_child': 'a.navlink', // id на который вешается делегируемый клик
		'metod'   : 'click', // вызываемый метод
		'url'     : '/classes/ajaxSite/ajax_loader.php', // адрес скрипта
		'id_load' : '#pageContent', // id для загрузки ответа сервера (контента)
		'type'    : 'GET', // тип вызова
		'header'  : 'Content-Type: application/json; charset=utf-8;', // посылаемый заголовок
		'data': {'location': 'index'}  // массив посылаемого к серверу запроса

	});*/

	$(this).ajax_load('load', {
		'url'    : '/classes/ajaxSite/ajax_loader.php', // адрес скрипта
		'id_load': '#pageContent', // id для загрузки ответа сервера (контента)
		'type'   : 'GET', // тип вызова
		'header' : 'Content-Type: application/json; charset=utf-8;', // посылаемый заголовок
		'data'   : {'location': 'index', 'id': '#tabId'}  // массив посылаемого к серверу запроса
	});


	// вызов клика на первом a.navlink для выделения и загрузки контента
//	$('.list-title li:first a.navlink').click();

});