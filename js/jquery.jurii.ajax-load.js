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
 * 'url'     : '/src/lib/ajaxSite/ajaxLoad.php', - адрес скрипта
 * 'id_load' : '#pageContent', - id для загрузки ответа сервера (контента)
 * 'type'    : 'POST', - тип вызова
 * 'header'  : 'Content-Type: application/json; charset=utf-8;', - посылаемый заголовок
 * 'data': {'location': 'index'}  - массив посылаемого к серверу запроса
 * });
 *
 *
 * просто вызов ajax:
 *
 * $('ul.list-title').ajax_load('load', {
 * 'url'    : '/src/lib/ajaxSite/ajaxLoad.php', - адрес скрипта
 * 'id_load': '#pageContent', - id для загрузки ответа сервера (контента)
 * 'type'   : 'GET', - тип вызова
 * 'header' : 'Content-Type: application/json; charset=utf-8;', - посылаемый заголовок
 * 'data'   : {'location': 'index', 'id': '#element_id'}  - массив посылаемого к серверу запроса
 * 	});
 *
 */
(function ($) {


	/*
	 * значени по умолчанию
	 */
	var defaults = {

		'id_child': false, // 'a.navlink', класс на который вешается делегируемый клик
		'metod'   : null, // 'click',  вызываемый метод
		'url'     : '/src/lib/ajaxSite/ajaxLoad.php', // адрес скрипта
		'id_load' : '#pageContent', // id для загрузки ответа сервера (контента)
		'type'    : 'GET', // тип вызова
		'header'  : 'Content-Type: application/json; charset=utf-8;', // посылаемый заголовок
		'data'    : {}  // массив посылаемого к серверу запроса

	};

	/*
	 * глобальная переменная опций
	 */
	var options;


	var methods = {

		init: function (params) {

			options = $.extend({}, defaults, params);

			return $(this).ajax_load('on_load', { });

		},

		on_load: function () {

			return this.on(options.metod, options.id_child, function (e) {

				options.data.id = this.parentElement.id; // id click-элемента делегированного элемента

				$(this).ajax_load('load', { });

				e.preventDefault(); // удаление с тега 'а' действия по умолчанию
			})
		},

		// загрузка по ссылке a:href
		load: function (params) {

			options = $.extend({}, defaults, params);

			var event = $(this).find("a[href=" + options.data.id + "]"); // Запоминаеи event

			event.after("<span class='aiax-load-gif'></span>"); // добавляем скрытый индикатор загрузки

			$(document).ajaxStart(function () {

				event.next(".aiax-load-gif").show();

			}).ajaxStop(function () {

				setTimeout(function () {

					event.next(".aiax-load-gif").remove();

				}, 300)

			});

			return $.ajax({
				ifModified: true,
				global    : true,
				type      : options.type,
				header    : options.header,
				url       : options.url,
				data      : options.data,

				error: function (XHR) {
					log('Ответ функции: "Ошибка: ' + XHR.status + '  ' + XHR.statusText + ': ' + options.url + 'in jquery.jurii.ajax-load".');
				},

				statusCode: {
					404: function () {
						log('Ошибка задания параметров вызова функции "jquery.jurii.ajax-load". Страница: "', options.url, '" не найдена.');
					}
				},
				success   : function (html) {

					$(options.id_load).after(html);

				}
			});
		}
	};


	$.fn.ajax_load = function (method) {

		if (methods[method]) {
			// если запрашиваемый метод существует, мы его вызываем
			// все параметры, кроме имени метода прийдут в метод
			// this так же перекочует в метод
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
			//return methods.init.apply(this, arguments);
		} else if (typeof method === 'object' || !method) {
			// если первым параметром идет объект, либо совсем пусто
			// выполняем метод init
			return methods.init.apply(this, arguments);
		} else {
			// если ничего не получилось
			$.error('Метод "' + method + '" не найден в плагине jurii.ajax-load.js');
			return false;
		}
	};

})(jQuery);