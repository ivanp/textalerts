<?php
$this->pageTitle=Yii::app()->name . ' - Subscriptions';
$this->breadcrumbs=array(
	'Subscriptions',
);

?>
<h1>Subscriptions</h1>

<?php if(Yii::app()->user->hasFlash('user-subscription')): ?>
<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('user-subscription'); ?>
</div>
<?php endif; ?>

<table width="100%" border="1" id="subscribedgroups">
	<thead>
		<tr>
			<th width="50%">Name</th>
			<th>Your Subscription</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($subscribed_groups as $group) {
		 echo $this->renderPartial('_subscription_row',array('user'=>$user,'group'=>$group)); 
		 }
		 ?>
	</tbody>
</table>

<div class="clear"></div>

<?php echo CHtml::beginForm('','post',array('id'=>'addgroupform')); ?>

<div class="form">
<div id="selectgroup" style="margin-top: 16px">
	<select name="group_id">
		<option>Select group to subscribe:</option>
		<?php foreach ($available_groups as $group): ?>
		<option value="<?php echo $group->id?>"><?php echo CHtml::encode($group->title)?></option>
		<?php endforeach; ?>
	</select>
	<input type="submit" value="Subscribe"/>
</div>
</div>

<?php echo CHtml::endForm(); ?>