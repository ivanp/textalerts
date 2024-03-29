<?php
$this->pageTitle=Yii::app()->name . ' - Create New Event';
$this->breadcrumbs=array(
	'Calendar'=>array('/calendar/index'),
	'Create New Event'
);

$cs=Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot').'/js/jquery-ui-timepicker-addon.min.js'), CClientScript::POS_HEAD);
?>
<h1>Create New Event</h1>

<?php if(Yii::app()->user->hasFlash('event-create')): ?>
<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('event-create'); ?>
</div>
<?php endif; ?>

<?php echo $this->renderPartial('_event_form', array('event'=>$event)); ?>

