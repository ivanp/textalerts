<?php

/**
 * This is the model class for table "MessageQueue".
 *
 * The followings are the available columns in table 'MessageQueue':
 */
class MessageQueue extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return MessageQueue the static model class
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
		return 'queues';
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
			array('', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
		);
	}

	/**
	 *
	 * @return GroupMessage
	 */
	public function getMessage()
	{
		return GroupMessage::modelByCompany($this->company)->findByPk($this->message_id);
	}

	protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			if ($this->getIsNewRecord())
				$this->created = date('Y-m-d H:i:s');

			return true;
		}
		else
			return false;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
		);
	}
}