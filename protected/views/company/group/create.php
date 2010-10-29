<?php
$this->pageTitle=Yii::app()->name . ' - Create Group';
$this->breadcrumbs=array(
	'Groups' => array('group/index'),
	'Create new group',
);
?>
<h1>Create New Group</h1>
<?php echo $this->renderPartial('_group_form', array('model'=>$model)); ?>