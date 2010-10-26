<?php

class CreateNewMessageForm extends CFormModel
{
	public $group_id;
	public $message;

	public $sendTypes = array('now','scheduledevery','');
	
	public $sendType;

	public $when;

	// Send now options

	// Send simple schedule options (daily, monthly, yearly)
	public $simpleType;

	// Send at specific time
	public $sendSpecificTime;
	public $sendSpecificRepeat;

}