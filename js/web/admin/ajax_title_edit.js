$(function () {

	var blockList = $('.list-title');

	// ui sortable
	blockList.sortable({
		axis: 'y',				// Разрешено только вертикальное перемещение
		update: function () {		// Вызываемая после перестройки функция

			// Метод toArray возвращает массив с перемещаемыми элементами
			var arr = blockList.sortable('toArray');

			// заменить htad на id
			arr = $.map(arr, function (val, key) {
				return val.replace('head-', '');
			});

			// Saving with AJAX
			$.get('/src/lib/ajaxSite/titleEdit.php', {action: 'rearrange', positions: arr});
		},

		/* fix: */
		stop: function (e, ui) {
			ui.item.removeAttr("style");
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

				$.get("/src/lib/ajaxSite/titleEdit.php", {
					"action": "delete",
					"id"    : currentBlock.data('id')
				}, function (msg) {
					if (1 == msg) {
						log('Запись успешно удалена.');

					} else {
						log('Ошибка удаления записи.');
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


	// альтернативное управление меню и ajax в админке:

	blockList.on("click", "a.navlink" , function(e){
		$(".tabs").find('.tab-content').hide();
		$($(this).attr('href')).fadeIn(300);
		$(this).parent().addClass('selected').siblings().removeClass('selected');

		$('ul.list-title').ajax_load('load', {
			'url'    : '/src/lib/ajaxSite/ajaxLoad.php', // адрес скрипта
			'id_load': '#pageContent', // id для загрузки ответа сервера (контента)
			'type'   : 'GET', // тип вызова
			'header' : 'Content-Type: application/json; charset=utf-8;', // посылаемый заголовок
			'data'   : {'location': window.location.pathname, id: this.hash}  // массив посылаемого к серверу запроса
		});
   // log("this.hash = " + this.hash);
	 e.preventDefault();
	});


	// При возникновении двойной щелчок, просто имитировать нажатие на кнопку редактирования:
	blockList.on('dblclick', 'li', function () {
		$(this).find('a.edit').click();
	});


	blockList.on('click', 'a', function (e) {

		currentBlock = $(e.target).closest('li');
		// для корректного удаления блока из бызы
		currentBlock.data('id', currentBlock.attr('id').replace('head-', ''));

    // отключить действие по умолчанию тега a
		e.preventDefault();
	});


	// Прослушивание за клик по кнопке удаления:
	blockList.on('click', 'a.delete', function (e) {
		$("#dialog-confirm").dialog('open');
		e.preventDefault();
	});


	// переключает видимость блока actions при входе в редактор
	blockList.find('li').on_off('init', 'div.actions');


	//Прослушивание за клик по кнопке редактирования
	blockList.on('click', 'a.edit', function () {

		var container = currentBlock.find('.navlink');

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
		//Блокирование кнопок редактирования и удаления, если поле ввода уже открыто:
		currentBlock.on_off('block');

	});

	//Отменить редактирование:
	blockList.on('click', 'a.discardChanges', function (e) {
		currentBlock.find('.navlink')
				.text(currentBlock.data('origText'))
				.end()
				.removeData('origText');
		e.preventDefault();
		currentBlock.on_off('on', '.actions').find('div.ok-cancel').remove();
	});

	// Сохранить:
	blockList.on('click', 'a.saveChanges', function (e) {
		var text = currentBlock.find("input[type=text]").val();

		$.get("/src/lib/ajaxSite/titleEdit.php", {'action': 'edit', 'id': currentBlock.data('id'), 'text': text});

		currentBlock.removeData('origText')
				.find(".navlink")
				.text(text);
		e.preventDefault();
		currentBlock.on_off('on', '.actions').find('div.ok-cancel').remove();
	});

	// Добавить новое
	var timestamp = 0;
	$('#addButton').click(function (e) {

		// Добавление не чаще 1 раза в секунду
		if ((new Date()).getTime() - timestamp < 1000) return false;

		$.get("/src/lib/ajaxSite/titleEdit.php", {
			'action': 'new',
			'text'  : 'Новый раздел',
			'rand'  : Math.random()
		}, function (msg) {

			// Добавление нового блока , вывод его на экран и инициализация кнопок редактирования и удаления:
			$(msg).hide().appendTo('.list-title').fadeIn().on_off('init', 'div.actions');
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
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		} else {
			$.error('Метод "' + method + '" не найден в плагине jQuery.on_off');
			return false;
		}
	};
})(jQuery);
