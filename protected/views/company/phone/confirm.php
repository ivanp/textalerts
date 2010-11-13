<?php
$this->pageTitle=Yii::app()->name . ' - Phone Number Confirmation';
$this->breadcrumbs=array(
	'Profile'=>array('user/profile'),
	'Confirm Phone Number'
);
?>
<h1>Confirm your phone number</h1>

<?php if(Yii::app()->user->hasFlash('phone-confirm')): ?>
<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('phone-confirm'); ?>
</div>
<?php endif; ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array('htmlOptions'=>array('autocomplete'=>'off'))); ?>


	<div class="row">
		Your current phone number: <strong><?php echo $phone->number ?></strong> (Carrier: <?php echo $phone->carrier->name?>) <a href="<?php echo $this->createUrl('user/profile',array('#'=>'phone'))?>">Change this number/carrier</a>
	</div>

	<div class="row oneliner">
		<?php echo $form->labelEx($model,'confirmCode'); ?>
		<?php echo $form->textField($model,'confirmCode',array('size'=>5,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'confirmCode'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Confirm!'); ?>
	</div>

	<div class="row">
		Didn't get the confirmation code yet? It might take a while. <a href="<?php echo $this->createUrl('phone/resend')?>">Click here to resend it.</a>
	</div>

<?php $this->endWidget(); ?>

