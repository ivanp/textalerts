<?php
$model = new LoginForm();
if (!isset($returnUrl))
{
	$request = Yii::app()->getRequest();
	$returnUrl = $request->getHostInfo().$request->getRequestUri();
	Yii::app()->user->setReturnUrl($returnUrl);
}
?><div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'action'=>$this->createFrontUrl('user/login'),
	'enableAjaxValidation'=>false
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email'); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Login'); ?> <a href="#">Register</a>
	</div>

<?php $this->endWidget(); ?>

	<p><a href="<?php  ?>">I forgot my password</a></p>
</div><!-- form -->