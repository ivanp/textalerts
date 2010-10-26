<?php
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot').'/js/datepicker.js'), CClientScript::POS_HEAD);



$this->pageTitle=Yii::app()->name . ' - Create New Message';
$this->breadcrumbs=array(
	'Messages' => array('message/index'),
	'Create New'
);
//new CActiveForm();
?>
<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php endif; ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm'); ?>

	<div class="row">
		<label>Group:</label>
		<select>
			<?php foreach ($groups as $group): ?>
			<option value="<?php echo $group->id?>"><?php echo htmlspecialchars($group->title)?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'message'); ?>
		<?php echo $form->textArea($model,'message'); ?>
	</div>

	<div class="row oneliner">
		<input type="radio" name="sendwhen" value="1" id="send_cb_1"/> <label for="send_cb_1">Send now</label>
	</div>

	<div class="row oneliner">
		<input type="radio" name="sendwhen" value="2" id="send_cb_2"/> <label for="send_cb_2">Simple schedule:</label>
		<select>
			<option>Hourly</option>
			<option>Daily</option>
			<option>Weekly</option>
			<option>Monthly</option>
			<option>Yearly</option>
		</select>
	</div>
	<div class="row">
		
	</div>

	<div class="row oneliner">
		<input type="radio" name="sendwhen" value="3" id="send_cb_3"/> <label for="send_cb_3">Advanced schedule</label>
	</div>
	<div class="row oneliner jcalendar jcalendar-selects">
		<p id="calselect"></p>

		<script type="text/javascript">
			$(document).ready(function() {
				$('#calselect').DatePicker({
					flat: true,
	date: [],
	current: '<?php echo date('Y-m-d') ?>',
	format: 'Y-m-d',
	calendars: 1,
	mode: 'multiple'
				});
			});
		</script>
	</div>

	<div class="row oneliner">
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>
</div>