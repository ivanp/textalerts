
App.modules.company.event = {
	init: function() {
			
	},

	create: function() {
		
	},

	edit: function() {
		
	},

	init_event_form: function(form) {
		var time_type=form.find('#time_type');
		var repeat_type=form.find('#repeat_type');
		var time_inputs=form.find('.time_fields');
		var dt_start=form.find('.dt_fields_start input');
		var dt_end=form.find('.dt_fields_end input');
		var schedule_inputs=form.find('.repeat_fields');
		var msg_repeat_labels={
			"daily": "Repeat every how many days?",
			"weekly": "Repeat every how many weeks?",
			"monthly": "Repeat every how many months?",
			"yearly": "Repeat every how many years?"
		};
		
		// Change "repeat every" label accordingly
		repeat_type.change(function() { 
			var val=$(this).val();
			if (val!='never')
			{
				schedule_inputs.slideDown();
				form.find('div.repeat_every_label label').html(msg_repeat_labels[val]);
			}
			else
				schedule_inputs.slideUp();
		});

		// Show time fields only on normal mode
		time_type.change(function() {
			if ($(this).val()=='normal') 
				time_inputs.fadeIn();
			else 
				time_inputs.fadeOut();
		}); 
		
		// When start date changed, set end date with same value
		dt_start.eq(0).change(function() {
			dt_end.eq(0).val($(this).val());
		});
		
		// When start time changed, end time will be start time +1 hour
		dt_start.eq(1).change(function() {
			var h=Date.parse($(this).val());
			h.addHours(1);
			dt_end.eq(1).val(h.toString("hh:mm tt").toLowerCase())
		});

		time_type.change();
		repeat_type.change();
	}
};