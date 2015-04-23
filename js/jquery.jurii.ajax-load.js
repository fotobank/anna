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
 * 'url'     : '/classes/ajaxSite/ajax_load.php', - адрес скрипта
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
 * 'url'    : '/classes/ajaxSite/ajax_load.php', - адрес скрипта
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

				'id_child': false, // 'a.navlink', класс на который вешается делегируемый клик
				'metod'   : null, // 'click',  вызываемый метод
				'url'     : '/classes/ajaxSite/ajax_load.php', // адрес скрипта
				'id_load' : '#pageContent', // id для загрузки ответа сервера (контента)
				'type'    : 'GET', // тип вызова
				'header'  : 'Content-Type: application/json; charset=utf-8;', // посылаемый заголовок
				'data'    : {},  // массив посылаемого к серверу запроса
				'id_load_gif' : 1 // техничесская переменная для правильного показа gif

			}, options);



			  return this.on(o.metod, o.id_child, function (e) {

				o.data.id = this.parentElement.id; // id click-элемента делегированного элемента

				$(this).ajax_load('load', {
					'id-load-gif' : o.id_child,
					'url'    : o.url,
					'id_load': o.id_load,
					'type'   : o.type,
					'header' : o.header,
					'data'   : o.data,
					'id_load_gif': o.id_load_gif
				});
				e.preventDefault(); // удаление с тега 'а' действия по умолчанию
			})
		},


		load: function (argument) {
			var a = $.extend({

				'id_load_gif': 0,
				'url'    : '/classes/ajaxSite/ajax_load.php',
				'id_load': '#pageContent',
				'type'   : 'GET',
				'header' : 'Content-Type: application/json; charset=utf-8;',
				'data'   : {}
			}, argument);


		var event = $(this); // Запоминаеи event


    if(a.id_load_gif == 0) event = $(this).find("a[href="+a.data.id+"]"); // Изменяем event


			event.after("<span class='aiax-load-gif'></span>"); // добавляем скрытый индикатор загрузки
			$(document).ajaxStart(function () {

			       	event.next(".aiax-load-gif").show();

			}).ajaxStop(function () {

				setTimeout(function () {

				     	event.next(".aiax-load-gif").remove();

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

					$(a.id_load).empty().after(html);

				}
			});
		}
	};


	$.fn.ajax_load = function (method) {

		console.log(Array.prototype.slice.call(arguments, 1));
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