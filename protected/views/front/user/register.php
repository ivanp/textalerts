<?php
$this->breadcrumbs=array(
	'Registration',
);

$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Manage User', 'url'=>array('admin')),
);
?>

<h1>Registration</h1>

<p>If you would like to begin receiving email or text messages to advise you of school delays and closings, please enter your first name, last name and email address. After you enter your information, we will send you an email with your password. Once you have a password you may use it to login to the site and then register your cell phone to receive text messages. </p>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

	<textarea name="comments" cols="50" rows="18" readonly="readonly">
    TERMS AND CONDITIONS

    The subscription for alerts is a recurring service;
    Message and data rates may apply per your carrier.
    must have a two way text-enabled phone with compatible
    carrier and plan. Compatible major carriers include Alltel
    Wireless, AT&amp;T, Boost Mobile, Nextel, Sprint, T-Mobile
    (R), Verizon Wireless and Virgin Mobile USA. Text messaging
    is not available in all areas of the United States. We value
    your participation and we promise to keep your information
    private and not sell to another 3rd party. A valid email
    address is required for email alerts. All messages that you
    receive MAY include a note from a sponsor - in many cases, this
    is included so that we can keep the service FREE to you
    (outside of your carrier charges - we have no control over those).
     To opt out of receiving alerts, Email admin@lakewoodalerts.com,
    clearly stating your email address, with instructions that
     you wish to be opted out of email and/or text alerts.
    </textarea>