/**
 * �������
 */

/**
 * ajax ������ �������� ��������
 *
 * ������������� � �����:
 * // id �������� ��� ������������� ��������
 * $('ul.list-categorii').ajax_load({
 * 'id_child': 'a.text', - id �� ������� �������� ������������ ����
 * 'metod'   : 'click', - ���������� �����
 * 'url'     : '/classes/ajaxSite/index_loader.php', - ����� �������
 * 'id_load' : '#list-content', - id ��� �������� ������ ������� (��������)
 * 'type'    : 'POST', - ��� ������
 * 'header'  : 'Content-Type: application/json; charset=utf-8;', - ���������� ���������
 * 'data': {'location': 'index'}  - ������ ����������� � ������� �������
 * });
 *
 *
 * ������ ����� ajax:
 *
 * $(this).ajax_load('load', {
 * 'url'    : '/classes/ajaxSite/index_loader.php', - ����� �������
 * 'id_load': '#list-content', - id ��� �������� ������ ������� (��������)
 * 'type'   : 'POST', - ��� ������
 * 'header' : 'Content-Type: application/json; charset=utf-8;', - ���������� ���������
 * 'data'   : {'location': 'index', 'id': '#element_id'}  - ������ ����������� � ������� �������
 * 	});
 *
 */
(function ($) {

	var methods = {

		init: function (options) {

			var o = $.extend({

				'id_child': 'a.text', // id �� ������� �������� ����
				'metod'   : 'click', // ���������� �����
				'url'     : '/classes/ajaxSite/index_loader.php', // ����� �������
				'id_load' : '#list-content', // id ��� �������� ������ ������� (��������)
				'type'    : 'POST', // ��� ������
				'header'  : 'Content-Type: application/json; charset=utf-8;', // ���������� ���������
				'data'    : {}  // ������ ����������� � ������� �������

			}, options);


			this.on(o.metod, o.id_child, function (e) {

				// id click-��������
			  o.data.id = this.parentElement.id;

				$(this).ajax_load('load', {

					'url'    : o.url,
					'id_load': o.id_load,
					'type'   : o.type,
					'header' : o.header,
					'data'   : o.data

				});
				e.preventDefault(); // �������� � ���� '�' �������� �� ���������
			})
		},


		load: function (argument) {
			var a = $.extend({

				'url'    : '/classes/ajaxSite/index_loader.php',
				'id_load': '#list-content',
				'type'   : 'POST',
				'header' : 'Content-Type: application/json; charset=utf-8;',
				'data'   : {}
			}, argument);

			var loader = $('#' + this.context.nextElementSibling.id);
			loader.empty();
			// ��������� ��������
			$(document).ready(function () {

				// ��������� ajax indicator
				loader.append('<img src="/images/ajax-loader.gif">');

				loader.css({
					display : "none",
					margin: "2px 50px 0 0"
				});
			});

			// Ajax activity indicator bound to ajax start/stop document events
			$(document).ajaxStart(function () {
				loader.show();
			}).ajaxStop(function () {

				setTimeout(function () {
					loader.empty();
				}, 200)

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
// alert (html);
// console.log(html);
					$(a.id_load).html(html);
				}
			});
		}
	};


	$.fn.ajax_load = function (method) {

		if (methods[method]) {
			// ���� ������������� ����� ����������, �� ��� ��������
			// ��� ���������, ����� ����� ������ ������� � �����
			// this ��� �� ���������� � �����
			return methods[ method ].apply(this, Array.prototype.slice.call(arguments, 1));
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



// ������ ����
$(function () {

	tabs.init();

	// id �������� ��� ������������� ��������
	$('ul.list-categorii').ajax_load({

		'id_child': 'a.text', // id �� ������� �������� ������������ ����
		'metod'   : 'click', // ���������� �����
		'url'     : '/classes/ajaxSite/index_loader.php', // ����� �������
		'id_load' : '#list-content', // id ��� �������� ������ ������� (��������)
		'type'    : 'POST', // ��� ������
		'header'  : 'Content-Type: application/json; charset=utf-8;', // ���������� ���������
		'data': {'location': 'index'}  // ������ ����������� � ������� �������

	});


	// ����� ����� �� ������ a.text ��� ��������� � �������� ��������
	$('.list-categorii li:first a.text').click();

});


tabs = {
	init: function () {

		var tabCont = $('.tab-content');
		$('.tabs').each(function () {

			tabCont.hide();

			$($('ul.list-categorii .selected a').attr('href')).fadeIn(300);

			$('ul.list-categorii a').click(function (e) {

				tabCont.hide();

				$($(this).attr('href')).fadeIn(300);

				$(this).parent().addClass('selected').siblings().removeClass('selected');

				e.preventDefault();

			});
		});
	}
};
