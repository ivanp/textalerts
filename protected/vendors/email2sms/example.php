<?php

/** Sample Email2SMS Usage */

require_once('class-email2sms.php');

// Creating Object
$email2sms_obj = new Email2SMS('utsavhanda@hotmail.com', 'email2sms.db');

// Setting Country and carrier information
$email2sms_obj -> setProperty('email2smsCountry', 'India');
$email2sms_obj -> setProperty('email2smsCarrier', 'Andhra Pradesh Airtel');
$email2sms_obj -> setProperty('email2smsSubject', 'SMS');
$email2sms_obj -> setProperty('email2smsNumber' , '9810112345');

// Setting SMS text
$email2sms_obj -> setProperty('email2smsText'   , "SMS text goes here");

// Voila, SMS sent!
$email2sms_obj -> prepareAndSendSMS();


// Getting errors
//echo $email2sms_obj -> getError();
//print_r($email2sms_obj -> getError('all'));

?>