<?php

class Company extends CActiveRecord
{
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
		foreach ($this->administrators as $admin)
		{
			if ($admin->id == $user->id)
			{
				return true;
			}
		}
		return false;
	}

	public function isSender(User $user)
	{
		foreach ($this->senders as $admin)
		{
			if ($admin->id == $user->id)
			{
				return true;
			}
		}
		return false;
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