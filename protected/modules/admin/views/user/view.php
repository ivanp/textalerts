<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->subscriber_id,
);

$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Create User', 'url'=>array('create')),
	array('label'=>'Update User', 'url'=>array('update', 'id'=>$model->subscriber_id)),
	array('label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->subscriber_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage User', 'url'=>array('admin')),
);
?>

<h1>View User #<?php echo $model->subscriber_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'subscriber_id',
		'email',
		'admin',
		'sender',
		'first_name',
		'last_name',
		'password',
		'crypt_email',
		'phone',
		'carrier_id',
		'text_me',
	),
)); ?>
