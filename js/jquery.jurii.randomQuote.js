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

		// ������������� �������������� �����
		function initAutoUpdate() {
			setInterval(update, settings.interval * 1000);
		}

		// ������������� ����� �������� � ���������� ��������
		function setQuote(quote) {
			$(self).text(quote).trigger("quoteChanged", [quote]);
		}

		// �������� ��������� ������ �� ������� � ������������� ��
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
