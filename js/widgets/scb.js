
App.widgets.SubscriptionCheckbox = {
	init: function() {
		$(document).ready(function() {
			var conts = $('span.groupsubcb');
			if (conts.length > 0) {
				conts.find('input[type=checkbox]').each(function() {
					var obj = $(this);
					obj.click(function() {
						alert(obj.attr('checked'));
					});
				});
			}
		});
	},

	init_cb: function(cb_info) {
		var container = $(cb_info['container']);
		var cb_mail = container.find('input.cb_mail');
		cb_mail.click(function() {
			var loading_img = $('<img class="loading" src="/images/loading.gif"/>');
			var url = App.extendUrl((cb_mail.attr('checked')) ? cb_info['subUrl'] : cb_info['unsubUrl'],
				{type: 'mail'});
			cb_mail.hide();
			cb_mail.before(loading_img);
			$.ajax({
				url: url,
				type: 'POST',
				success: function() {
					cb_mail.show();
					loading_img.remove();
				},
				error: function() {
					cb_mail.show();
					loading_img.remove();
				}
			});


		});

		var cb_text = container.find('input.cb_text');
		cb_text.click(function() {
			var loading_img = $('<img class="loading" src="/images/loading.gif"/>');
			var url = App.extendUrl((cb_text.attr('checked')) ? cb_info['subUrl'] : cb_info['unsubUrl'],
				{type: 'text'});
			cb_text.hide();
			cb_text.before(loading_img);
			$.ajax({
				url: url,
				type: 'POST',
				success: function() {
					cb_text.show();
					loading_img.remove();
				},
				error: function() {
					cb_text.show();
					loading_img.remove();
				}
			});


		});
	}
};

//App.widgets.SubscriptionCheckbox.init();