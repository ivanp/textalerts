<?php
$this->pageTitle=Yii::app()->name . ' - View Event';
$this->breadcrumbs=array(
	'Calendar'=>array('/calendar/index'),
	'View Event'
);

$start=$default_occurrence->getStartDate();
$end=$default_occurrence->getEndDate();

$user=User::getLoggedUser();
$is_sender=$this->company->isSender($user);
$is_admin=$this->company->isAdministrator($user);

?>
<h1><?php echo CHtml::encode($event->subject);?></h1>
<?php if ($is_admin): ?>
	<p>
		<button onclick="location.href='<?php echo $this->createUrl('event/edit',array('id'=>$event->id))?>'">Edit this event</button>
		<button onclick="if(window.confirm('Are you sure you want to delete this event?')) { location.href='<?php echo $this->createUrl('event/delete',array('id'=>$event->id))?>' }">Delete this event</button>
	</p>
<?php endif; ?>
<?php if (strlen(trim($event->description))): ?>
<p><?php echo CHtml::encode($event->description);?></p>
<?php endif; ?>
<p>When: 

	<?php if ($event->time_type=='normal'):?>
		<?php if ($start->compare($end, Zend_Date::DATES) == 0): ?>
			<?php echo $start->get(Zend_Date::DATE_FULL)?> at
			<?php echo $start->get(Zend_Date::TIME_SHORT)?> to
			<?php echo $end->get(Zend_Date::TIME_SHORT)?>
		<?php else: ?>
			<?php echo $start->get(Zend_Date::DATE_FULL)?> to
			<?php echo $end->get(Zend_Date::DATE_FULL)?>
		<?php endif; ?>
	<?php else: ?>
		<?php echo $start->get(Zend_Date::DATE_FULL)?>
		<?php if ($start->compare($end, Zend_Date::DATES) <> 0): ?>
			to <?php echo $end->get(Zend_Date::DATE_FULL)?>
		<?php endif; ?>
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