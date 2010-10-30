<?php

/**
 * This is the model class for table "User".
 *
 * The followings are the available columns in table 'User':
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('email, first_name, last_name', 'required'),
			array('first_name, last_name', 'length', 'max' => 40),
			array('email', 'email'),
			array('email', 'unique')
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
			'phone' => array(self::HAS_ONE, 'PhoneNumber', 'user_id'),
			'companies' => array(self::HAS_MANY, 'Company', 'user_id'),
			'memberships' => array(self::HAS_MANY, 'Membership', 'user_id')
		);
	}

	public function havePhoneNumber()
	{
		return $this->phone instanceof PhoneNumber;
	}

	public function phoneNumberConfirmed()
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
			'last_name' => 'Last Name'
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
			if(strlen($this->password))
			{
				$this->password = $this->hashPassword($this->password);
			}
			$this->created = date('Y-m-d H:i:s');
			return true;
		}
		else
			return false;
	}

	protected function hashPassword($string)
	{
		return sha1($string);
	}

	public function generatePassword() {
		$chars = "abcdefghijkmnpqrstuvwxyz23456789ABCDEFGHJKLMNPQRSTUVWXYZ";
		$i = 0;
		$this->password = '' ;
		while ($i <= 4) {
			$num = rand() % 58;
			$tmp = substr($chars, $num, 1);
			$this->password .= $tmp;
			$i++;
		}
		return $this->password;
	}

	/**
	 * @return User
	 */
	static public function getLoggedUser()
	{
		if (!Yii::app()->user->isGuest) {
			return User::model()->find('email = :email', array(':email' => Yii::app()->user->id));
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
}