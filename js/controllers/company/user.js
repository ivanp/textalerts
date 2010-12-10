

App.modules.company.user = {
	params: {},

	init: function() {
	},

	subscription: function() {
		var form=$('#addgroupform');
		var table=$('#subscribedgroups');
		form.find("input[type=submit]").remove(); // remove submit button
		form.find('select').change(function() {
			var select=$(this);
			var group_id=select.val();
			
			if (!group_id) {
				alert('Please select group');
				return false;
			}
			select.attr('disabled','disabled');

			$.ajax({
				url: form.attr('action'),
				type: 'POST',
				data: {
					group_id: group_id
				},
				success: function(response) {
					response=jQuery.parseJSON(response);
					var row=$(response.row);
					if (response.status=="success") {
						// Initialize AJAX checkbox
						App.widgets.SubscriptionCheckbox.init_cb({container: row,
									user_id: response.user_id, group_id: response.group_id});
						var cols=row.find("td").wrapInner('<div style="display:none"/>');
						table.find("tr:last").after(row);
						cols.parent().find("td > div").slideDown("700", function() {
							var div=$(this);
							div.replaceWith(div.contents());
						});
						select.removeAttr('disabled');
						select.val('');
					} else {
						alert(response.message);
						select.removeAttr('disabled');
						select.val('');
					}

				},
				error: function() {
					alert("Something's not right, reloading");
					window.location.reload(false);
				}
			});
		});
	}
};