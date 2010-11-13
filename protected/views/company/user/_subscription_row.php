<tr>
	<td>
		<?php if ($this->company->isAdministrator(User::getLoggedUser())): ?>
		<a href="<?php echo $group->createViewUrl()?>"><?php echo CHtml::encode($group->title)?></a>
		<?php else: ?>
		<?php echo CHtml::encode($group->title)?>
		<?php endif; ?>
	</td>
	<td>
		<?php
			$this->widget('application.components.widgets.GroupSubscriptionCheckboxes', array('group'=>$group,'user'=>$user));
		?>
	</td>
</tr>