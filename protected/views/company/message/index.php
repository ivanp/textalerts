<?php
$this->pageTitle=Yii::app()->name . ' - Messages';
$this->breadcrumbs=array(
	'Messages',
);
?>
<h1>Messages</h1>

<p>
	<button onclick="location.href='<?php echo $this->createUrl('message/create')?>'">Create new message</button>
</p>
 
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
			<th>Actions</th>
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
			<td>
				<a href="<?php echo $this->createUrl('message/edit',array('id'=>$msg->id))?>">Edit</a> | 
				<a onclick="if (window.confirm('Are you sure you want to delete this message?')) { location.href= '<?php echo $this->createUrl('message/delete',array('id'=>$msg->id))?>';} else { return false }" href="#">Delete</a>
			</td>
		</tr>
		<?php } ?>
	</tbody>

</table>