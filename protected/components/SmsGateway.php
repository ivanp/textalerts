<?php

class SmsGateway extends CModule
{
	public function sendByMail(PhoneNumber $number, $message)
	{
		$domain = $number->carrier->domain;
	}
}