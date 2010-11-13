<?php

class ConfirmPhoneForm extends CFormModel
{
	public $confirmCode;

	public function  attributeLabels() {
		return array(
			'confirmCode'	=> 'Confirmation Code'
		);
	}

	public function rules()
	{
		$code = User::getLoggedUser()->phone->code;
		return array(
			array('confirmCode', 'required'),
			array('confirmCode', 'compare', 'compareValue'=>$code, 'skipOnError'=>true,
					'message'=>'Sorry, that was not the correct confirmation code.')
		);
	}
}