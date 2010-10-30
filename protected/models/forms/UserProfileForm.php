<?php

class UserProfileForm extends CFormModel
{
	public $firstName;
	public $lastName;
	public $email;
	public $password;
	public $retryPassword;

	public $phoneNumber;

	public function attributeLabels()
	{
		return array(
		);
	}

	public function rules()
	{
		return array(
			array('email, firstName, firstName', 'required'),
			array('firstName, firstName', 'length', 'max' => 40),
			array('email', 'email'),
		);
	}
}