<?php
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot').'/js/datepicker.js'), CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot').'/js/jquery-ui-timepicker-addon.min.js'), CClientScript::POS_HEAD);

$this->pageTitle=Yii::app()->name . ' - Create New Message';
$this->breadcrumbs=array(
	'Messages' => array('message/index'),
	'Create New'
);

?>
<h1>Send New Message</h1>

<?php if(Yii::app()->user->hasFlash('message-create')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('message-create'); ?>
</div>

<?php endif; ?>

<?php echo $this->renderPartial('_message_form', array('message'=>$message,'groups'=>$groups)); ?>