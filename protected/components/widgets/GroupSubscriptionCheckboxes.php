<?php

class GroupSubscriptionCheckboxes extends CWidget
{
	/**
	 *
	 * @var User
	 */
	public $user;
	
	/**
	 *
	 * @var Group
	 */
	public $group;
	
	public $class='';

	const IdPrefix = 'groupscb';

	static private $idCounter = 0;

	public function init()
	{
		//var_dump(get_class($this->user), $this->group->id);
//		if (!isset($this->id)) {
//			$this->id = sprintf('%s_%d_%d_%d',
//				self::IdPrefix,
//				$this->group->id,
//				$this->user->id,
//				++self::$idCounter
//			);
//		}

		$this->class=trim($this->class.' groupsubcb');

		
	}

	public function run()
	{
	 	$id = $this->getId();
	 	echo CHtml::openTag('span', array('id'=>$this->id, 'class'=>$this->class));
		// Mail
		$mailCbId = $id.'_mail';
		echo CHtml::checkBox($mailCbId, $this->group->isUserSubscribed($this->user, 'mail'), array('id'=>$mailCbId,'class'=>'cb_mail'));
		echo CHtml::label(Yii::t('group', 'E-mail'), $mailCbId);
		// Text
		$textcbId = $id.'_text';
		echo CHtml::checkBox($textcbId, $this->group->isUserSubscribed($this->user, 'text'), array('id'=>$textcbId,'class'=>'cb_text'));
		echo CHtml::label(Yii::t('group', 'Text'), $textcbId);

		echo CHtml::closeTag('span');

		$subUrl=Yii::app()->createCompanyUrl($this->group->company, 'group/subscribe', array('group_id'=>$this->group->id,'user_id'=>$this->user->id));
		$unsubUrl=Yii::app()->createCompanyUrl($this->group->company, 'group/unsubscribe', array('group_id'=>$this->group->id,'user_id'=>$this->user->id));

		$json = json_encode(array('container'=>'#'.$id,'subUrl'=>$subUrl,'unsubUrl'=>$unsubUrl));
		$script = "App.widgets.SubscriptionCheckbox.init_cb(".$json.")";

		$cs = Yii::app()->getClientScript();
		$cs->registerScriptFile(Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot').'/js/widgets/scb.js'), CClientScript::POS_HEAD);
		$cs->registerScript($id, $script, CClientScript::POS_READY);
	}

	public function renderContent()
	{
		
	}
}