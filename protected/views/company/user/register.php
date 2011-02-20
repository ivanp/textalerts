<?php
$this->pageTitle=Yii::app()->name . ' - Registration';
$this->breadcrumbs=array(
	'Registration',
);

?>

<h1>Registration</h1>

<p>If you would like to begin receiving email or text messages, please enter your first name, last name and email address. After you enter your information, we will send you an email with your password. Once you have a password you may use it to login to the site and then register your cell phone to receive text messages. </p>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

	