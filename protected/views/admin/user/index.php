<?php
$this->pageTitle=Yii::app()->name . ' - Users';
$this->breadcrumbs=array(
	'Users',
);
?>
<h1>Users</h1>
<table width="100%">
	<thead>
		<tr>
			<th>ID#</th>
			<th>Name</th>
			<th>E-mail</th>
			<th>Created</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($users as $user) { ?>
		<tr>
			<td><?php echo $user->id?></td>
			<td><a href="<?php echo $this->createUrl('user/edit', array('id'=>$user->id))?>"><?php echo $user->getDisplayName()?></a></td>
			<td><?php echo $user->email?></td>
			<td><?php echo $user->created?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>