<?php

?>
<h2><?php echo $group->title?></h2>
<p><?php echo $group->description?></p>

<table width="100%" border="1">
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
