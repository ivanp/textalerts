<?php
$this->pageTitle=Yii::app()->name . ' - Profile';
$this->breadcrumbs=array(
	'Profile',
);

$hasErrors = $user->hasErrors() || $phone->hasErrors();
?>
<h1>Profile</h1>

<?php if(Yii::app()->user->hasFlash('user-profile')): ?>
<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('user-profile'); ?>
</div>
<?php endif; ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array('htmlOptions'=>array('autocomplete'=>'off'))); ?>

	<?php echo $form->errorSummary(array($user, $phone)); ?>

<fieldset>
	<h3>Contact Info</h3>
	<div class="row">
		<?php echo $form->labelEx($user,'first_name'); ?>
		<?php echo $form->textField($user,'first_name',array('size'=>30,'maxlength'=>40)); ?>
		<?php echo $form->error($user,'first_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($user,'last_name'); ?>
		<?php echo $form->textField($user,'last_name',array('size'=>30,'maxlength'=>40)); ?>
		<?php echo $form->error($user,'last_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($user,'email'); ?>
		<?php echo $form->textField($user,'email',array('size'=>40,'maxlength'=>255)); ?>
		<?php echo $form->error($user,'email'); ?>
	</div>
</fieldset>

<fieldset>
	<h3>Password</h3>
	<span>If you would like to change the password type a new one. Otherwise leave this blank.</span>
	<div class="row">
		<?php echo $form->labelEx($user,'password'); ?>
		<?php echo $form->passwordField($user,'password'); ?>
		<?php echo $form->error($user,'password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($user,'password_repeat'); ?>
		<?php echo $form->passwordField($user,'password_repeat'); ?>
		<?php echo $form->error($user,'password_repeat'); ?>
	</div>
</fieldset>

<fieldset>
	<h3>Phone</h3>
	<a name="phone"></a>
			<span>Please use this format: 111-222-3333</span>
	<div class="row">
		<?php echo $form->labelEx($phone,'number'); ?>
		<?php echo $form->textField($phone,'number',array('size'=>10,'maxlength'=>40)); ?>
		<?php echo $form->error($phone,'number'); ?>
		<?php if ($own_profile && !$hasErrors && User::getLoggedUser()->havePhoneNumber()): ?>
			<?php if ($user->isPhoneConfirmed()): ?>
			<span class="number_confirmed">Confirmed</span>
			<?php else: ?>
			<span class="number_confirmed" style="display:none"></span>
			<span class="number_unconfirmed"><span class="number_unconfirmed_msg">Unconfirmed!</span> <a href="<?php echo $this->createUrl('phone/confirm');?>">Click here to enter your confirmation code.</a></span>
			
				<span style="display:none" id="phone_confirm_input">
					Enter your confirmation code: <?php echo $form->textField($phone,'code',array('size'=>5,'maxlength'=>10)); ?>
					<input type="button" value="Confirm!"/>
				</span>
			<?php endif; ?>
		<?php endif; ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($phone,'carrier_id'); ?>
		<?php echo $form->dropDownList($phone,'carrier_id',$carriers,array('prompt'=>'Select carrier:')); ?>
		<?php echo $form->error($phone,'carrier_id'); ?>
	</div>
</fieldset>

<?php if ($set_privilege): ?>
<fieldset>
	<h3>Privilege</h3>
	<div class="row oneliner">
		<?php 
		echo $form->radioButtonList($user,'level',array('member'=>'Subscriber','sender'=>'Message Sender','admin'=>'Administrator'));
		
		?>
	</div>
</fieldset>
<?php endif; ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Update Profile'); ?>
	</div>

<?php $this->endWidget(); ?>



<?php

//if ($user->havePhoneNumber() && $user->isPhoneConfirmed())
//{
//	$attr = 'code_entered';
//	$id = CHtml::getIdByName(CHtml::resolveName($phone,$attr));
//	$submit_url = $this->createUrl('user/confirmphone');
//
//	$script =<<<SCRIPT
//
//$('.number_unconfirmed a').click(function() {
//	$(this).fadeOut('fast', function() {
//		var input = $('#phone_confirm_input');
//		input.fadeIn();
//		$('#{$id}').focus();
//	});
//
//	$('#phone_confirm_input input[type=button]').click(function() {
//		var code_textbox = $('#{$id}');
//		if (code_textbox.val().replace(/^\s*/, "").replace(/\s*$/, "").length==0) {
//			alert("Hey come on it's empty!");
//			code_textbox.focus();
//			return false;
//		}
//		var confirm_msg = $('.number_confirmed');
//		var unconfirm_msg = $('.number_unconfirmed .number_unconfirmed_msg');
//		var confirm_input = $('#phone_confirm_input');
//		confirm_input.fadeOut("fast");
//		unconfirm_msg.html("Processing confirmation code, please wait a few seconds.");
//
//		var input = $('#{$id}');
//		var code = input.val();
//		$.ajax({
//			url: "$submit_url",
//			type: "POST",
//			data: {"code": code},
//			success: function(response) {
//				response = jQuery.parseJSON(response);
//				if (response.status) {
//					unconfirm_msg.hide();
//					confirm_input.hide();
//					confirm_msg.html(response.message);
//					confirm_msg.fadeIn();
//				} else {
//					alert(response.message);
//					unconfirm_msg.html("Unconfirmed!");
//					confirm_input.fadeIn("fast", function() {
//						code_textbox.val("");
//						code_textbox.focus();
//					});
//				}
//			},
//			error: function() {
//				alert("Oops... there's something wrong with the request, please refresh the page");
//				location.reload();
//			}
//		});
//	});
//return false;
//});
//
//
//SCRIPT;
//
//	$cs = Yii::app()->getClientScript();
//	$cs->registerScript($id, $script, CClientScript::POS_READY);
//}

