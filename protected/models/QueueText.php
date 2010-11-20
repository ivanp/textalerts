<?php

class QueueText extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'queue_text';
	}

	public function beforeSave()
	{
		static $count=0;
		if (parent::beforeSave())
		{
			if ($this->getIsNewRecord())
			{
				do
				{
					$uniqueid=sprintf('%d:%s:%d:%s:%s:%d',
							$this->company_id,
							$this->number,
							$this->carrier_id,
							$this->created_on,
							$this->hash($this->body),
							++$count
					);
					$this->id=$this->hash($uniqueid);
				}
				while (QueueMail::model()->exists('id=:id',array(':id'=>$this->id)));

				$this->created_on=time();
			}
			return true;
		}
		else
			return false;
	}

	public function hash($str)
	{
		return sha1($str);
	}
	
	public function relations()
	{
		return array(
			'company'=>array(self::BELONGS_TO,'Company','company_id')
		);
	}
}