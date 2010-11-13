<?php
$this->pageTitle=Yii::app()->name . ' - Edit Group';
$this->breadcrumbs=array(
	'Groups' => array('group/index'),
	'Edit group',
);
?>
<h1>Edit Group</h1>
<?php echo $this->renderPartial('_group_form', array('model'=>$group)); ?>