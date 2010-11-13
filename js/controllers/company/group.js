// It's group thing

App.modules.company.group = {
		init: function() {
			
		},

		view: function() {

			var addUserForm=$('#adduserform');
			var addUserInput=addUserForm.find("#addusermail");
			var addUserBtn=addUserForm.find("input[type=submit]");
			var resetForm = function() {
				addUserInput.attr("disabled","");
				addUserBtn.attr("disabled","");
				addUserInput.val("");
			}
			var loadingForm = function() {
				addUserInput.attr("disabled","disabled");
				addUserBtn.attr("disabled","disabled");
			}

			addUserForm.submit(function() {
				var form=$(this);
				var input=form.find("#addusermail");
				var mail=input.val();
				var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
				if (!emailPattern.test(mail)) {
					alert("Invalid e-mail address");
					input.focus();
					return false;
				}
				loadingForm();
				$.ajax({
					url: form.attr('action'),
					type: "POST",
					data: {mail: mail},
					success: function(response) {
						response=jQuery.parseJSON(response);
						if (response.status=="success") {
							var row=$(response.row);
							// Initialize AJAX checkbox
							App.widgets.SubscriptionCheckbox.init_cb({container: row,
								user_id: response.user_id, group_id: response.group_id});
							// Hack for table animation
							var cols=row.find("td").wrapInner('<div style="display:none"/>');
							$("#content table tr:last").after(row);
							cols.parent().find("td > div").slideDown("700", function() {
								var div=$(this);
								div.replaceWith(div.contents());
							});
							resetForm();
						} else {
							alert(response.message);
							resetForm();
						}
					},
					error: function() {
						alert("Something's not right, reloading");
						window.location.reload(false);
					}
				});
				return false;
			});
		}
};
