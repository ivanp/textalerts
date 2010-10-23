<?php

class CompanySettingsForm extends CFormModel
{
	public $headingText;
	public $titleText;
	public $instituteName;
	public $emailFrom;
	public $emailServer;
	public $emailPassword;

	public $useEzTexting;
	public $ezTextingUsername;
	public $ezTextingPassword;

	public $bbText;

	public function rules()
	{
		return array(
			array('heading_text,title_text,institute_name,email_from,email_server', 'required'),
			array('email_from', 'email')
		);
	}

	public function attributeLabels()
	{
		return array(
			'useEzTexting'=>'Use EZ Texting',
			'ezTextingUsername'=>'EZ Texting Username',
			'ezTextingPassword'=>'EZ Texting Password',
			'bbText'=>'Bulletin Board Text'
		);
	}
}