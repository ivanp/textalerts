<?php
$this->pageTitle=Yii::app()->name . ' - Company Settings';
$this->breadcrumbs=array(
	'Company Settings',
);
?>
<h1>Company Settings</h1>

<?php if(Yii::app()->user->hasFlash('message-settings')): ?>
<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('message-settings'); ?>
</div>
<?php endif; ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm',array('htmlOptions'=>array('enctype'=>'multipart/form-data'))); ?>

	<?php echo $form->errorSummary(array($company, $info)); ?>

	<div class="row">
		<?php echo $form->labelEx($company,'name'); ?>
		<?php echo $form->textField($company,'name',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($info,'heading'); ?>
		<?php echo $form->textField($info,'heading',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($info,'title'); ?>
		<?php echo $form->textField($info,'title',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($info,'email_from'); ?>
		<?php echo $form->textField($info,'email_from',array('size'=>40,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($info,'email_server'); ?>
		<?php echo $form->textField($info,'email_server',array('size'=>40,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($info,'email_pass'); ?>
		<?php echo $form->passwordField($info,'email_pass',array('size'=>40,'maxlength'=>255)); ?>
	</div>

	<div class="row oneliner">
		<?php echo $form->checkBox($info,'use_eztext'); ?>
		<?php echo $form->labelEx($info,'use_eztext'); ?>
	</div>

	<div class="row eztext_field">
		<?php echo $form->labelEx($info,'eztext_user'); ?>
		<?php echo $form->textField($info,'eztext_user'); ?>
	</div>

	<div class="row eztext_field">
		<?php echo $form->labelEx($info,'eztext_pass'); ?>
		<?php echo $form->passwordField($info,'eztext_pass'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($info,'bb_label'); ?>
		<?php echo $form->textField($info,'bb_label'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($info,'bb_text'); ?>
		<?php echo $form->textArea($info,'bb_text',array('rows'=>6, 'cols'=>60)); ?>
	</div>
	
	<div class="row">
		<?php $tz_data=CHtml::listData($time_zones,'id','text','group'); ?>
		<?php echo $form->labelEx($info, 'time_zone'); ?>
		<?php echo $form->dropDownList($info,'time_zone',$tz_data,array('prompt'=>'Select timezone:',
				'class'=>'timezone-select')); ?> 
	</div>

	<div class="row">
		<?php echo $form->labelEx($info,'img_logo'); ?>
		<span class="img_logo"><?php
			$logo=$this->company->getLogoUrl();
			if ($logo!==false)
				echo CHtml::image($logo);
			?></span>
		<?php echo $form->fileField($info,'img_logo',array('rows'=>6, 'cols'=>60)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>
</div>

<?php

$attr = 'use_eztext';
$id = CHtml::getIdByName(CHtml::resolveName($info,$attr));

$script =<<<SCRIPT

function ezTextCheckbox()
{
	if (\$('#$id').attr('checked'))
		\$('.eztext_field').slideDown();
	else
		\$('.eztext_field').slideUp();
}

\$('#$id').click(function() {
	ezTextCheckbox();
});

ezTextCheckbox();

SCRIPT;

$attr = 'use_eztext';

$cs = Yii::app()->getClientScript();
$cs->registerScript($id, $script, CClientScript::POS_READY);
?>