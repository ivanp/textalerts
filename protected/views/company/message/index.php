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
			<td><?php
				?></td>
			<td><?php
				$prev_queue=$msg->getLastRunningQueue();
				$next_queue=$msg->getNextRunningQueue();

				$is_prev=$prev_queue instanceof MessageQueue;
				$is_next=$next_queue instanceof MessageQueue;

				if ($is_prev)
				{
					$count=$prev_queue->message_count;
					$total=$count->total_text + $count->total_mail;
					$sent=$count->sent_text + $count->sent_mail;
					$failed=$count->failed_text + $count->failed_mail;
					$prev_dt=new Zend_Date($prev_queue->schedule_on,Zend_Date::TIMESTAMP);
					$prev_msg=sprintf('Sending process started on %s.',
									$prev_dt->get(Zend_Date::RFC_2822));
				}
				if ($is_next)
				{
					$next_dt=new Zend_Date($next_queue->schedule_on,Zend_Date::TIMESTAMP);
					$next_dt_fmt=$next_dt->get(Zend_Date::RFC_2822);
				}

				if ($is_prev && $is_next)
				{
					// display prev & next
					echo $prev_msg;
					echo sprintf("<br/>Next queue ETA: %s</p>",$next_dt_fmt);
				}
				elseif ($is_prev && !$is_next)
				{
					echo $prev_msg;
					if ($sent+$failed>=$total)
					{
						echo "<br/><strong>Process completed.</strong>";
					}
				}
				elseif (!$is_prev && $is_next)
				{
					echo sprintf("Process will be started on: %s",$next_dt_fmt);
				}
				else
				{
					echo 'Pending process';
				}

			?></td>
			<td>
				<a href="<?php echo $this->createUrl('message/edit',array('id'=>$msg->id))?>">Edit</a> | 
				<a onclick="if (window.confirm('Are you sure you want to delete this message?')) { location.href= '<?php echo $this->createUrl('message/delete',array('id'=>$msg->id))?>';} else { return false }" href="#">Delete</a>
			</td>
		</tr>
		<?php } ?>
	</tbody>

</table>

<?php if ($pages->pageCount > 1): ?>
<div class="pagelinks">
<?$this->widget('CLinkPager', array(
    'pages' => $pages,
))?>
</div>
<?php endif; ?>
