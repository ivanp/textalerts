<?php

?>
<h2><?php echo $group->title?></h2>
<?php if (strlen($group->description)): ?>
<p><?php echo $group->description?></p>
<?php endif; ?>

<p>[ <a href="<?php echo $this->createUrl('group/edit',array('id'=>$group->id))?>">Edit</a> | <a href="<?php echo $this->createUrl('message/create',array('group_id'=>$group->id))?>">Send message</a> ]</p>

<table width="100%" border="1">
	<caption>Users</caption>
	<thead>
		<tr>
			<th>Name</th>
			<th>E-mail</th>
			<th>Subscription</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($group->subscribers as $subscriber) {
			$user = $subscriber->user;
		?>
		<tr>
			<td><a href="<?php echo $user->createCompanyViewUrl($company)?>"><?php echo $user->getDisplayName() ?></a></td>
			<td><?php echo $user->email ?></td>
			<td></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
