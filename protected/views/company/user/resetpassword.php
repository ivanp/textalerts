<?php
$this->pageTitle=Yii::app()->name . ' - Reset Password';
$this->breadcrumbs=array(
	'Reset Password',
);

?>
<h1>Reset Password</h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary(array($model, $user)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>30,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password_repeat'); ?>
		<?php echo $form->passwordField($model,'password_repeat',array('size'=>30,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'password_repeat'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Reset my password!'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->