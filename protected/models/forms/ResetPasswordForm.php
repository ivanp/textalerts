<?php

class ResetPasswordForm extends CFormModel
{
	public $password;
	public $password_repeat;

	public function attributeLabels()
	{
		return array(
			'password'=>'Password',
			'password_repeat'=>'Repeat password'
		);
	}

	public function rules()
	{
		return array(
			array('password,password_repeat','required'),
			array('password', 'compare', 'compareAttribute' => 'password_repeat','skipOnError'=>true),
			array('password','length','min'=>5,'skipOnError'=>true),
		);
	}
}