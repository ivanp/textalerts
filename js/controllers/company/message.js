
App.modules.company.message = {
	create: function() {
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
		$('.timepick').timepicker({
			showMinute: false
		});
		$('.datepick').datepicker({dateFormat:"yy-mm-dd"});
		$('.datetimepick').datetimepicker({
			dateFormat:"yy-mm-dd",
			timeFormat:"hh:00",
			showMinute:false
		});
	}
};