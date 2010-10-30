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
			<th>Destinations</th>
			<th>Status</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($messages as $msg) { ?>
		<tr>
			<td><?php echo $msg->id?></td>
			<td><?php echo htmlspecialchars($msg->body)?></td>
			<td><?php echo $msg->created?></td>
			<td><?php echo htmlspecialchars($msg->group->title)?></td>
			<td><?php echo $msg->status?></td>
		</tr>
		<?php } ?>
	</tbody>

</table>