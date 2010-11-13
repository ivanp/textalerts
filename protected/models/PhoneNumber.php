<?php

/**
 * This is the model class for table "PhoneNumber".
 *
 * The followings are the available columns in table 'PhoneNumber':
 */
class PhoneNumber extends CompanyActiveRecord
{
//	public $code_entered;
	private $_confirmPhone = false;

	public function  primaryKey()
	{
		return 'user_id';
	}

	public static function baseTableName()
	{
		return 'phone';
	}

	public static function modelByCompany(Company $company)
	{
		return parent::modelByCompany($company, __CLASS__);
	}

	public static function createSqlByCompany(Company $company)
	{
		$tableName = self::tableNameByCompany($company, self::baseTableName());
		return "CREATE TABLE IF NOT EXISTS $tableName (
  `user_id` INT NOT NULL ,
  `carrier_id` INT NOT NULL ,
  `number` VARCHAR(45) NULL ,
  `code` VARCHAR(10) NULL ,
  `confirmed` TINYINT(1) NULL ,
  INDEX `fk_table1_user1` (`user_id` ASC) ,
  INDEX `fk_phone_carrier1` (`carrier_id` ASC))
ENGINE = MyISAM";
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
//			array('', 'safe', 'on'=>'search'),
//			array('number,user_id', 'required')
				array('number', 'match', 'allowEmpty' => true, 'pattern' => '#((\(\d{3}\) ?)(\d{3}-))?\d{3}-\d{4}#'),
				array('carrier_id', 'numberfilled'),
				array('carrier_id', 'exist', 'className'=>'Carrier', 'attributeName'=>'id'),
//				array('code_entered', 'safe'),
				array('number, code, confirmed, carrier_id', 'unsafe')
		);
	}

	public function numberfilled($attribute,$params)
	{
		if (strlen($this->number) && !$this->hasErrors('number'))
		{
			if (!strlen($this->carrier_id))
				$this->addError('carrier_id', 'Please select carrier');
		}
	}

	public function onUnsafeAttribute($name,$value)
	{
		if ($name=='number' || $name=='carrier_id')
		{
			if ($value != $this->$name)
			{
				$this->$name = $value;
				$this->confirmed = 0;
				$this->_confirmPhone = true;
			}
		}
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, $this->getCompanyClass('User'), 'user_id'),
			'carrier' => array(self::BELONGS_TO, 'Carrier', 'carrier_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'carrier_id' => 'Carrier'
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

		return new CActiveDataProvider('PhoneNumber', array(
			'criteria'=>$criteria,
		));
	}

	protected function afterSave()
	{
		parent::afterSave();
		if ($this->_confirmPhone && strlen($this->number) && is_numeric($this->carrier_id))
			$this->sendConfirmationCode();
	}

	protected function beforeSave()
	{
		if (parent::beforeSave())
		{
			if (empty($this->number))
				$this->carrier_id=null;
			if ($this->_confirmPhone)
				$this->generateCode();
			return true;
		}
		else
			return false;
	}
	
	public function getNormalizedNumber()
	{
		return preg_replace('#[^\d]+#', '', $this->number);
	}

	public function getSmsMailGateway()
	{
		$carrier = $this->carrier;
		if ($carrier instanceof Carrier)
			return $this->getNormalizedNumber().'@'.$carrier->domain;
		else
			return null;
	}

	public function generateCode()
	{
		$arr = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$code = '';
		for ($i=0;$i<5;$i++)
			$code.=substr($arr, mt_rand(0, strlen($arr)-1), 1);
		$this->code=$code;
	}

	public function sendConfirmationCode()
	{
		if (!$this->user->havePhoneNumber() || $this->user->isPhoneConfirmed())
			return;
		
		$body = sprintf('phoneduck.com confirmation code: '.$this->code);
		$to_mail = $this->getSmsMailGateway();
		CompanyMailer::sendMessage($this->company->owner->email, $to_mail, $body, 'Confirmation Code');
	}
}