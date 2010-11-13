<?php if(Yii::app()->user->hasFlash('company-create')): ?>
<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('company-create'); ?>
</div>
<?php endif; ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array('htmlOptions'=>array('autocomplete'=>'off'))); ?>

	<?php echo $form->errorSummary(array($company, $info)); ?>

	<fieldset>
		<h4>Company Information</h4>
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
			<?php echo $form->labelEx($company,'host'); ?>
			<span class="oneliner"><strong>http://</strong><?php echo $form->textField($company,'host',array('size'=>20,'maxlength'=>255)); ?>
				<strong>.homeduck.com/</strong>
			</span>
		</div>
	</fieldset>

	<fieldset>
		<h4>Owner Information</h4>
		<div class="row">
			<?php echo $form->labelEx($company,'ownerFirstName'); ?>
			<?php echo $form->textField($company,'ownerFirstName',array('size'=>30,'maxlength'=>40)); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($company,'ownerLastName'); ?>
			<?php echo $form->textField($company,'ownerLastName',array('size'=>30,'maxlength'=>40)); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($company,'ownerEmail'); ?>
			<?php echo $form->textField($company,'ownerEmail',array('size'=>40,'maxlength'=>255)); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($company,'ownerPassword'); ?>
			<?php echo $form->passwordField($company,'ownerPassword'); ?>
		</div>
	</fieldset>

	<fieldset>
		<h4>E-mail Settings</h4>
		
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
	</fieldset>

	<fieldset>
		<h4>Text Settings</h4>
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
	</fieldset>

	<fieldset>
		<h4>Bulletin Board</h4>
		<div class="row">
			<?php echo $form->labelEx($info,'bb_text'); ?>
			<?php echo $form->textArea($info,'bb_text',array('rows'=>6, 'cols'=>60)); ?>
		</div>
	</fieldset>

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