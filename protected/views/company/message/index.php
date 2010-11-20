<?php
$this->pageTitle=Yii::app()->name . ' - Messages';
$this->breadcrumbs=array(
	'Messages',
);
?>
<h1>Messages</h1>

<?php if(Yii::app()->user->hasFlash('message')):?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('message'); ?>
    </div>
<?php endif; ?>

<table width="100%">
	<thead>
		<tr>
			<th>ID#</th>
			<th>Message</th>
			<th>Created</th>
			<th>Groups</th>
			<th>Status</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($messages as $msg) { ?>
		<tr>
			<td><?php echo $msg->id?></td>
			<td><?php echo htmlspecialchars($msg->body)?></td>
			<td><?php echo date('Y-m-d H:i:s', $msg->created_on)?></td>
			<td>&nbsp;</td>
			<td><?php echo $msg->status?></td>
		</tr>
		<?php } ?>
	</tbody>

</table>