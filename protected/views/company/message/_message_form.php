<div class="form">
<?php $form=$this->beginWidget('CompanyForm', array(
	'id'=>'message-form',
	'enableAjaxValidation'=>false,
));
	?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($message); ?>

	<fieldset>
		<h3>Recipients</h3>
	<div class="row">
		<?php echo $form->labelEx($message,'groups'); ?>
		<span id="sendselectgroup" class="oneliner"><?php echo $form->checkBoxList($message,'groups',$groups,array('prompt'=>'Select group:')); ?></span>
		<span id="selectgrouplinks"><a href="#">Select all</a> <a href="#">Deselect all</a></span>
		<?php echo $form->error($message,'groups'); ?>
	</div>
	</fieldset>

	<fieldset>
		<h3>Content</h3>
		<div class="row">
			<?php echo $form->labelEx($message,'body'); ?>
			<?php echo $form->textArea($message,'body',array('cols'=>40,'rows'=>4)); ?>
			<?php echo $form->error($message,'body'); ?>
		</div>
	</fieldset>

	<fieldset>
		<h3>Scheduling</h3>
		<div id="schedule_type_row" class="row">
			<?php echo $form->labelEx($message,'type'); ?>
			<span class="oneliner"><?php echo $form->radioButtonList($message,'type',Message::getTypeOptions(),array()); ?></span>
			<?php echo $form->error($message,'type'); ?>
		</div>

		<div id="schedule_fields">
			<div class="row">
				<?php echo $form->labelEx($message,'start'); ?>
				<?php echo $form->dateTimeField($message,'start'); ?>
				<?php echo $form->error($message,'start'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($message,'repeatType'); ?>
				<span class="oneliner"><?php echo $form->dropDownList($message,'repeatType',MessageSchedule::getRepeatTypes(),array('id'=>'msgRepeatType')); ?></span>
				<?php echo $form->error($message,'repeatType'); ?>
			</div>

			<div class="row repeat_fields">
				<?php echo $form->labelEx($message,'repeatEvery'); ?>
				<?php echo $form->dropDownList($message,'repeatEvery', MessageSchedule::getRepeatEveryDays(),array('id'=>'msgRepeatEvery')); ?>
				<?php echo $form->error($message,'repeatEvery'); ?>
			</div>

			<div class="row repeat_fields">
				<?php echo $form->labelEx($message,'repeatUntil'); ?>
				<?php echo $form->dateField($message,'repeatUntil'); ?>
				<?php echo $form->error($message,'repeatUntil'); ?>
			</div>
		</div>
	</fieldset>

	<div class="row buttons">
		<?php //echo CHtml::submitButton('Save Draft',array('name'=>'cmd_save')); ?> <?php echo CHtml::submitButton('Start Sending',array('name'=>'cmd_start')); ?>
	</div>

	

<?php $this->endWidget(); ?>
</div>