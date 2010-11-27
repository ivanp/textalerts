<?php

class Company extends CActiveRecord
{
	public $ownerEmail;
	public $ownerFirstName;
	public $ownerLastName;
	public $ownerPassword;

	private $_createModel = array('User','PhoneNumber', 'Group','Subscription',
			'StatusUpdate','Event','Occurrence',
			'Message','MessageGroup','MessageLog','MessageSchedule','MessageQueue');

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'company';
	}

	public function relations()
	{
		return array(
			'info' => array(self::HAS_ONE, 'CompanyInfo', 'company_id'),
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
	
	public function getManagers()
	{
		return User::modelByCompany($this)->findAll('level IN :level', array(':level'=>array('sender','admin')));
	}

	public function getMembers()
	{
		return User::modelByCompany($this)->findAll('level=:level', array(':level'=>'member'));
	}

	public function getAllUsers()
	{
		return User::modelByCompany($this)->findAll();
	}

	/**
	 *
	 * @param User $user
	 * @return bool
	 */
	public function isAdministrator($user)
	{
		if (!$user instanceof User)
			return false;
		if ($user->id == $this->owner->id)
			return true;
		$admin = User::modelByCompany($this)->find('id=:user_id and level=:level', array(':user_id'=>$user->id,':level'=>'admin'));
		return ($admin instanceof User);
	}

	/**
	 *
	 * @param User $user
	 * @return bool
	 */
	public function isSender($user)
	{
		if (!$user instanceof User)
			return false;
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
			array('ownerEmail,ownerFirstName,ownerLastName,ownerPassword', 'required', 'on' => 'insert'),
			array('ownerEmail', 'email', 'on'=>'insert'),
			array('host', 'unique')
		);
	}

	public function attributeLabels()
	{
		return array(
			'name' => 'Company Name',
//			'ownerEmail' => 'Owner\'s E-mail',
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
				$queries=call_user_func(array($class, 'createSqlByCompany'), $this);
				if (!is_array($queries))
					$queries=array($queries);
				foreach($queries as $query)
					$connection->createCommand($query)->execute();
			}

			$user = User::factoryByCompany($this);
			$user->email = $this->ownerEmail;
			$user->first_name = $this->ownerFirstName;
			$user->last_name = $this->ownerLastName;
			$user->password = $this->ownerPassword;
			$user->level='admin';
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

	public function getUploadDir()
	{
		$dir=Yii::getPathOfAlias(Yii::app()->params['uploadDir']).'/'.$this->id;
		if (!is_dir($dir))
		{
			if (!mkdir($dir,0777,true))
				throw new CException('Failed to create directory: '.$dir);
		}
		return $dir;
	}
 
	public function getLogoUrl()
	{
		$url=false;
		if (!empty($this->info->img_logo))
		{
			$logo=$this->getUploadDir().'/'.$this->info->img_logo;
			try
			{
				$url=Yii::app()->getAssetManager()->publish($logo);
			}
			catch (CException $e)
			{}
		}
		return $url;
	}
}