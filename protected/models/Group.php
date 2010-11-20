<?php

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
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(40) CHARACTER SET 'ascii' COLLATE 'ascii_general_ci' NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `description` TEXT NULL ,
  `created` DATETIME NULL ,
  `updated` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name` (`name` ASC) ,
  INDEX `title` (`title` ASC) )
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

	protected function beforeSave()
	{
		if (parent::beforeSave())
		{
			if ($this->getIsNewRecord())
				$this->created = date('Y-m-d H:i:s');
			else
				$this->updated = date('Y-m-d H:i:s');


			$retries = 0;
			do {
				$title = $this->title;
				if ($retries++)
					$title .= ' '.$retries;
				$this->name = $this->normalizeText($title);
			} while ($this->find('name = :name', array(':name' => $this->name)) instanceof Group);

			return true;
		}
		else
			return false;
	}

	public function normalizeText($text)
	{
		return substr(preg_replace('#(\W|\s|_)+#', '-', strtolower(trim($text))), 0, 34);
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
		$messageModel=Message::modelByCompany($this->company);
		$msgGroupModel=MessageGroup::modelByCompany($this->company);
		return array(
			'subscribers' => array(self::HAS_MANY, $this->getCompanyClass('Subscription'), 'group_id'),
			'subscriberCount' => array(self::STAT, $this->getCompanyClass('Subscription'), 'group_id'),
//			'messages'=>array(self::HAS_MANY,get_class($msgGroupModel),$msgGroupModel->tableName().'(group_id,message_id)')
		);
	}

	public function subscribeUser(User $user, $type)
	{
		//$subscription = Subscription::modelByCompany($this->company)->find('user_id = :user_id', array(':user_id' => $user->id));
		$subscription = current($this->subscribers(array('condition'=>'user_id='.$user->id)));
		if (!($subscription instanceof Subscription))
		{
			$class = get_class(Subscription::modelByCompany($this->company));
			$subscription = new $class;
			$subscription->group_id = $this->id;
			$subscription->user_id = $user->id;
		}
		switch ($type)
		{
			case 'mail':
				$subscription->mail = 1;
				break;
			case 'text':
				$subscription->text = 1;
				break;
		}
		$subscription->save();
	}

	public function unsubscribeUser(User $user, $type)
	{
		//$subscription = Subscription::modelByCompany($this->company)->find('group_id = :group_id and user_id = :user_id', array(':user_id' => $user->id));
		$subscription = current($this->subscribers(array('condition'=>'user_id='.$user->id)));
		if ($subscription instanceof Subscription)
		{
			switch ($type)
			{
				case 'mail':
					$subscription->mail = 0;
					break;
				case 'text':
					$subscription->text = 0;
					break;
			}
			$subscription->save();
		}
	}

	public function isUserSubscribed(User $user, $type = null)
	{
		if (is_null($type))
			$subscription = current($this->subscribers(array('condition'=>'(mail=1 or text=1) and user_id='.$user->id)));
		else
		{
			switch ($type)
			{
				case 'mail':
					$subscription = current($this->subscribers(array('condition'=>'mail=1 and user_id='.$user->id)));
					break;
				case 'text':
					$subscription = current($this->subscribers(array('condition'=>'text=1 and user_id='.$user->id)));
					break;
				default:
					$subscription = false;
			}
		}
		return ($subscription instanceof Subscription);
	}

	public function getLastMessageTime()
	{
		$message=current($this->messages(array('limit'=>1,'order'=>'created DESC')));
		if ($message instanceof Message)
			return $message->created;
		else
			return null;
	}

	public function scopes()
	{
		return array(
			'sortByTitle'=>array(
				'order'=>'title'
			)
		);
	}

//	public function order()
//	{
//		$this->getDbCriteria()->mergeWith(array(
//			'limit'=>$limit
//		));
//		return $this;
//	}

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