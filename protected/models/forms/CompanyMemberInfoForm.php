<?php

class CompanyMemberInfoForm extends CFormModel
{
	public $firstName;
	public $lastName;
	public $email;
	public $level;
	public $phoneNumber;
	public $carrier;

	public function rules()
	{
		return array(
			array('firstName, lastName, email', 'required'),
			array('email', 'email')
		);
	}
}