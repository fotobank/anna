$(function () {

	var blockList = $('.list-categorii');

	blockList.sortable({
		axis  : 'y',				// Разрешено только вертикальное перемещение
		update: function () {		// Вызываемая после перестройки функция

			// Метод toArray возвращает массив с перемещаемыми элементами
			var arr = blockList.sortable('toArray');

			// заменить htad на id
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


	//Глобальная переменная, содержащая объект JQuery
	// текущий элемент текстового блока:
	var currentBlock;

	// Настройка диалогового окна подтверждения удаления
	$("#dialog-confirm").dialog({
		resizable: false,
		modal    : true,
		autoOpen : false,
		buttons  : {
			'Удалить': function () {

				$.get("/classes/ajaxSite/ajax_title_edit.php", {"action": "delete", "id": currentBlock.data('id')}, function (msg) {
					if (1 == msg) {
						console.log('Запись успешно удалена.');
					} else {
						console.log('Ошибка удаления записи.');
					}

					currentBlock.fadeOut('fast');
				});

				$(this).dialog('close');
			},
			'Отмена' : function () {
				$(this).dialog('close');
			}
		}
	});

	// При возникновении двойной щелчок, просто имитировать нажатие на кнопку редактирования:
	blockList.on('dblclick', 'li', function () {
		$(this).find('a.edit').click();
	});


	blockList.on('click', 'a', function (e) {

		currentBlock = $(this).closest('li');

		currentBlock.data('id', currentBlock.attr('id').replace('head-', ''));

		e.preventDefault();
	});


	// Прослушивание за клик по кнопке удаления:
	blockList.on('click', 'a.delete', function (e) {
		$("#dialog-confirm").dialog('open');
		e.preventDefault();
	});

	// переключает видимость блока actions при входе в редактор
	blockList.find('li').on_off('init', '.actions');


	//Прослушивание за клик по кнопке редактирования
	blockList.on('click', 'a.edit', function () {

		var container = currentBlock.find('.text');

		if (!currentBlock.data('origText')) {
			// Сохранение текущего текста блока, чтобы мы могли
			// восстановить его позже, если пользователь не учитывает изменения:

			currentBlock.data('origText', container.text());
		}
		else {
			return false;
		}

		$('<input type="text">').val(container.text()).appendTo(container.empty());

		// Добавление параметров сохранения и отмены ссылки:
		currentBlock.append(
				'<div class="ok-cancel">' +
						'<a title="Сохранить." class="saveChanges"><img class="ok" src="/images/ok.png" /></a>' +
						'<a title="Отменить редактирование." class="discardChanges"><img class="return" src="/images/return.png" /></a>' +
						'</div>'
		);
		//Блокирование кнопок редактирования, если поле ввода уже открыто:
		 currentBlock.on_off('block');

	});

	//Отменить редактирование:
	blockList.on('click', 'a.discardChanges', function (e) {
		currentBlock.find('.text')
				.text(currentBlock.data('origText'))
				.end()
				.removeData('origText');
		e.preventDefault();
		currentBlock.on_off('on', '.actions').find( 'div.ok-cancel' ).remove();
	});

	// Сохранить:
	blockList.on('click', 'a.saveChanges', function (e) {
		var text = currentBlock.find("input[type=text]").val();

		$.get("/classes/ajaxSite/ajax_title_edit.php", {'action': 'edit', 'id': currentBlock.data('id'), 'text': text});

		currentBlock.removeData('origText')
				.find(".text")
				.text(text);
		e.preventDefault();
		currentBlock.on_off('on', '.actions').find( 'div.ok-cancel' ).remove();
	});

	// Добавить новое
	var timestamp = 0;
	$('#addButton').click(function (e) {

		// Добавление не чаще 1 раза в секунду
		if ((new Date()).getTime() - timestamp < 1000) return false;

		$.get("/classes/ajaxSite/ajax_title_edit.php", {'action': 'new', 'text': 'Новый раздел.', 'rand': Math.random()}, function (msg) {

			// Добавление нового блока и вывод его на экран:
			$(msg).hide().appendTo('.list-categorii').fadeIn();
		});

		timestamp = (new Date()).getTime();

		e.preventDefault();
	});

});




(function ($) {

	var methods = {

		// инициализация переключает видимость блока
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
			});
		},

		off: function (id) {

			return this.mouseleave(function () {
				$(this).find(id).css('display', 'none');
			});
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
			$.error('Метод "' + method + '" не найден в плагине jQuery.on_off');
			return false;
		}
	};
})(jQuery);
