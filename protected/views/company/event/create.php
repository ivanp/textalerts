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

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array('htmlOptions'=>array('autocomplete'=>'off'))); ?>

	<?php echo $form->errorSummary($event); ?>

<fieldset>
	<h3>What</h3>
	<div class="row">
		<?php echo $form->labelEx($event,'subject'); ?>
		<?php echo $form->textField($event,'subject',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($event,'subject'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($event,'description'); ?>
		<?php echo $form->textArea($event,'description',array('rows'=>4,'cols'=>60)); ?>
		<?php echo $form->error($event,'description'); ?>
	</div>

</fieldset>

<fieldset>
	<h3>When</h3>
	<div class="row">
		<?php echo $form->labelEx($event,'time_type'); ?>
		<?php echo $form->dropDownList($event,'time_type',$event->getTimeTypes()); ?>
		<?php echo $form->error($event,'time_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($event,'startdate'); ?>
		<span class="oneliner">
			<?php echo $form->textField($event,'startdate',array('size'=>10,'maxlength'=>10,'class'=>'datepick')); ?>
			<?php echo $form->textField($event,'starttime',array('size'=>5,'maxlength'=>5,'class'=>'timepick eventdt')); ?>
		</span>
		<?php echo $form->error($event,'startdate'); ?>
		<?php echo $form->error($event,'starttime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($event,'enddate'); ?>
		<span class="oneliner">
			<?php echo $form->textField($event,'enddate',array('size'=>10,'maxlength'=>10,'class'=>'datepick')); ?>
			<?php echo $form->textField($event,'endtime',array('size'=>5,'maxlength'=>5,'class'=>'timepick eventdt')); ?>
		</span>
		<?php echo $form->error($event,'enddate'); ?>
		<?php echo $form->error($event,'endtime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($event,'repeat_type'); ?>
		<?php echo $form->dropDownList($event,'repeat_type',$event->getRepeatTypes()); ?>
		<?php echo $form->error($event,'repeat_type'); ?>
	</div>

	<div class="row repeat_fields">
		<?php echo $form->labelEx($event,'repeat_every'); ?>
		<?php echo $form->dropDownList($event,'repeat_every',$event->getRepeatEveryDays()); ?>
		<?php echo $form->error($event,'repeat_every'); ?>
	</div>

	<div class="row repeat_fields">
		<?php echo $form->labelEx($event,'repeat_until'); ?>
		<?php echo $form->textField($event,'repeat_until',array('size'=>10,'maxlength'=>10,'class'=>'datepick')); ?>
		<?php echo $form->error($event,'repeat_until'); ?>
	</div>
</fieldset>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Create Event'); ?>
	</div>

<?php $this->endWidget(); ?>

</div>

<?php

$attr = 'repeat_type';
$repeat_id = CHtml::getIdByName(CHtml::resolveName($event,$attr));
$attr = 'time_type';
$attr_id = CHtml::getIdByName(CHtml::resolveName($event,$attr));

$script =<<<SCRIPT

\$('#$repeat_id').change(function() {
	if ($(this).val()!='never')
		\$('.repeat_fields').slideDown();
	else
		\$('.repeat_fields').slideUp();
});

\$('#$attr_id').change(function() {
	if ($(this).val()=='normal')
		\$('input.eventdt').fadeIn();
	else
		\$('input.eventdt').fadeOut();
});

	
$("#$repeat_id").change();
$("#$attr_id").change();

$('.timepick').timepicker({});

$('.datepick').datepicker({dateFormat:"yy-mm-dd"});

SCRIPT;


$cs = Yii::app()->getClientScript();
$cs->registerScript('createEvent', $script, CClientScript::POS_READY);
?>