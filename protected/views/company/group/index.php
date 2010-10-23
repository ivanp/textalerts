<?php
$roles = array('Member', 'Administrator', 'Sender', 'Non-member');
?>
<h2>Groups</h2>
<h4><a href="<?php echo $this->createUrl('group/create'); ?>">Create new group</a></h4>

<?php echo CHtml::beginForm(); ?>
<table width="100%" border="1">
	<thead>
		<tr>
			<th width="30%">Name</th>
			<th>Members</th>
			<th>Last message</th>
			<th>Role</th>
			<th>Subscription</th>
		</tr>
	</thead>
	<tbody>
		<?php $row = 0; ?>
		<?php foreach ($groups as $group): ?>
		<tr>
			<td><a href="<?php echo $group->createViewUrl()?>"><?php echo $group->title?></a></td>
			<td>-</td>
			<td><?php echo date('Y-m-d H:i:s', time()-mt_rand(3600, 86400*3)); ?></td>
			<td><?php echo $roles[mt_rand(0, count($roles)-1)] ?></td>
			<td>
				<?php
				$row++;
				$sm_name = "subscription_mail[$row]";
				$sm_id = "subscription_mail_$row";
				echo CHtml::checkBox($sm_name, false, array('id'=>$sm_id));
				echo CHtml::label('E-mail', $sm_id);
				$st_name = "subscription_text[$row]";
				$st_id = "subscription_text_$row";
				echo CHtml::checkBox($st_name, false, array('id'=>$st_id));
				echo CHtml::label('Text', $st_id);
				?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php echo CHtml::endForm(); ?>