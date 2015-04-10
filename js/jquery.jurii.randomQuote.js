/**
 * Created by Jurii on 10.04.2015.
 */

;(function($) {
	$.fn.randomQuote = function(options) {

		var self = this;

		if ($.isPlainObject(options)) {
			var settings = $.extend({
				interval: 10
			}, options || {});

			initAutoUpdate();

			return this;
		} else {
			switch (options) {
				case "update":
					update();
					break;
				default:
					break;
			}
		}

		// Инициализация автообновления цитат
		function initAutoUpdate() {
			setInterval(update, settings.interval * 1000);
		}

		// Устанавливает текст элемента в переданное значение
		function setQuote(quote) {
			$(self).text(quote).trigger("quoteChanged", [quote]);
		}

		// Получает случайную цитату от сервера и устанавилвает ее
		function update() {
			$.get(
					'/quote',
					{},
					function(response) {
						setQuote(response.quote);
					},
					'json'
			);
		}
	};
})(jQuery);
