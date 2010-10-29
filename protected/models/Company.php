<?php

class Company extends CActiveRecord
{
	public $ownerEmail;
	
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
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
//		if (isset($this->id)) {
//			$group_model = get_class(Group::modelByCompany($this));
//		} else {
//			$group_model = 'Group';
//		}
		return array(
			'owner' => array(self::BELONGS_TO, 'User', 'user_id'),
			'administrators' => array(self::MANY_MANY, 'User', 'members(company_id, user_id)',
					'condition' => "level = 'admin'"
					),
			'senders' => array(self::MANY_MANY, 'User', 'members(company_id, user_id)',
					'condition' => "level = 'sender'"
					),
			'members' => array(self::MANY_MANY, 'User', 'members(company_id, user_id)',
					'condition' => "level = 'member'"
					),
//			'allUsers' => array(self::MANY_MANY, 'User', 'members(company_id, user_id)'),
//			'groups' => array(self::MANY_MANY, $group_model, 'company_groups(company_id, group_id)')
		);
	}

	public function isAdministrator(User $user)
	{
		if ($user->getIsNewRecord())
			return false;
		return (current($this->administrators(array('condition' => 'user_id = '.$user->id))) instanceof User);
	}

	public function isSender(User $user)
	{
		if ($user->getIsNewRecord())
			return false;
		return (current($this->senders(array('condition' => 'user_id = '.$user->id))) instanceof User);
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

	protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			$user = User::model()->find('email=:email', array(':email'=>$this->ownerEmail));
			if (!($user instanceof User))
			{
				$user = new User();
				$user->email = $this->ownerEmail;
				$user->first_name = '-';
				$user->last_name = '- ';
				$user->save();
			}
			$this->user_id = $user->id;

			if ($this->getIsNewRecord())
				$this->created = date('Y-m-d H:i:s');
			else
				$this->updated = date('Y-m-d H:i:s');

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