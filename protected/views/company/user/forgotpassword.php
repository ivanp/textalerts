<?php

$this->pageTitle=Yii::app()->name . ' - Password Recovery';
$this->breadcrumbs=array(
	'Password Recovery',
);

?>
<h1>Password Recovery Form</h1>

<div class="form">
	
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>40,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Recover my password'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->