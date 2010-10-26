<?php

/**
 * This is the model class for table "Group".
 *
 * The followings are the available columns in table 'Group':
 */
class Group extends CompanyActiveRecord
{
	public static function baseTableName()
	{
		return 'group';
	}

	public static function createSqlByCompany(Company $company)
	{
		$tableName = self::tableNameByCompany($company, self::baseTableName());
		return "CREATE TABLE IF NOT EXISTS $tableName (
  `id` INT NOT NULL ,
  `name` VARCHAR(40) CHARACTER SET 'ascii' COLLATE 'ascii_general_ci' NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `description` TEXT NULL ,
  `created` DATETIME NULL ,
  `updated` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name` (`name` ASC) )
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
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
//			array('', 'safe', 'on'=>'search'),
			array('title', 'required'),
		);
	}


//	static public function getAvailableMessageGroups($user = null) {
//		if (!($user instanceof User)) {
//			$user = User::getLoggedUser();
//		}
//
//		$mail_alerts = $user->mail_alerts;
//		$ids = array();
//		foreach ($mail_alerts as $row) {
//			$ids[] = $row->message_group_id;
//		}
//
//		if (count($ids)) {
//			$conn = Yii::app()->db;
//			$groups = Group::model()->findAll('message_group_id NOT IN ('.join(',', $ids).')');
//		} else {
//			$groups = Group::model()->findAll();
//		}
//		return $groups;
//	}
//
//	static public function getAvailableTextGroups($user = null) {
//		if (!($user instanceof User)) {
//			$user = User::getLoggedUser();
//		}
//
//		$text_alerts = $user->text_alerts;
//		$ids = array();
//		foreach ($text_alerts as $row) {
//			$ids[] = $row->message_group_id;
//		}
//
//		if (count($ids)) {
//			$conn = Yii::app()->db;
//			$groups = Group::model()->findAll('message_group_id NOT IN ('.join(',', $ids).')');
//		} else {
//			$groups = Group::model()->findAll();
//		}
//		return $groups;
//	}

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

		return new CActiveDataProvider('Group', array(
			'criteria'=>$criteria,
		));
	}

	public function primaryKey()
	{
		return 'id';
	}

	public function createViewUrl()
	{
		return Yii::app()->createCompanyUrl($this->company, 'group/view', array('id'=>$this->id));
	}

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		$subscription_model = Subscription::modelByCompany($this->company);
		return array(
			'subscribers' => array(self::HAS_MANY, get_class($subscription_model), 'group_id')
		);
	}

	public function getUsers()
	{
	  //User::model()->findAl
	}

//	public function __get($name)
//	{
//		if ($name=='subscribers')
//			return Subscription::modelByCompany($this->company)->findAll();
//		else
//			return parent::__get($name);
//	}
//
//	public function __call($name,$parameters)
//	{
//		if ($name=='subscribers')
//		{
//			$condition = isset($parameters[0]['condition'])
//				? $parameters[0]['condition']
//				: '';
//			return Subscription::modelByCompany($this->company)->findAll($condition, $parameters[0]);
//		}
//		else
//			return parent::__call($name, $parameters);
//	}
}