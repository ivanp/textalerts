<?php

/**
 * This is the model class for table "PhoneNumber".
 *
 * The followings are the available columns in table 'PhoneNumber':
 */
class PhoneNumber extends CompanyActiveRecord
{
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
			array('number,user_id', 'required')
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
			'user' => array(self::BELONGS_TO, $this->getCompanyClass('User'), 'user_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
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
}