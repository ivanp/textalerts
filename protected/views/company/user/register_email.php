<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  </head>
  <body>
    <p>Thank you for subscribing to <?php echo Yii::app()->name ?>.</p>
		<p>Your Password is: <b><?php echo $password?></b></p>
		<p>
        You will need to <a href="<?php echo $loginUrl; ?>">login
        </a> to your account and choose which alert groups
        you would like to receive notifications for.  You may also
        <a href="<?php echo $resetPwdUrl ?>">change your password to something easier to remember by following this link.</a>
		</p>
  </body>
</html>
