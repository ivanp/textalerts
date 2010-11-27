
App.widgets.SubscriptionCheckbox = {
	params: {},

	init: function(vars) {
		params=vars;
	},

	/**
	 * cb_info.container	Row containing checkboxes
	 * cb_info.group_id		Group ID
	 * cb_info.user_id		User ID
	 */
	init_cb: function(cb_info) {
		var container = $(cb_info['container']);
		var cb_mail = container.find('input.cb_mail');
		cb_mail.click(function() {
			var loading_img = $('<img class="loading" src="/images/loading.gif"/>');
			cb_mail.hide();
			cb_mail.before(loading_img);
			$.ajax({
				url: params.url,
				type: 'POST',
				data: {
					group_id: cb_info.group_id,
					user_id: cb_info.user_id,
					type: "mail",
					command: cb_mail.attr('checked') ? "subscribe" : "unsubscribe"
				},
				success: function() {
					cb_mail.show();
					loading_img.remove();
				},
				error: function() {
					alert("Error - please try again");
					cb_mail.attr('checked', cb_mail.attr('checked') ? "" : "checked");
					loading_img.remove();
					cb_mail.show();
				}
			});
		});

		var cb_text = container.find('input.cb_text');
		cb_text.click(function() {
			var loading_img = $('<img class="loading" src="/images/loading.gif"/>');
			cb_text.hide();
			cb_text.before(loading_img);
			$.ajax({
				url: params.url,
				type: 'POST',
				data: {
					group_id: cb_info.group_id,
					user_id: cb_info.user_id,
					type: "text",
					command: cb_text.attr('checked') ? "subscribe" : "unsubscribe"
				},
				success: function() {
					cb_text.show();
					loading_img.remove();
				},
				error: function() {
					alert("Error - please try again");
					cb_text.attr('checked', cb_text.attr('checked') ? "" : "checked");
					loading_img.remove();
					cb_text.show();
				}
			});
		});
	}
};
