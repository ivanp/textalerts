<?php
$this->breadcrumbs=array(
	'Registration',
);
?>
<h1>Congratulations!</h1>
<p>Registration has been completed. Your password will be sent to <?php echo $model->email; ?>. Thanks!</p>
<p><a href="<?php echo $this->createUrl('user/login')?>">Click here to login to your account.</a></p>