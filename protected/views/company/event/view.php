<?php
$this->pageTitle=Yii::app()->name . ' - View Event';
$this->breadcrumbs=array(
	'Calendar'=>array('/calendar/index'),
	'View Event'
);
?>
<h1><?php echo CHtml::encode($event->subject);?></h1>
<?php if (strlen(trim($event->description))): ?>
<p><?php echo CHtml::encode($event->description);?></p>
<?php endif; ?>
<p>When: <?php echo $default_occurrence->startdate?> 
	<?php if ($default_occurrence->startdate <> $default_occurrence->enddate): ?>
	‒ <?php echo $default_occurrence->enddate?>
	<?php endif; ?>
	<?php if ($default_occurrence->timetype=='normal'):?>
		at <?php echo $default_occurrence->starttime?> to <?php echo $default_occurrence->endtime; ?>
	<?php endif; ?>
</p>
<p>
	<?php if (isset($prev_occur)):?>
	<a href="<?php echo $this->createUrl('event/view',array('eid'=>$event->id,'oid'=>$prev_occur->id))?>">Previous Occurrence</a>
	<?php endif; ?>
	<?php if (isset($next_occur)):?>
	<a href="<?php echo $this->createUrl('event/view',array('eid'=>$event->id,'oid'=>$next_occur->id))?>">Next Occurrence</a>
	<?php endif; ?>
</p>