<?php

class CompanyInfo extends CActiveRecord
{
	public function primaryKey()
	{
		return 'company_id';
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function relations()
	{
		return array(
			'company'	=> array(self::BELONGS_TO, 'Company', 'company_id')
		);
	}

	public function rules()
	{
		return array(
			array('heading,title,email_from,email_server', 'required'),
			array('bb_label','length','max'=>100),
//			array('company_id', 'required', 'on'=>'insert'),
			array('company_id', 'unsafe', 'on'=>'update'),
			array('email_pass,use_eztext,eztext_user,eztext_pass,bb_text', 'safe'),
			array('email_from', 'email'),
			array('img_logo', 'file', 'types'=>'jpg, gif, png','allowEmpty'=>true)
		);
	}

	public function attributeLabels()
	{
		return array(
			'heading'=>'Heading Text',
			'title'=>'Title Text',
			'use_eztext'=>'Use EZ Texting Gateway for texts',
			'eztext_user'=>'EZ Texting User Name',
			'eztext_pass'=>'EZ Texting Password',
			'img_logo'=>'Logo picture',
			'bb_label'=>'Bulletin Board Label',
			'bb_text'=>'Bulletin Board Text'
		);
	}

	public function beforeSave()
	{
		if (parent::beforeSave())
		{
			if (!strlen($this->img_logo))
				unset($this->img_logo);
			if ($this->use_eztext)
			{
				if (empty($this->eztext_user))
					$this->addError('eztext_user', 'You must provide username for EzText');
				if (empty($this->eztext_pass))
					$this->addError('eztext_pass', 'You must provide password for EzText');
				return !$this->hasErrors();
			}
			else
				return true;
		}
		else
			return false;
	}

	public function tableName()
	{
		return 'company_info';
	}
}