<?php

class User extends CompanyActiveRecord
{
	public $password_repeat;

	public $password_modified = false;

	public static function baseTableName()
	{
		return 'user';
	}

	public static function createSqlByCompany(Company $company)
	{
		$tableName = self::tableNameByCompany($company, self::baseTableName());
		return "CREATE TABLE IF NOT EXISTS $tableName (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `first_name` VARCHAR(45) NULL ,
  `last_name` VARCHAR(45) NULL ,
  `email` VARCHAR(255) CHARACTER SET 'ascii' COLLATE 'ascii_general_ci' NULL ,
  `password` VARCHAR(45) NULL ,
  `created` VARCHAR(45) NULL ,
  `updated` DATETIME NULL ,
  `level` ENUM('member','sender','admin') NULL DEFAULT 'member' ,
  `code` VARCHAR(5) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `email` (`email` ASC) ,
  INDEX `level` (`level` ASC) )
ENGINE = MyISAM";
	}

	public static function modelByCompany(Company $company)
	{
		return parent::modelByCompany($company, __CLASS__);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('email','required'),
			array('first_name, last_name','required','on'=>'register,update'),
			array('first_name, last_name', 'length', 'max' => 40),
			array('email', 'email'),
			array('email', 'unique'),
//			array('password', 'required', 'on'=>'insert,register'),
			array('password', 'compare', 'compareAttribute' => 'password_repeat', 'on' => 'update'),
			array('password', 'length', 'min' => 4, 'on' => 'update,resetpassword'),
			array('password_repeat', 'safe', 'on' => 'update'),
			array('code','required','on'=>'recoverpassword'),
			array('code,level', 'unsafe')
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'phone' => array(self::HAS_ONE, $this->getCompanyClass('PhoneNumber'), 'user_id'),
			'subscriptions' => array(self::HAS_MANY, $this->getCompanyClass('Subscription'), 'user_id')
		);
	}

	public function getCompanies()
	{
		return array($this->company);
	}

	public function havePhoneNumber()
	{
		return ($this->phone instanceof PhoneNumber) && !empty($this->phone->number);
	}

	public function isPhoneConfirmed()
	{
		return ($this->havePhoneNumber() && $this->phone->confirmed);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'email' => 'Email',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'password_repeat' => 'Repeat Password'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		return new CActiveDataProvider('User', array(
			'criteria'=>$criteria,
		));
	}

	public function primaryKey()
	{
		return 'id';
	}
	
	/**
	 * This is invoked before the record is saved.
	 * @return boolean whether the record should be saved.
	 */
	protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			if (isset($this->password) && $this->password_modified)
			{
				if (strlen($this->password))
					$this->password=$this->hashPassword($this->password);
				else
					unset($this->password);
			}


//			if (isset($this->password) && 0==strlen($this->password))
//				unset($this->password);
//
//			if($this->password_modified && isset($this->password) && strlen($this->password))
//				$this->password = $this->hashPassword($this->password);
//			else
//				unset($this->password);
			if ($this->getIsNewRecord())
				$this->created = date('Y-m-d H:i:s');
			else
				$this->updated = date('Y-m-d H:i:s');
			return true;
		}
		else
			return false;
	}

	protected function afterSave()
	{
		parent::afterSave();
		$phone = $this->phone;
		if (!($phone instanceof PhoneNumber))
		{
			$phone = PhoneNumber::factoryByCompany($this->company);
			$phone->user_id = $this->id;
			$phone->save();
		}
	}

	protected function hashPassword($string)
	{
		return sha1($string);
	}

	public function generatePassword()
	{
		$chars = "abcdefghijkmnpqrstuvwxyz23456789ABCDEFGHJKLMNPQRSTUVWXYZ";
		$i = 0;
		$password = '' ;
		while ($i <= 4) {
			$num = rand() % 58;
			$tmp = substr($chars, $num, 1);
			$password .= $tmp;
			$i++;
		}
		$this->password = $password;
		return $password;
	}

	/**
	 * @return User
	 */
	static public function getLoggedUser()
	{
		if (!Yii::app()->user->isGuest) {
			return User::modelByCompany(Yii::app()->getCompany())->findByPk(Yii::app()->user->id);
		} else {
			return null;
		}
	}

	public function isAdmin()
	{
		return (bool)$this->admin;
	}

	public function getDisplayName()
	{
		$name = ucwords(strtolower(trim($this->first_name.' '.$this->last_name)));
		if (!strlen($name)) {
			$name = $this->email;
		}
		return $name;
	}

	public function createCompanyViewUrl(Company $company)
	{
		return Yii::app()->createCompanyUrl($company, 'user/view', array('id'=>$this->id));
	}

	public function createCompanyEditUrl(Company $company)
	{
		return Yii::app()->createCompanyUrl($company, 'user/edit', array('id'=>$this->id));
	}

	static public function getLevelOptions()
	{
		return array(
			'member' => 'Standard member',
			'sender' => 'E-mail sender',
			'admin' => 'Administrator'
		);
	}

	public function getSubscriptions(Company $company) {
	  return Subscription::modelByCompany($company)->findAll('user_id = :user_id', array(':user_id'=>$this->id));
	}

	public function createViewUrl()
	{
		return Yii::app()->createFrontUrl('user/view', array('id'=>$this->id));
	}

	public function generateCode()
	{
		$arr = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$code = '';
		for ($i=0;$i<5;$i++)
			$code.=substr($arr, mt_rand(0, strlen($arr)-1), 1);
		$this->code=$code;
	}

	public function __set($name, $value)
	{
		if ($name=='password')
			$this->password_modified=true;
		parent::__set($name, $value);
	}

	public function limit($limit)
	{
		$this->getDbCriteria()->mergeWith(array(
			'limit'=>$limit
		));
		return $this;
	}
}