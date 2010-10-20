<?php
switch ($type) {
	case 'mail':
		$groups = Group::getAvailableMessageGroups();
		break;
	case 'text':
		$groups = Group::getAvailableTextGroups();
		break;
}
?>
	<tr class="group_add">
		<td colspan="2">
			<?php if (isset($groups) && count($groups)): ?>
			<select class="select_add_group">
				<option>Subscribe to a group:</option>
				<?php foreach ($groups as $group): ?>
				<option value="<?php echo $this->createUrl('site/subscribe', array('type' => $type, 'group_id' => $group->message_group_id));?>"><?php echo $group->group_name; ?></option>
				<?php endforeach; ?>
			</select>
			<?php else: ?>
			<span class="add_info">You have subscribed to all group alerts</span>
			<?php endif; ?>
		</td>
	</tr>