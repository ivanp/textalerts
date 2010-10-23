<?php
$this->pageTitle=Yii::app()->name . ' - Company Settings';
$this->breadcrumbs=array(
	'Contact',
);
?>
<h1>Company Settings</h1>

<?php if(Yii::app()->user->hasFlash('contact')): ?>
<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>
<?php endif; ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm'); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'headingText'); ?>
		<?php echo $form->textField($model,'headingText',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'titleText'); ?>
		<?php echo $form->textField($model,'titleText',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'instituteName'); ?>
		<?php echo $form->textField($model,'instituteName',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'emailFrom'); ?>
		<?php echo $form->textField($model,'emailFrom',array('size'=>40,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'emailServer'); ?>
		<?php echo $form->textField($model,'emailServer',array('size'=>40,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'emailPassword'); ?>
		<?php echo $form->passwordField($model,'emailPassword',array('size'=>40,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->checkBox($model,'useEzTexting'); ?>
		<?php echo $form->labelEx($model,'useEzTexting'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ezTextingUsername'); ?>
		<?php echo $form->textField($model,'ezTextingUsername'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ezTextingPassword'); ?>
		<?php echo $form->passwordField($model,'ezTextingPassword'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bbText'); ?>
		<?php echo $form->textArea($model,'bbText',array('rows'=>6, 'cols'=>60)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>
</div>
