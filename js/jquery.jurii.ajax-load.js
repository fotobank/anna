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
 * 'url'     : '/classes/ajaxSite/ajax_load.php', - ����� �������
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
 * 'url'    : '/classes/ajaxSite/ajax_load.php', - ����� �������
 * 'id_load': '#pageContent', - id ��� �������� ������ ������� (��������)
 * 'type'   : 'GET', - ��� ������
 * 'header' : 'Content-Type: application/json; charset=utf-8;', - ���������� ���������
 * 'data'   : {'location': 'index', 'id': '#element_id'}  - ������ ����������� � ������� �������
 * 	});
 *
 */
(function ($) {

	var methods = {

		init: function (options) {

			var o = $.extend({

				'id_child': false, // 'a.navlink', ����� �� ������� �������� ������������ ����
				'metod'   : null, // 'click',  ���������� �����
				'url'     : '/classes/ajaxSite/ajax_load.php', // ����� �������
				'id_load' : '#pageContent', // id ��� �������� ������ ������� (��������)
				'type'    : 'GET', // ��� ������
				'header'  : 'Content-Type: application/json; charset=utf-8;', // ���������� ���������
				'data'    : {},  // ������ ����������� � ������� �������
				'id_load_gif' : 1 // ������������ ���������� ��� ����������� ������ gif

			}, options);



			  return this.on(o.metod, o.id_child, function (e) {

				o.data.id = this.parentElement.id; // id click-�������� ��������������� ��������

				$(this).ajax_load('load', {
					'id-load-gif' : o.id_child,
					'url'    : o.url,
					'id_load': o.id_load,
					'type'   : o.type,
					'header' : o.header,
					'data'   : o.data,
					'id_load_gif': o.id_load_gif
				});
				e.preventDefault(); // �������� � ���� '�' �������� �� ���������
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


		var event = $(this); // ���������� event


    if(a.id_load_gif == 0) event = $(this).find("a[href="+a.data.id+"]"); // �������� event


			event.after("<span class='aiax-load-gif'></span>"); // ��������� ������� ��������� ��������
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
					console.log('����� �������: "������: ' + XHR.status + '  ' + XHR.statusText + ': ' + a.url + 'in jQuery.ajax_load".');
				},

				statusCode: {
					404: function () {
						console.log('������ ������� ���������� ������ ������� "jQuery.ajax_load". ��������: "', a.url, '" �� �������.');
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
			// ���� ������������� ����� ����������, �� ��� ��������
			// ��� ���������, ����� ����� ������ ������� � �����
			// this ��� �� ���������� � �����
			return methods[ method ].apply(this, Array.prototype.slice.call(arguments, 1));
			//return methods.init.apply(this, arguments);
		} else if (typeof method === 'object' || !method) {
			// ���� ������ ���������� ���� ������, ���� ������ �����
			// ��������� ����� init
			return methods.init.apply(this, arguments);
		} else {
			// ���� ������ �� ����������
			$.error('����� "' + method + '" �� ������ � ������� jQuery.ajax_load');
			return false;
		}
	};

})(jQuery);