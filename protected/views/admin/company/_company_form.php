<div class="form">

	<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'company-form',
	'enableAjaxValidation'=>false,
));
	?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>40,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ownerEmail'); ?>
		<?php echo $form->textField($model,'ownerEmail',array('size'=>40,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'ownerEmail'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'host'); ?>
		<?php echo $form->textField($model,'host',array('size'=>10,'maxlength'=>255)); ?>
		<span id="company-url"><?php echo '.'.Yii::app()->params['domain'] ?></span>
		<?php echo $form->error($model,'host'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Create new company'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div>