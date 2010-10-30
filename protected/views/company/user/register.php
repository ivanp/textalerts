<?php
$this->breadcrumbs=array(
	'Registration',
);

?>

<h1>Registration</h1>

<p>If you would like to begin receiving email or text messages to advise you of school delays and closings, please enter your first name, last name and email address. After you enter your information, we will send you an email with your password. Once you have a password you may use it to login to the site and then register your cell phone to receive text messages. </p>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

	