<?php
$unsubscribe_url = $this->createUrl(
		'site/unsubscribe',
		array('type' => $type, 'group_id' => $group->message_group_id)
	);
?><tr class="group_row">
		<td>
			<?php echo $group->group_name; ?>
		</td>
		<td>
			<?php echo CHtml::link('remove', $unsubscribe_url, array('class' => 'remove_link')); ?>
		</td>
	</tr>