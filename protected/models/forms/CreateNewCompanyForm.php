<?php

class CreateNewCompanyForm extends CFormModel
{
	public $name;
	public $ownerEmail;
	public $host;

	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('name, host, ownerEmail', 'required'),
			array('name, host', 'length', 'max' => 255),
			array('ownerEmail', 'email'),
			array('host', 'unique')
		);
	}

	public function attributeLabels()
	{
		return array(
			'name' => 'Company Name',
			'ownerEmail' => 'Owner\'s E-mail',
			'host' => 'URL'
		);
	}
}