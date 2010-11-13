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

	static private $loaded = false;

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
	 	$id = sprintf('gscb_%d_%d',$this->group->id,$this->user->id);
	 	echo CHtml::openTag('span', array('id'=>$id, 'class'=>$this->class));
		// Mail
		$mailCbId = $id.'_mail';
		echo CHtml::checkBox($mailCbId, $this->group->isUserSubscribed($this->user, 'mail'), array('id'=>$mailCbId,'class'=>'cb_mail'));
		echo CHtml::label(Yii::t('group', 'E-mail'), $mailCbId);
		// Text
		$textcbId = $id.'_text';
		echo CHtml::checkBox($textcbId, $this->group->isUserSubscribed($this->user, 'text'), array('id'=>$textcbId,'class'=>'cb_text'));
		echo CHtml::label(Yii::t('group', 'Text'), $textcbId);

		echo CHtml::closeTag('span');

//		$subUrl=Yii::app()->createCompanyUrl($this->group->company, 'group/subscribe', array('group_id'=>$this->group->id,'user_id'=>$this->user->id));
//		$unsubUrl=Yii::app()->createCompanyUrl($this->group->company, 'group/unsubscribe', array('group_id'=>$this->group->id,'user_id'=>$this->user->id));

		$cs = Yii::app()->getClientScript();
		$cs->registerScriptFile(Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot').'/js/widgets/scb.js'), CClientScript::POS_HEAD);

		if (!self::$loaded)
		{
			$script='App.widgets.SubscriptionCheckbox.init('.json_encode(
				array('url'=>Yii::app()->createCompanyUrl($this->group->company,'group/subscription'))).');';
			$cs->registerScript('initcblib', $script, CClientScript::POS_READY);
			self::$loaded=true;
		}
		$script = "App.widgets.SubscriptionCheckbox.init_cb(".json_encode(array('container'=>'#'.$id,'group_id'=>$this->group->id,'user_id'=>$this->user->id)).");";
		$cs->registerScript($id, $script, CClientScript::POS_READY);
	}

	public function renderContent()
	{
		
	}
}