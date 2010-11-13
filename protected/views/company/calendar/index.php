<?php
$this->pageTitle=Yii::app()->name . ' - Calendar';
$this->breadcrumbs=array(
	'Calendar',
);

$user=User::getLoggedUser();
$is_sender=$this->company->isSender($user);
$is_admin=$this->company->isAdministrator($user);

?>
<h1>Calendar</h1>
<?php if(Yii::app()->user->hasFlash('calendar-index')): ?>
<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('calendar-index'); ?>
</div>
<?php endif; ?>

<p>

<?php if ($is_admin): ?>
	<button onclick="location.href='<?php echo $this->createUrl('event/create')?>'">Create New Event</button>
<?php endif; ?>

</p>

<div style="width:900px;margin: 0 auto;" id="fullcalendar"></div>

<script>
<?php
ob_start();
?>
$("#fullcalendar").fullCalendar({
	events: "<?php echo $this->createAbsoluteUrl('/calendar/events')?>",
	header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			}
});
<?php
$js=ob_get_contents();
ob_end_clean();?>
</script>
<?php
$cs=Yii::app()->getClientScript();
$cs->registerScript('calendar-index',$js,  CClientScript::POS_READY);

$cs->registerCssFile(Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot').'/css/fullcalendar.css'));
$cs->registerScriptFile(Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot').'/js/fullcalendar.min.js'));
?>
