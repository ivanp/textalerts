<?php

class ForgotPasswordForm extends CFormModel
{
	public $email;

	public function attributeLabels()
	{
		return array(
			'email'=>'Your e-mail address'
		);
	}

	public function rules()
	{
		$userClass=get_class(User::modelByCompany(Yii::app()->getCompany()));
		return array(
			array('email','required'),
			array('email','email'),
			array('email','exist','className'=>$userClass,'attributeName'=>'email','skipOnError'=>true)
		);
	}
}