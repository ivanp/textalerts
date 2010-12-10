<?php
$this->pageTitle=Yii::app()->name . ' - Members';
$this->breadcrumbs=array(
	'Members',
);
?>
<?php if(Yii::app()->user->hasFlash('group')):?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('group'); ?>
    </div>
<?php endif; ?>

<?php echo CHtml::beginForm(); ?>
<table width="100%" border="1">
	<thead>
		<tr>
			<th width="50%">Name</th>
			<th>Options</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($users as $user): ?>
		<tr>
			<td>
				<?php
				echo CHtml::link(CHtml::encode($user->getDisplayName()), $user->createViewUrl());
				?>
			</td>
			<td>
				<a class="imgbtn edit" href="<?php echo $this->createUrl('user/profile',array('id'=>$user->id))?>">Edit</a> <a class="imgbtn delete" href="<?php echo $this->createUrl('user/delete',array('id'=>$user->id))?>">Delete</a>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php echo CHtml::endForm(); ?>

<?php if ($pages->pageCount > 1): ?>
<div class="pagelinks">
<?$this->widget('CLinkPager', array(
    'pages' => $pages,
))?>
</div>
<?php endif; ?>