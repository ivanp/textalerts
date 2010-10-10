<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('subscriber_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->subscriber_id), array('view', 'id'=>$data->subscriber_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('admin')); ?>:</b>
	<?php echo CHtml::encode($data->admin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sender')); ?>:</b>
	<?php echo CHtml::encode($data->sender); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('first_name')); ?>:</b>
	<?php echo CHtml::encode($data->first_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_name')); ?>:</b>
	<?php echo CHtml::encode($data->last_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('password')); ?>:</b>
	<?php echo CHtml::encode($data->password); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('crypt_email')); ?>:</b>
	<?php echo CHtml::encode($data->crypt_email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phone')); ?>:</b>
	<?php echo CHtml::encode($data->phone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('carrier_id')); ?>:</b>
	<?php echo CHtml::encode($data->carrier_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('text_me')); ?>:</b>
	<?php echo CHtml::encode($data->text_me); ?>
	<br />

	*/ ?>

</div>