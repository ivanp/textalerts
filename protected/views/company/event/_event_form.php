<div class="form">

<?php $form=$this->beginWidget('CompanyForm', array('id'=>'event_form','htmlOptions'=>array('autocomplete'=>'off'))); ?>

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
		<?php echo $form->dropDownList($event,'time_type',$event->getTimeTypes(),array('id'=>'time_type')); ?>
		<?php echo $form->error($event,'time_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($event,'start'); ?>
		<span class="oneliner">
			<?php echo $form->dateField($event,'start'); ?>
			<?php echo $form->timeField($event,'start_time',array('class'=>'time_fields')); ?>
		</span>
		<?php echo $form->error($event,'start'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($event,'end'); ?>
		<span class="oneliner">
			<?php echo $form->dateField($event,'end'); ?>
			<?php echo $form->timeField($event,'end_time',array('class'=>'time_fields')); ?>
		</span>
		<?php echo $form->error($event,'end'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($event,'repeat_type'); ?>
		<?php echo $form->dropDownList($event,'repeat_type',$event->getRepeatTypes(),array('id'=>'repeat_type')); ?>
		<?php echo $form->error($event,'repeat_type'); ?>
	</div>

	<div class="row repeat_fields">
		<?php echo $form->labelEx($event,'repeat_every'); ?>
		<?php echo $form->dropDownList($event,'repeat_every',$event->getRepeatEveryDays()); ?>
		<?php echo $form->error($event,'repeat_every'); ?>
	</div>

	<div class="row repeat_fields">
		<?php echo $form->labelEx($event,'repeat_until'); ?>
		<?php echo $form->dateField($event,'repeat_until',array('id'=>'repeat_until')); ?>
		<?php echo $form->error($event,'repeat_until'); ?>
	</div>
</fieldset>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Create Event'); ?>
	</div>

<?php $this->endWidget(); ?>

</div>

<?php


$script="App.modules.company.event.init_event_form($('#event_form'))";


$cs = Yii::app()->getClientScript();
$cs->registerScript('event_form', $script, CClientScript::POS_READY);
?>