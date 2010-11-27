
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
		var schedule_inputs=form.find('.repeat_fields');
		var everylabels={};

		repeat_type.change(function() {
			if ($(this).val()!='never')
				schedule_inputs.slideDown();
			else
				schedule_inputs.slideUp();
		});

		time_type.change(function() {
			if ($(this).val()=='normal') {
				time_inputs.fadeIn();
			} else {
				time_inputs.fadeOut();
			}
		});


		time_type.change();
		repeat_type.change();
	}
};