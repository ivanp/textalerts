<?php

$this->breadcrumbs=array(
	'Dashboard',
);


?>
<h1>Welcome!</h1>

<?php if(Yii::app()->user->hasFlash('dashboard')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('dashboard'); ?>
</div>

<?php endif; ?>



<script type="text/javascript">

App.check_empty_table = function() {
	$('table.table_alerts').each(function() {
		var table = $(this);
		if (table.find('tr.group_row').length) {
			table.find('tr.empty').hide();
		} else {
			table.find('tr.empty').show();
		}
	});
}

App.init_add_groups = function(container) {
	var container = $(container);
	container.find('select.select_add_group').change(function() {
		var sel = $(this);
		var table = sel.parents("table");
		var td = sel.parents("td");
		var oldhtml = td.html();
		td.html('<div class="center"><img src="<?php echo Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot').'/images/loading.gif') ?>"/></div>');
		$.ajax({
			url : sel.val(),
			type : 'POST',
			data : {'ajax' : 1},
			success : function(response) {
					response = $(response);
					td.parents("tr").remove();
					table.append(response);
					App.init_add_groups(response);
					App.init_del_groups(response);
					App.check_empty_table();
				},
				error		: function() {
					alert('Oops... something is wrong with the request. Please try again.');
					tr.find('td:eq(1)').html(oldhtml);
				}
		});
		return false;
	});

}

App.init_del_groups = function(container) {
	container = $(container);
	container.find('a.remove_link').click(function() {
		var link = $(this);
		var table = link.parents("table");
		if (window.confirm('Are you sure you want to unsubscribe from this group?')) {
			var tr = link.parents('tr');
			var oldhtml = tr.find('td:eq(1)').html();
			tr.find('td:eq(1)').html('<div class="center"><img src="<?php echo Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot').'/images/loading.gif') ?>"/></div>')
			$.ajax({
				url : link.attr('href'),
				type : 'POST',
				data : {'ajax' : 1},
				success : function(response) {
					response = $(response);
					tr.fadeOut('fast', function() {
						tr.remove();
						App.check_empty_table();
					});
					table.find("tr:last").remove();
					table.append(response);
					App.init_add_groups(response);
				},
				error		: function() {
					alert('Oops... something is wrong with the request. Please try again.');
					tr.find('td:eq(1)').html(oldhtml);
				}
			});
		}
		return false;
	});
}


	$(document).ready(function() {
		$('table.table_alerts').each(function() {
			var table = $(this);
			App.init_add_groups(table);
			App.init_del_groups(table);
		});

		App.check_empty_table();
	});

</script>