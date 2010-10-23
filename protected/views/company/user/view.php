<?php

$this->pageTitle=Yii::app()->name . ' - '.$user->getDisplayName();
$this->breadcrumbs=array(
	'Users' => array('user/index'),
	$user->getDisplayName()
);

?>
<h2><?php echo $user->getDisplayName(); ?></h2>

<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php endif; ?>

<div class="form">
	<h3>User Info</h3>
<?php $form=$this->beginWidget('CActiveForm'); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'firstName'); ?>
		<?php echo $form->textField($model,'firstName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lastName'); ?>
		<?php echo $form->textField($model,'lastName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'level'); ?>
		<?php echo $form->dropDownList($model,'level', User::getLevelOptions(), array('prompt'=>'Select level of this user')); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'phoneNumber'); ?>
		<?php echo $form->textField($model,'phoneNumber'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'carrier'); ?>
		<?php echo $form->dropDownList($model,'carrier', array(), array('prompt'=>'Select carrier')); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>
	
<?php $this->endWidget(); ?>
</div>