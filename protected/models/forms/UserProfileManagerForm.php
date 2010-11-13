<?php

class UserProfileManagerForm extends CFormModel
{
	public $type;

	public function rules()
	{
		return array(
			array('type','required','on'=>'admin'),
			array('type','in','range'=>array('member','sender','admin'),'on'=>'admin')
		);
	}
}