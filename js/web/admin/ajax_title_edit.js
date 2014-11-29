$(function () {

	var blockList = $('.list-categorii');

	blockList.sortable({
		axis  : 'y',				// ��������� ������ ������������ �����������
		update: function () {		// ���������� ����� ����������� �������

			// ����� toArray ���������� ������ � ������������� ����������
			var arr = blockList.sortable('toArray');

			// �������� htad �� id
			arr = $.map(arr, function (val, key) {
				return val.replace('head-', '');
			});

			// Saving with AJAX
			$.get('/classes/ajaxSite/ajax_title_edit.php', {action: 'rearrange', positions: arr});
		},

		/* Opera fix: */
		stop  : function (e, ui) {
			ui.item.css({'top': '0', 'left': '0'});
		}
	});


	//���������� ����������, ���������� ������ JQuery
	// ������� ������� ���������� �����:
	var currentBlock;

	// ��������� ����������� ���� ������������� ��������
	$("#dialog-confirm").dialog({
		resizable: false,
		modal    : true,
		autoOpen : false,
		buttons  : {
			'�������': function () {

				$.get("/classes/ajaxSite/ajax_title_edit.php", {"action": "delete", "id": currentBlock.data('id')}, function (msg) {
					if (1 == msg) {
						console.log('������ ������� �������.');
					} else {
						console.log('������ �������� ������.');
					}

					currentBlock.fadeOut('fast');
				});

				$(this).dialog('close');
			},
			'������' : function () {
				$(this).dialog('close');
			}
		}
	});

	// ��� ������������� ������� ������, ������ ����������� ������� �� ������ ��������������:
	blockList.on('dblclick', 'li', function () {
		$(this).find('a.edit').click();
	});


	blockList.on('click', 'a', function (e) {

		currentBlock = $(this).closest('li');

		currentBlock.data('id', currentBlock.attr('id').replace('head-', ''));

		e.preventDefault();
	});


	// ������������� �� ���� �� ������ ��������:
	blockList.on('click', 'a.delete', function (e) {
		$("#dialog-confirm").dialog('open');
		e.preventDefault();
	});

	// ����������� ��������� ����� actions ��� ����� � ��������
	blockList.find('li').on_off('init', '.actions');


	//������������� �� ���� �� ������ ��������������
	blockList.on('click', 'a.edit', function () {

		var container = currentBlock.find('.text');

		if (!currentBlock.data('origText')) {
			// ���������� �������� ������ �����, ����� �� �����
			// ������������ ��� �����, ���� ������������ �� ��������� ���������:

			currentBlock.data('origText', container.text());
		}
		else {
			return false;
		}

		$('<input type="text">').val(container.text()).appendTo(container.empty());

		// ���������� ���������� ���������� � ������ ������:
		container.append(
				'<div class="ok-cancel">' +
						'<a title="���������." class="saveChanges"><img class="ok" src="/images/ok.png" /></a>' +
						'<a title="�������� ��������������." class="discardChanges"><img class="return" src="/images/return.png" /></a>' +
						'</div>'
		);
		//������������ ������ ��������������, ���� ���� ����� ��� �������:
		 currentBlock.on_off('block');

	});

	//�������� ��������������:
	blockList.on('click', 'a.discardChanges', function (e) {
		currentBlock.find('.text')
				.text(currentBlock.data('origText'))
				.end()
				.removeData('origText');
		e.preventDefault();

		currentBlock.on_off('on', '.actions');
	});

	// ���������:
	blockList.on('click', 'a.saveChanges', function (e) {
		var text = currentBlock.find("input[type=text]").val();

		$.get("/classes/ajaxSite/ajax_title_edit.php", {'action': 'edit', 'id': currentBlock.data('id'), 'text': text});

		currentBlock.removeData('origText')
				.find(".text")
				.text(text);
		e.preventDefault();
		currentBlock.on_off('on', '.actions');
	});

	// �������� �����
	var timestamp = 0;
	$('#addButton').click(function (e) {

		// ���������� �� ���� 1 ���� � �������
		if ((new Date()).getTime() - timestamp < 1000) return false;

		$.get("/classes/ajaxSite/ajax_title_edit.php", {'action': 'new', 'text': '����� ������.', 'rand': Math.random()}, function (msg) {

			// ���������� ������ ����� � ����� ��� �� �����:
			$(msg).hide().appendTo('.list-categorii').fadeIn();
		});

		timestamp = (new Date()).getTime();

		e.preventDefault();
	});

});




(function ($) {

	var methods = {

		// ������������� ����������� ��������� �����
		init: function (id) {

			return this.hover(
					function () {
						$(this).find(id).css('display', 'block');
					},
					function () {
						$(this).find(id).css('display', 'none');
					}
			);
		},

		on: function (id) {

			return this.mouseenter(function () {
				$(this).find(id).css('display', 'block');
			})
		},

		off: function (id) {

			return this.mouseleave(function () {
				$(this).find(id).css('display', 'none');
			})
		},

		block: function () {

			return this.trigger('mouseleave').unbind('mouseenter');
		}

	};

	$.fn.on_off = function (method) {

		if (methods[method]) {
			return methods[ method ].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		} else {
			$.error('����� "' + method + '" �� ������ � ������� jQuery.on_off');
			return false;
		}
	};
})(jQuery);
