
App.modules.company.message = {
	init_message_form: function() {
		var form=$('form#message-form');
		var msgRepeatType=$('#msgRepeatType');
		var msgRepeatEvery=$('#msgRepeatEvery');
		var msgRepeatEveryLabels={
			"daily": "Repeat every how many days?",
			"weekly": "Repeat every how many weeks?",
			"monthly": "Repeat every how many months?",
			"yearly": "Repeat every how many years?"
		};
		var msgScheduleTypeInputs=$('#schedule_type_row input[type=radio]');
		var msgScheduleFields=$('#schedule_fields');
		var GroupsInput=$('#sendselectgroup input[type=checkbox]');

		var updateRepeatLabel=function() {
			var type=msgRepeatType.val();
			if (type=='none') {
				form.find('.repeat_fields').slideUp('fast');
			} else {
				msgRepeatEvery.parent("div").find("label").html(msgRepeatEveryLabels[type]);
				form.find('.repeat_fields').slideDown('slow');
			}
		};

		msgScheduleTypeInputs.click(function() {
			var val=$(this).val();
			switch (val) {
				case "schedule":
					msgScheduleFields.slideDown();
					break;
				default:
				case "now":
					msgScheduleFields.slideUp('fast');
					break;
			}
		});
		msgScheduleTypeInputs.filter(":checked").click();

		msgRepeatType.change(updateRepeatLabel);
		updateRepeatLabel();
		
		form.find("#selectgrouplinks a:eq(0)").click(function() {
			GroupsInput.each(function() {
				$(this).attr('checked','checked');
			});
			return false;
		});
		form.find("#selectgrouplinks a:eq(1)").click(function() {
			GroupsInput.each(function() {
				$(this).attr('checked','');
			});
			return false;
		});
	},
	
	create: function() {
		App.modules.company.message.init_message_form();
	},
	
	edit: function() {
		App.modules.company.message.init_message_form();
	}
};