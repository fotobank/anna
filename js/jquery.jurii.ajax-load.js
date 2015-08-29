/**
 * �������
 */

/**
 * ajax ������ �������� ��������
 *
 * ������������� � �����:
 * // id �������� ��� ������������� ��������
 * $('ul.list-title').ajax_load({
 * 'id_child': 'a.navlink', - id �� ������� �������� ������������ ����
 * 'metod'   : 'click', - ���������� �����
 * 'url'     : '/src/lib/ajaxSite/ajaxLoad.php', - ����� �������
 * 'id_load' : '#pageContent', - id ��� �������� ������ ������� (��������)
 * 'type'    : 'POST', - ��� ������
 * 'header'  : 'Content-Type: application/json; charset=utf-8;', - ���������� ���������
 * 'data': {'location': 'index'}  - ������ ����������� � ������� �������
 * });
 *
 *
 * ������ ����� ajax:
 *
 * $('ul.list-title').ajax_load('load', {
 * 'url'    : '/src/lib/ajaxSite/ajaxLoad.php', - ����� �������
 * 'id_load': '#pageContent', - id ��� �������� ������ ������� (��������)
 * 'type'   : 'GET', - ��� ������
 * 'header' : 'Content-Type: application/json; charset=utf-8;', - ���������� ���������
 * 'data'   : {'location': 'index', 'id': '#element_id'}  - ������ ����������� � ������� �������
 * 	});
 *
 */
(function ($) {


	/*
	 * ������� �� ���������
	 */
	var defaults = {

		'id_child': false, // 'a.navlink', ����� �� ������� �������� ������������ ����
		'metod'   : null, // 'click',  ���������� �����
		'url'     : '/src/lib/ajaxSite/ajaxLoad.php', // ����� �������
		'id_load' : '#pageContent', // id ��� �������� ������ ������� (��������)
		'type'    : 'GET', // ��� ������
		'header'  : 'Content-Type: application/json; charset=utf-8;', // ���������� ���������
		'data'    : {}  // ������ ����������� � ������� �������

	};

	/*
	 * ���������� ���������� �����
	 */
	var options;


	var methods = {

		init: function (params) {

			options = $.extend({}, defaults, params);

			return $(this).ajax_load('on_load', { });

		},

		on_load: function () {

			return this.on(options.metod, options.id_child, function (e) {

				options.data.id = this.parentElement.id; // id click-�������� ��������������� ��������

				$(this).ajax_load('load', { });

				e.preventDefault(); // �������� � ���� '�' �������� �� ���������
			})
		},

		// �������� �� ������ a:href
		load: function (params) {

			options = $.extend({}, defaults, params);

			var event = $(this).find("a[href=" + options.data.id + "]"); // ���������� event

			event.after("<span class='aiax-load-gif'></span>"); // ��������� ������� ��������� ��������

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
					log('����� �������: "������: ' + XHR.status + '  ' + XHR.statusText + ': ' + options.url + 'in jquery.jurii.ajax-load".');
				},

				statusCode: {
					404: function () {
						log('������ ������� ���������� ������ ������� "jquery.jurii.ajax-load". ��������: "', options.url, '" �� �������.');
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
			// ���� ������������� ����� ����������, �� ��� ��������
			// ��� ���������, ����� ����� ������ ������� � �����
			// this ��� �� ���������� � �����
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
			//return methods.init.apply(this, arguments);
		} else if (typeof method === 'object' || !method) {
			// ���� ������ ���������� ���� ������, ���� ������ �����
			// ��������� ����� init
			return methods.init.apply(this, arguments);
		} else {
			// ���� ������ �� ����������
			$.error('����� "' + method + '" �� ������ � ������� jurii.ajax-load.js');
			return false;
		}
	};

})(jQuery);