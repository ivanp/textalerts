<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->subscriber_id=>array('view','id'=>$model->subscriber_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Create User', 'url'=>array('create')),
	array('label'=>'View User', 'url'=>array('view', 'id'=>$model->subscriber_id)),
	array('label'=>'Manage User', 'url'=>array('admin')),
);
?>

<h1>Update User <?php echo $model->subscriber_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>