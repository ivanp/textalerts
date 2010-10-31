<?php

class Company extends CActiveRecord
{
	public $ownerEmail;
	public $ownerFirstName;
	public $ownerLastName;

	private $_createModel = array('User','PhoneNumber', 'Group','Subscription','GroupMessage','MessageLog','StatusUpdate');

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

//	public function __construct()
//	{
////		self::$_counter++;
//
//		parent::__construct();
//
////		error_log(self::$_counter.':__construct()');
//	}

	public function tableName()
	{
		return 'company';
	}

	public function relations()
	{
//		error_log(CActiveRecord::$_counter.':relations()');
//		if (isset($this->id))
//						error_log('with ID='.$this->id);
//		error_log(self::$_counter.':relations()');
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
//		if (isset($this->id)) {
//			$group_model = get_class(Group::modelByCompany($this));
//		} else {
//			$group_model = 'Group';
//		}
		return array(
			'info' => array(self::HAS_ONE, 'CompanyInfo', 'company_id'),
//			'owner' => array(self::BELONGS_TO, 'User', 'user_id'),
			
//			'administrators' => array(self::MANY_MANY, 'User', 'members(company_id, user_id)',
//					'condition' => "level = 'admin'"
//					),
//			'senders' => array(self::MANY_MANY, 'User', 'members(company_id, user_id)',
//					'condition' => "level = 'sender'"
//					),
//			'members' => array(self::MANY_MANY, 'User', 'members(company_id, user_id)',
//					'condition' => "level = 'member'"
//					),
		);
	}

	public function getOwner()
	{
		return User::modelByCompany($this)->findByPk(1);
	}

	public function getAdministrators()
	{
		return User::modelByCompany($this)->findAll('level=:level', array(':level'=>'admin'));
	}

	public function getSenders()
	{
		return User::modelByCompany($this)->findAll('level=:level', array(':level'=>'sender'));
	}

	public function getMembers()
	{
		return User::modelByCompany($this)->findAll('level=:level', array(':level'=>'member'));
	}

	public function getAllUsers()
	{
		return User::modelByCompany($this)->findAll();
	}

	public function isAdministrator(User $user)
	{
		if ($user->id == $this->owner->id)
			return true;
		$admin = User::modelByCompany($this)->find('id=:user_id and level=:level', array(':user_id'=>$user->id,':level'=>'admin'));
		return ($admin instanceof User);
	}

	public function isSender(User $user)
	{
		$sender = User::modelByCompany($this)->find('id=:user_id and level=:level', array(':user_id'=>$user->id,':level'=>'sender'));
		return ($sender instanceof User);
	}

	public function createUrl($route, $params = array(), $ampersand = '&')
	{
		return Yii::app()->createCompanyUrl($this,$route,$params,$ampersand);
	}

	/**
	 * @return Group
	 */
	public function groupModel()
	{
		return Group::modelByCompany($this);
	}

	public function getGroups($condition = '', $params = array())
	{
		return $this->groupModel()->findAll($condition, $params);
	}

	public function rules()
	{
		return array(
			array('name, host', 'required'),
			array('name, host', 'length', 'max' => 255),
			array('ownerEmail, ownerFirstName, ownerLastName', 'required', 'on' => 'insert'),
			array('ownerEmail', 'email', 'on'=>'insert'),
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

	protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			if ($this->getIsNewRecord())
				$this->created = date('Y-m-d H:i:s');
			else
				$this->updated = date('Y-m-d H:i:s');

			return true;
		}
		else
			return false;
	}

	public function insert($attributes=null)
	{
		if (parent::insert($attributes))
		{
			$connection = Yii::app()->db;
			// Create necessary tables
			foreach ($this->_createModel as $class)
			{
				$sql = call_user_func(array($class, 'createSqlByCompany'), $this);
				$command = $connection->createCommand($sql);
				$command->execute();
			}

			$user = User::factoryByCompany($this);
			$user->email = $this->ownerEmail;
			$user->first_name = $this->ownerFirstName;
			$user->last_name = $this->ownerLastName;
			$user->save();

			return true;
		}
		else
			return false;
	}


//	public function __get($name)
//	{
//		if ($name=='groups')
//			return Group::modelByCompany($this)->findAll();
//		else
//			return parent::__get($name);
//	}
//
//	public function __call($name,$parameters)
//	{
//		if ($name=='groups')
//		{
//			$condition = isset($parameters[0]['condition'])
//				? $parameters[0]['condition']
//				: '';
//			return Group::modelByCompany($this)->findAll($condition, $parameters[0]);
//		}
//		else
//			return parent::__call($name, $parameters);
//	}
}