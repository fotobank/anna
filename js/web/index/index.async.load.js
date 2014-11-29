/**
 * Created by Jurii on 17.08.14.
 */


// асинхронная загрузка галереи
/*YUI().use(
		'aui-io-request',
		function (Y) {
			Y.io.request(
					'inc/carosel.php',
					{
						dataType: 'html',
						on      : {
							success: function () {
								var data = this.get('responseData');
								//	Y.one('#new-gal').append('data');
								//	log(data);
								// передача данных
								Y.one('#new-gal').setHTML(data);

								// запуск карусели
								jQuery('#mycarousel')
										.jcarousel({
											horisontal: true,
											wrap      : 'circular',
											scroll    : 1,
											easing    : 'easeInOutBack',
											animation : 1200
										});
							}
						}
					}
			);
		}
);*/

/*
YUI().use(
		'aui-io-request',
		function (Y) {
			Y.io.request(
					'inc/social_icons.php',
					{
						dataType: 'html',
						on      : {
							success: function () {
								var data = this.get('responseData');
								Y.one('#social_icons').setHTML(data);

							}
						}
					}
			);
		}
);
*/

/*
YUI().use(
		'aui-io-request',
		function (Y) {
			Y.io.request(
					'inc/online_widget.php',
					{
						dataType: 'html',
						on      : {
							success: function () {
								var data = this.get('responseData');
								Y.one('#onlineWidget').setHTML(data);



							}
						}
					}
			);
		}
);*/
